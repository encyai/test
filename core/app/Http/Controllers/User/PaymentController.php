<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Chat;
use App\Models\Investment;
use App\Models\Payment;
use App\Models\RecommitLog;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalInfo;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class PaymentController extends Controller {
    public function detail($id) {
        $user      = auth()->user();
        $admin     = Admin::first();
        $pageTitle = "Payment Details";
        $payment   = Payment::where('id', $id)->firstOrFail();

        if ($payment->to_user_id == $user->id) {
            $getUser = $payment->fromUser;
            $payUser = false;
        } else {
            $payUser = $payment->toUser;
            $getUser = false;
        }

        $messages      = Chat::where('payment_id', $payment->id)->with('user')->take(10)->latest()->get();
        $receivingInfo = WithdrawalInfo::where('user_id', $payment->to_user_id)->where('currency_id', $payment->investment->currency_id)->first();
        return view($this->activeTemplate . 'user.payment_details', compact('pageTitle', 'payment', 'getUser', 'payUser', 'messages', 'admin', 'receivingInfo'));
    }

    public function paymentProved(Request $request, $id) {
        $request->validate([
            'image'       => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'information' => 'required'
        ]);

        $user = auth()->user();
        try {
            $decryptData = decrypt($id);
        } catch (\Throwable $th) {
            abort(404);
        }

        $identifyData = explode('|', $decryptData);
        if ($identifyData[1] != $user->id) {
            abort(403);
        }

        $payment = Payment::where('from_user_id', $user->id)->findOrFail($identifyData[0]);
        $payment->info = $request->information;

        if ($request->hasFile('image')) {
            try {
                $payment->image = fileUploader($request->image, getFilePath('payment'), null, $payment->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $payment->status                = Status::PAYMENT_WAITING;
        $payment->confirmation_deadline = now()->addHours(gs()->confirm_duration);
        $payment->save();
        $notify[] = ['success', 'Payment information submitted'];

        notify($payment->toUser, 'PAYMENT_PROVE', [
            'username'              => $payment->fromUser->username,
            'mobile'                => $payment->fromUser->mobile,
            'amount'                => getAmount($payment->amount) . ' ' .  $payment->investment->currency->code,
            'confirmation_deadline' => date('d-m-Y h:i:s A', strtotime($payment->confirmation_deadline)),
            'payment_info'          => $payment->info,
        ]);

        return back()->withNotify($notify);
    }

    public function paymentConfirm($id) {
        $user = auth()->user();

        try {
            $decryptData = decrypt($id);
        } catch (\Throwable $th) {
            abort(404);
        }

        $identifyData = explode('|', $decryptData);
        if ($identifyData[1] != $user->id) {
            $notify[] = ['error', 'Your are not authorized for this action!'];
            return back()->withNotify($notify);
        }
        $payment = Payment::where('id', $identifyData[0])->firstOrFail();
        if ($payment->status != Status::PAYMENT_WAITING) {
            $notify[] = ['error', 'Your are not authorized for this action!'];
            return back()->withNotify($notify);
        }
        $payment->status = 1;
        $payment->info   = "Receiver approved the payment";
        $payment->save();

        $transaction              = new Transaction();
        $transaction->user_id     = $payment->to_user_id;
        $transaction->amount      = $payment->amount;
        $transaction->currency_id = $payment->investment->currency->id;
        $transaction->trx_type    = '+';
        $transaction->details     = 'From ' . $payment->fromUser->fullname;
        $transaction->trx         = getTrx();
        $transaction->save();

        $trx              = new Transaction();
        $trx->user_id     = $payment->from_user_id;
        $trx->amount      = $payment->amount;
        $trx->currency_id = $payment->investment->currency->id;
        $trx->trx_type    = '-';
        $trx->details     = 'To ' . $payment->toUser->fullname;
        $trx->trx         = $transaction->trx;
        $trx->save();

        $withdraw = $payment->withdraw;
        if ($withdraw) {
            $withdraw->success_amount += $payment->amount;
            if ($withdraw->success_amount >= $withdraw->amount) {
                $withdraw->status = Status::WITHDRAW_COMPLETED;
            }
            $withdraw->save();
        }

        $invest = $payment->investment;
        if ($invest) {
            $invest->success_amount += $payment->amount;
            if ($invest->success_amount >= $invest->amount) {
                $invest->status  = Status::INVESTMENT_COMPLETE;
                $invest->paid_at = now();
                if ($invest->is_activation == Status::INVESTMENT_ACTIVATION) {
                    $activeUser = User::find($payment->from_user_id);
                    if ($activeUser) {
                        $activeUser->activation = Status::USER_ACTIVATION;
                        $activeUser->save();
                    }
                    $invest->withdrawable_time = now();
                    $invest->withdrawn_at      = now();
                } else {
                    $invest->withdrawable_time = now()->addDays($invest->plan->duration);
                }
                $general = gs();
                if ($general->referral) {
                    levelCommission($invest);
                }
            }
            $invest->save();
        }

        //recommitment
        $recommitLogs = RecommitLog::where('by_invest_id', $payment->investment_id)->where('remain_amount', '>', 0)->orderBy('id')->get();
        if ($recommitLogs->isNotEmpty()) {
            $grossAmount = $payment->amount;
            foreach ($recommitLogs as $recommitLog) {
                $weak                         = ($recommitLog->remain_amount < $grossAmount) ? $recommitLog->remain_amount : $grossAmount;
                $grossAmount                 -= $weak;
                $recommitLog->remain_amount  -= $weak;
                $recommitLog->success_amount += $weak;
                if ($recommitLog->success_amount >= $recommitLog->amount) {
                    $recommitLog->status = Status::RECOMMIT_COMPLETE;
                }
                $recommitLog->save();
                $recommittedFor                    = Investment::find($recommitLog->for_invest_id);
                $recommittedFor->recommit_success += $weak;
                if ($recommittedFor->recommit_success >= $recommittedFor->recommit_amount) {
                    $recommittedFor->recommit_status = Status::RECOMMIT_COMPLETE;
                }
                $recommittedFor->save();
                if ($grossAmount <= 0) {
                    break;
                }
            }
        }
        notify($payment->fromUser, 'PAYMENT_CONFIRMED', [
            'fullname' => $payment->toUser->fullname,
            'mobile'   => $payment->toUser->mobile,
            'amount'   => getAmount($payment->amount) . ' ' . $payment->investment->currency->code
        ]);
        $notify[] = ['success', 'Approved this payment'];
        return back()->withNotify($notify);
    }

    public function paymentNotPaid($id) {
        $user = auth()->user();
        try {
            $decryptData = decrypt($id);
        } catch (\Throwable $th) {
            abort(404);
        }

        $identifyData = explode('|', $decryptData);
        if ($identifyData[1] != $user->id) {
            $notify[] = ['error', 'Your are not authorized for this action!'];
            return back()->withNotify($notify);
        }
        $payment = Payment::where('id', $identifyData[0])->firstOrFail();
        if ($payment->status != 0) {
            $notify[] = ['error', 'Your are not authorized for this action!'];
            return back()->withNotify($notify);
        }

        if ($payment->deadline > now()) {
            $notify[] = ['error', 'Your are not authorized for this action!'];
            return back()->withNotify($notify);
        }

        $payment->status = Status::PAYMENT_REPORT_RECEIVER;
        $payment->info   = 'Payer has not paid!';
        $payment->save();

        $invest                 = $payment->investment;
        $invest->remain_amount += $payment->amount;
        $invest->save();

        $withdraw                 = $payment->withdraw;
        $withdraw->remain_amount += $payment->amount;
        $withdraw->save();

        $notify[] = ['success', 'Payment rejected successfully!'];
        return back()->withNotify($notify);
    }

    public function informationDownload($id) {
        $user = auth()->user();

        try {
            $decryptData = decrypt($id);
        } catch (\Throwable $th) {
            abort(404);
        }

        $identifyData = explode('|', $decryptData);
        if ($identifyData[1] != $user->id) {
            $notify[] = ['error', 'Your are not authorized for this action!'];
            return back()->withNotify($notify);
        }
        $payment   = Payment::where('id', $identifyData[0])->firstOrFail();
        $file      = $payment->image;
        $path      = getFilePath('payment');
        $full_path = $path . '/' . $file;
        $title     = rand();
        $ext       = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype  = mime_content_type($full_path);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

    public function paymentReport(Request $request, $id) {
        $payment = Payment::findOrFail($id);
        $user = auth()->user();
        if ($payment->from_user_id == $user->id && $payment->status == Status::PAYMENT_WAITING && $payment->confirmation_deadline < now()) {
            $payment->status = Status::PAYMENT_REPORT_SENDER;
            $payment->save();
        } elseif ($payment->to_user_id == $user->id && ($payment->status == Status::PAYMENT_WAITING || ($payment->status == Status::PAYMENT_CREATED && $payment->deadline < now()))) {
            $payment->status = Status::PAYMENT_REPORT_RECEIVER;
            $payment->save();
        } else {
            abort(403, 'Unauthorize Action');
        }

        $chat             = new Chat();
        $chat->payment_id = $payment->id;
        $chat->user_id    = 0;
        $chat->message    = 'This payment is reported by ' . $user->username . '. We will check this request soon. Please be patient.';
        $chat->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = 0;
        $adminNotification->title     = 'A payment is reported by  ' . $user->username;
        $adminNotification->click_url = urlPath('admin.payment.detail', $payment->id);
        $adminNotification->save();

        $notify[] = ['success', 'Your report has been submitted successfully!'];
        return back()->withNotify($notify);
    }


}
