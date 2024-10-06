<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Withdrawal;
use App\Models\WithdrawalInfo;
use Exception;

class WithdrawController extends Controller {
    public function index() {
        $pageTitle   = 'Withdraw Money';
        $investments = Investment::orderBy('id', 'desc')
            ->completed()
            ->where('user_id', auth()->id())
            ->withdrawable()
            ->with('currency')
            ->paginate(getPaginate());
        return view($this->activeTemplate . 'user.withdrawal.index', compact('pageTitle', 'investments'));
    }

    public function withdrawStore($encodedData) {
        try {
            $decodedData = decrypt($encodedData);
        } catch (Exception $e) {
            abort(404);
        }
        $user = auth()->user();
        $data = explode('|', $decodedData);
        if ($data[1] != $user->id) {
            $notify[] = ['error', 'Your are not authorized for this action!'];
            return back()->withNotify($notify);
        }
        $investment = Investment::where('id', $data[0])->where('status', Status::INVESTMENT_COMPLETE)
            ->where('user_id', $user->id)
            ->withdrawable()
            ->firstOrFail();
        $withdrawalInfo = WithdrawalInfo::where('user_id', $user->id)->where('currency_id', $investment->currency_id)->first();

        if (!$withdrawalInfo) {
            $notify[] = ['error', 'First, ' . lcfirst($investment->currency->name) . ' currency withdraw information add'];
            return to_route('user.withdraw.information')->withNotify($notify);
        }

        $withdraw                = new Withdrawal();
        $withdraw->plan_id       = $investment->plan_id;
        $withdraw->investment_id = $investment->id;
        $withdraw->user_id       = $user->id;
        $withdraw->amount        = $investment->withdrawable_amount;
        $withdraw->remain_amount = $investment->withdrawable_amount;
        $withdraw->status        = Status::WITHDRAW_DEFAULT;
        $withdraw->currency_id   = $investment->currency_id;
        $withdraw->date_priority = now();
        $withdraw->save();
        $investment->withdrawn_at = now();
        $investment->save();
        $notify[] = ['success', 'Withdraw Requested Successfully!'];
        return back()->withNotify($notify);
    }

    public function withdrawHistory() {
        $pageTitle   = 'My Withdrawals';
        $withdrawals = Withdrawal::orderBy('id', 'desc')->where('user_id', auth()->id())->with('currency')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.withdrawal.history', compact('pageTitle', 'withdrawals'));
    }

    public function withdrawDetails($id = null) {
        $withdraw  = Withdrawal::where('user_id', auth()->id())->with(['payments', 'payments.fromUser', 'currency'])->findOrFail($id);
        $pageTitle = 'Withdrawal Details';
        return view($this->activeTemplate . 'user.withdrawal.details', compact('pageTitle', 'withdraw'));
    }
}
