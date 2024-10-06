<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Investment;
use App\Models\Payment;
use App\Models\RecommitLog;
use App\Models\Transaction;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $pageTitle;

    public function filterPayment($scope)
    {
        $this->pageTitle = ucfirst($scope) . ' Payments';
        $query           = Payment::orderBy('id', 'desc');
        if ($scope != 'all') {
            $query = $query->$scope();
        }
        $payments = $query->searchable(['trx'])->with(['fromUser', 'toUser', 'investment', 'investment.currency'])->paginate(getPaginate());
        return $payments;
    }

    public function index()
    {
        $segments  = request()->segments();
        $payments  = $this->filterPayment('all');
        $pageTitle = $this->pageTitle;
        return view('admin.payments.list', compact('pageTitle', 'payments'));
    }

    public function waiting()
    {
        $segments  = request()->segments();
        $payments  = $this->filterPayment('awaiting');
        $pageTitle = $this->pageTitle;
        return view('admin.payments.list', compact('pageTitle', 'payments'));
    }

    public function complete()
    {
        $segments  = request()->segments();
        $payments  = $this->filterPayment('completed');
        $pageTitle = $this->pageTitle;
        return view('admin.payments.list', compact('pageTitle', 'payments'));
    }

    public function rejected()
    {
        $segments  = request()->segments();
        $payments  = $this->filterPayment('rejected');
        $pageTitle = $this->pageTitle;
        return view('admin.payments.list', compact('pageTitle', 'payments'));
    }

    public function reported()
    {
        $segments  = request()->segments();
        $payments  = $this->filterPayment('reported');
        $pageTitle = $this->pageTitle;
        return view('admin.payments.list', compact('pageTitle', 'payments'));
    }

    public function detail($id = null)
    {
        $payment      = Payment::with(['chats', 'chats.user'])->findOrFail($id);
        $pageTitle    = 'Payment Details';
        $emptyMessage = 'Say hi to start conversation.';
        return view('admin.payments.details', compact('pageTitle', 'payment', 'emptyMessage'));
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|max:250',
            'file' => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt'])]
        ]);

        $payment = Payment::where('id', $id)->firstOrFail();
        $chat             = new Chat();
        $chat->payment_id = $payment->id;
        $chat->user_id    = 0;
        $chat->message    = $request->message;

        if ($request->hasFile('file')) {
            try {
                $chat->file = fileUploader($request->file('file'), getFilePath('conversation'));
            } catch (\Exception $exp) {
                return response()->json(['error' => 'Couldn\'t upload your image']);
            }
        }

        $chat->save();
        $notify[] = ['success', 'Message sent'];

        return back()->withNotify($notify);
    }

    public function takeAction(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:inFavourOfSender,inFavourOfReceiver,setBackToInitialStage'
        ]);
        $type    = $request->type;
        $payment = Payment::whereIn('status', [Status::PAYMENT_REPORT_SENDER, Status::PAYMENT_REPORT_RECEIVER])->findOrFail($id);
        $this->$type($payment);
        $notify[] = ['success', 'Decision taken successfully'];
        return back()->withNotify($notify);
    }

    protected function inFavourOfReceiver($payment)
    {
        $user             = $payment->fromUser;
        $user->ban_reason = "";
        $user->status     = 0;
        $user->save();

        $withdraw                 = $payment->withdraw;
        $withdraw->remain_amount += $payment->amount;
        $withdraw->save();

        $payment->status = Status::PAYMENT_CANCELLED;
        $payment->save();
    }

    protected function inFavourOfSender($payment)
    {
        $user             = $payment->toUser;
        $user->ban_reason = "";
        $user->status     = 0;
        $user->save();

        $trx                      = getTrx();
        $transaction              = new Transaction();
        $transaction->user_id     = $payment->fromUser->id;
        $transaction->amount      = $payment->amount;
        $transaction->trx_type    = '-';
        $transaction->currency_id = $payment->currency_id;
        $transaction->details     = 'Payment succeeded';
        $transaction->trx         = $trx;
        $transaction->save();

        $investment = $payment->investment;

        if ($investment) {
            $investment->success_amount += $payment->amount;
            if ($investment->success_amount >= $investment->amount) {
                $investment->status  = Status::INVESTMENT_COMPLETE;
                $investment->paid_at = now();
                if ($investment->is_activation == Status::INVESTMENT_ACTIVATION) {
                    $target_user = $investment->user;
                    if ($target_user) {
                        $target_user->activation = Status::INVESTMENT_ACTIVATION;
                        $target_user->save();
                    }
                    $investment->withdrawn_at = now();
                } else {
                    $plan                          = $investment->plan;
                    $investment->withdrawable_time = now()->addDays($plan->duration);
                }
                $general = gs();
                if ($general->referral) {
                    levelCommission($investment);
                }
            }
            $investment->save();
        }

        //// Recommitment
        $recommits   = RecommitLog::orderBy('id', 'desc')->where('by_invest_id', $payment->investment_id)->where('remain_amount', '>', 0)->get();
        $grossAmount = $payment->amount;
        foreach ($recommits as $recommit) {

            $weak                      = ($recommit->remain_amount < $grossAmount) ? $recommit->remain_amount : $grossAmount;
            $grossAmount              -= $weak;
            $recommit->remain_amount  -= $weak;
            $recommit->success_amount += $weak;
            if ($recommit->success_amount >= $recommit->amount) {
                $recommit->status = Status::RECOMMIT_COMPLETE;
            }
            $recommit->save();

            $recommittedFor                    = Investment::find($recommit->for_invest_id);
            $recommittedFor->recommit_success += $weak;
            if ($recommittedFor->recommit_success >= $recommittedFor->recommit_amount) {
                $recommittedFor->recommit_status = Status::RECOMMIT_COMPLETE;
            }
            $recommittedFor->save();

            if ($grossAmount <= 0) {
                break;
            }
        }

        notify($payment->fromUser, 'PAYMENT_CONFIRMED', [
            'username' => $payment->toUser->username,
            'mobile'   => $payment->toUser->mobile,
            'amount'   => getAmount($payment->amount) . ' ' . $payment->investment->currency->code,
        ]);

        $payment->status = Status::PAYMENT_COMPLETED;
        $payment->save();
    }

    protected function setBackToInitialStage($payment)
    {
        $general           = gs();
        $payment->status   = 0;
        $payment->deadline = Carbon::now()->addHour($general->payment_duration);;
        $payment->info     = null;
        fileManager()->removeFile(getFilePath('payment') . '/' . @$payment->image);
        $payment->save();
        $notify[] = ['success', 'Set back to initial stage successfully'];
        return back()->withNotify($notify);
    }
}
