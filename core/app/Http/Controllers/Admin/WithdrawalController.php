<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Withdrawal;

class WithdrawalController extends Controller
{

    public function index()
    {
        $pageTitle = "Withdrawal Log";
        $withdrawals = Withdrawal::orderBy('id', 'desc')->with('user', 'currency')->paginate(getPaginate());
        return view('admin.withdrawals.list', compact('pageTitle', 'withdrawals'));
    }

    public function pending()
    {
        $pageTitle = "Pending Withdrawal Log";
        $withdrawals = Withdrawal::orderBy('id', 'desc')->pending()->with('user', 'currency')->paginate(getPaginate());
        return view('admin.withdrawals.list', compact('pageTitle', 'withdrawals'));
    }

    public function completed()
    {
        $pageTitle = "Completed Withdrawal Log";
        $withdrawals = Withdrawal::orderBy('id', 'desc')->completed()->with('user', 'currency')->paginate(getPaginate());
        return view('admin.withdrawals.list', compact('pageTitle', 'withdrawals'));
    }

    public function referral()
    {
        $pageTitle    = "Referral Withdraw Log";
        $withdrawals  = Withdrawal::orderBy('id', 'desc')->where('type', 2)->with('user', 'currency')->paginate(getPaginate());
        return view('admin.withdrawals.list', compact('pageTitle', 'withdrawals'));
    }

    public function upcoming()
    {
        $pageTitle = "Upcoming Withdrawals";
        $upcomingWithdrawals = Investment::where('is_activation', 0)
            ->where('recommit_status', Status::RECOMMIT_COMPLETE)
            ->where('withdrawn_at', null)
            ->orderBy('withdrawable_time')
            ->with('user', 'currency')
            ->paginate(getPaginate());
        return view('admin.withdrawals.upcoming', compact('pageTitle', 'upcomingWithdrawals'));
    }

}
