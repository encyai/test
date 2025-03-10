<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\RefLog;
use App\Models\Transaction;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function transaction()
    {
        $pageTitle    = 'Transactions History';
        $transactions = Transaction::orderBy('id', 'desc')->searchable(['trx'])->with('user', 'currency')->paginate(getPaginate());
        return view('admin.reports.transactions', compact('pageTitle', 'transactions'));
    }

    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Login History';
        $loginLogs = UserLogin::orderBy('id', 'desc')->searchable(['user:username'])->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip', $ip)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $logs      = NotificationLog::orderBy('id', 'desc')->searchable(['user:username'])->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email     = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }

    public function referralCommission()
    {
        $pageTitle    = "Referral Commission Log";
        $referralLogs = RefLog::orderBy('id', 'DESC')->with('currency', 'byWho', 'user')->paginate(getPaginate());
        return view('admin.reports.referral', compact('pageTitle', 'referralLogs'));
    }
}
