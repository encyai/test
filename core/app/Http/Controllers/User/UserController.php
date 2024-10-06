<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\AdminNotification;
use App\Models\Chat;
use App\Models\Currency;
use App\Models\Form;
use App\Models\Investment;
use App\Models\Payment;
use App\Models\ReferralCommissionWallet;
use App\Models\ReferralWithdrawLog;
use App\Models\RefLog;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    public function home() {
        $pageTitle = 'Dashboard';

        $user                   = auth()->user();
        $widget['investments']  = Investment::where('user_id', $user->id)->count();
        $widget['withdrawals']  = Withdrawal::where('user_id', $user->id)->count();
        $widget['referred']     = User::where('ref_by', $user->id)->count();
        $widget['transactions'] = Transaction::orderBy('id', 'desc')->where('user_id', $user->id)->count();
        $transactions           = Transaction::orderBy('id', 'desc')->where('user_id', $user->id)->with('currency')->take(10)->get();
        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'widget', 'transactions'));
    }

    public function show2faForm() {
        $general   = gs();
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view($this->activeTemplate . 'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request) {
        $user = auth()->user();
        $this->validate($request, [
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts  = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request) {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user     = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts  = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions(Request $request) {
        $request->validate([
            'currency' => 'nullable|exists:currencies,id'
        ]);
        $pageTitle  = 'Transaction History';
        $currencies = Currency::where('status', Status::ENABLE)->select('id', 'code')->get();
        if ($request->currency)
            $transactions = auth()->user()->transactions()->where('currency_id', $request->currency)->orderBy('id', 'desc')->with('currency', 'user')->paginate(getPaginate());
        else {
            $transactions = auth()->user()->transactions()->orderBy('id', 'desc')->with('currency', 'user')->paginate(getPaginate());
        }
        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'transactions', 'currencies'));
    }

    public function referral() {
        $pageTitle     = "My Referred Users";
        $referralUsers = User::where('ref_by', auth()->id())->where('status', Status::USER_ACTIVE)->orderBy('id', 'DESC')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.referral_user', compact('pageTitle', 'referralUsers'));
    }

    public function referralUserInvest($id) {
        $pageTitle        = 'Investment History';
        $user             = auth()->user();
        $referralUser     = User::where('id', $id)->where('ref_by', $user->id)->firstOrFail();
        $referInvestments = Investment::where('status', Status::INVESTMENT_COMPLETE)
            ->where('user_id', $referralUser->id)
            ->groupBy('currency_id')
            ->selectRaw('sum(amount) as total, user_id, currency_id, status, created_at')
            ->with('user', 'currency')
            ->paginate(getPaginate());
        return view($this->activeTemplate . 'user.referral_user_invest', compact('pageTitle', 'referInvestments'));
    }

    public function referralCommission() {
        $pageTitle      = "Referral Commission";
        $user           = auth()->user();
        $commissionLogs = RefLog::where('user_id', $user->id)->orderBy('id', 'desc')->with('currency', 'byWho', 'user')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.referral_commission', compact('pageTitle', 'commissionLogs', 'user'));
    }

    public function referralWithdraw() {
        $pageTitle       = "Referral Bonus Withdraw log";
        $refWithdrawLogs = ReferralWithdrawLog::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.referral_withdraw_logs', compact('pageTitle', 'refWithdrawLogs'));
    }

    public function withdrawCommission(Request $request) {
        $request->validate([
            'id'     => 'required|exists:referral_commission_wallets,id',
            'amount' => 'required|numeric|gt:0',
        ]);
        $user   = auth()->user();
        $wallet = ReferralCommissionWallet::where('id', $request->id)->where('user_id', $user->id)->firstOrFail();
        if ($request->amount > $wallet->amount) {
            $notify[] = ['error', 'Insufficient Balance!'];
            return back()->withNotify($notify);
        }
        $wallet->amount -= $request->amount;
        $wallet->save();

        $withdrawal                = new Withdrawal();
        $withdrawal->user_id       = $user->id;
        $withdrawal->currency_id   = $wallet->currency_id;
        $withdrawal->amount        = $request->amount;
        $withdrawal->remain_amount = $request->amount;
        $withdrawal->date_priority = now();
        $withdrawal->save();

        $log              = new ReferralWithdrawLog();
        $log->user_id     = $user->id;
        $log->currency_id = $wallet->currency_id;
        $log->amount      = getAmount($request->amount);
        $log->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'amount'   => getAmount($request->amount),
            'currency' => $wallet->currency->code,
        ]);
        $notify[] = ['success', 'Your Withdraw Requested Sent Successfully'];
        return back()->withNotify($notify);
    }

    public function kycForm() {
        if (auth()->user()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Verification';
        $form      = Form::where('act', 'kyc')->first();
        return view($this->activeTemplate . 'user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData() {
        $user      = auth()->user();
        $pageTitle = 'KYC Verification';
        return view($this->activeTemplate . 'user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request) {
        $form           = Form::where('act', 'kyc')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData       = $formProcessor->processFormData($request, $formData);
        $user           = auth()->user();
        $user->kyc_data = $userData;
        $user->kv       = 2;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function attachmentDownload($fileHash) {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general   = gs();
        $title     = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype  = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function userData() {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }
        $pageTitle = 'Complete Your Profile';
        return view($this->activeTemplate . 'user.user_data', compact('pageTitle', 'user'));
    }

    public function userDataSubmit(Request $request) {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }

        $request->validate([
            'firstname' => 'required',
            'lastname'  => 'required',
        ]);

        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->address   = [
            'country' => @$user->address->country,
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'city'    => $request->city,
        ];

        $user->profile_complete = 1;
        $user->save();

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function activateAccount() {
        $currencies = Currency::active()->get();
        $pageTitle  = 'Activate Account';
        return view($this->activeTemplate . 'user.activate_account', compact('pageTitle', 'currencies'));
    }

    public function sendActivationRequest(Request $request) {
        $request->validate([
            'currency_id' => 'required',
        ]);

        $user = auth()->user();

        if ($user->activation != 0) {
            $notify[] = ['error', 'Please wait till merging your activating investment'];
            return redirect()->route('user.home')->withNotify($notify);
        }

        $currency = Currency::active()->findOrFail($request->currency_id);

        $investment                = new Investment();
        $investment->currency_id   = $currency->id;
        $investment->user_id       = $user->id;
        $investment->amount        = $currency->activation_fees;
        $investment->remain_amount = $currency->activation_fees;
        $investment->is_activation = Status::INVESTMENT_ACTIVATION;
        $investment->save();

        $user->activation = Status::PENDING;
        $user->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New activation request for the amount of ' . getAmount($investment->amount) . ' ' . $investment->currency->code;
        $adminNotification->click_url = urlPath('admin.investment.detail', @$investment->id);;
        $adminNotification->save();

        notify($user, 'ACTIVATION_REQUEST');
        $notify[] = ['success', 'Activating request sent successfully'];

        return redirect()->route('user.home')->withNotify($notify);
    }

    public function sendMessage(Request $request, $id) {


        $validator = Validator::make($request->all(), [
            'message'    => 'required|max:250',
            'file' => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }


        $payment = Payment::where('id', $id)->first();
        if (!$payment) {
            return response()->json(['error' => 'Invalid this payment']);
        }


        if ($payment->status != Status::PAYMENT_COMPLETED && $payment->status != Status::PAYMENT_REPORT_RECEIVER && $payment->status != Status::PAYMENT_CANCELLED) {
            $user             = auth()->user();
            $chat             = new Chat();
            $chat->payment_id = $payment->id;
            $chat->user_id    = auth()->id();
            $chat->message    = $request->message;

            if ($request->hasFile('file')) {
                try {
                    $chat->file = fileUploader($request->file('file'), getFilePath('conversation'));
                } catch (\Exception $exp) {
                    return response()->json(['error' => 'Couldn\'t upload your image']);
                }
            }

            $chat->save();
            return view($this->activeTemplate . 'user.chat.last_message', compact('chat'));
        } else {
            return response()->json(['error' => 'You are not eligible to send a message']);
        }
    }

    public function chatMessage(Request $request) {
        $totalMessage = Chat::where('payment_id', $request->payment_id)->count();
        $messages     = Chat::where('payment_id', $request->payment_id)->take($request->messageCount)->with('user')->latest()->get();
        $messageCount = 1;
        if ($messages->count() == $totalMessage) {
            $messageCount = 0;
        }
        return view($this->activeTemplate . 'user.chat.message', compact('messages', 'messageCount'));
    }
}
