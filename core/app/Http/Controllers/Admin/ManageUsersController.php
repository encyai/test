<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\NotificationLog;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ManageUsersController extends Controller
{

    public function allUsers()
    {
        $pageTitle = 'All Users';
        $users     = $this->userData();
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function activeUsers()
    {
        $pageTitle = 'Active Users';
        $users     = $this->userData('active');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function bannedUsers()
    {
        $pageTitle = 'Banned Users';
        $users     = $this->userData('banned');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function emailUnverifiedUsers()
    {
        $pageTitle = 'Email Unverified Users';
        $users     = $this->userData('emailUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function kycUnverifiedUsers()
    {
        $pageTitle = 'KYC Unverified Users';
        $users     = $this->userData('kycUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function kycPendingUsers()
    {
        $pageTitle = 'KYC Unverified Users';
        $users     = $this->userData('kycPending');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function emailVerifiedUsers()
    {
        $pageTitle = 'Email Verified Users';
        $users     = $this->userData('emailVerified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function mobileUnverifiedUsers()
    {
        $pageTitle = 'Mobile Unverified Users';
        $users     = $this->userData('mobileUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function mobileVerifiedUsers()
    {
        $pageTitle = 'Mobile Verified Users';
        $users     = $this->userData('mobileVerified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    protected function userData($scope = null)
    {
        if ($scope) {
            $users = User::$scope();
        } else {
            $users = User::query();
        }
        return $users->searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function detail($id)
    {
        $user      = User::findOrFail($id);
        $pageTitle = 'User Detail - ' . $user->username;
        $total['investment']  = Investment::where('user_id', $user->id)->count();
        $total['withdraw']    = Withdrawal::where('user_id', $user->id)->count();
        $total['transaction'] = Transaction::where('user_id', $user->id)->count();
        $total['referral']    = User::where('ref_by', $user->id)->count();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.detail', compact('pageTitle', 'user', 'total', 'countries'));
    }

    public function kycDetails($id)
    {
        $pageTitle = 'KYC Details';
        $user      = User::findOrFail($id);
        return view('admin.users.kyc_detail', compact('pageTitle', 'user'));
    }

    public function kycApprove($id)
    {
        $user     = User::findOrFail($id);
        $user->kv = 1;
        $user->save();
        notify($user, 'KYC_APPROVE', []);
        $notify[] = ['success', 'KYC approved successfully'];
        return to_route('admin.users.kyc.pending')->withNotify($notify);
    }

    public function kycReject($id)
    {
        $user = User::findOrFail($id);
        foreach ($user->kyc_data as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $user->kv       = 0;
        $user->kyc_data = null;
        $user->save();

        notify($user, 'KYC_REJECT', []);

        $notify[] = ['success', 'KYC rejected successfully'];
        return to_route('admin.users.kyc.pending')->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $user         = User::findOrFail($id);
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray = (array)$countryData;
        $countries    = implode(',', array_keys($countryArray));

        $countryCode = $request->country;
        $country     = $countryData->$countryCode->country;
        $dialCode    = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname'  => 'required|string|max:40',
            'email'     => 'required|email|string|max:40|unique:users,email,' . $user->id,
            'mobile'    => 'required|string|max:40|unique:users,mobile,' . $user->id,
            'country'   => 'required|in:' . $countries,
        ]);
        $user->mobile       = $dialCode . $request->mobile;
        $user->country_code = $countryCode;
        $user->firstname    = $request->firstname;
        $user->lastname     = $request->lastname;
        $user->email        = $request->email;
        $user->address      = [
            'address' => $request->address,
            'city'    => $request->city,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'country' => @$country,
        ];
        $user->ev = $request->ev ? Status::VERIFIED : Status::UNVERIFIED;
        $user->sv = $request->sv ? Status::VERIFIED : Status::UNVERIFIED;
        $user->ts = $request->ts ? Status::ENABLE : Status::DISABLE;
        if (!$request->kv) {
            $user->kv = 0;
            if ($user->kyc_data) {
                foreach ($user->kyc_data as $kycData) {
                    if ($kycData->type == 'file') {
                        fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
                    }
                }
            }
            $user->kyc_data = null;
        } else {
            $user->kv = 1;
        }
        $user->save();

        $notify[] = ['success', 'User details updated successfully'];
        return back()->withNotify($notify);
    }

    public function login($id)
    {
        Auth::loginUsingId($id);
        return to_route('user.home');
    }

    public function status(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->status == Status::USER_ACTIVE) {
            $request->validate([
                'reason' => 'required|string|max:255'
            ]);
            $user->status     = Status::USER_BAN;
            $user->ban_reason = $request->reason;
            $notify[]         = ['success', 'User banned successfully'];
        } else {
            $user->status     = Status::USER_ACTIVE;
            $user->ban_reason = null;
            $notify[]         = ['success', 'User unbanned successfully'];
        }
        $user->save();
        return back()->withNotify($notify);
    }


    public function showNotificationSingleForm($id)
    {
        $user    = User::findOrFail($id);
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.users.detail', $user->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $user->username;
        return view('admin.users.notification_single', compact('pageTitle', 'user'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        notify($user, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm()
    {
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }
        $users     = User::active()->count();
        $pageTitle = 'Notification to Verified Users';
        return view('admin.users.notification_all', compact('pageTitle', 'users'));
    }

    public function sendNotificationAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $user = User::active()->skip($request->skip)->first();
        if (!$user) {
            return response()->json([
                'error'      => 'User not found',
                'total_sent' => 0,
            ]);
        }

        notify($user, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json([
            'success'    => 'message sent',
            'total_sent' => $request->skip + 1,
        ]);
    }

    public function notificationLog($id)
    {
        $user      = User::findOrFail($id);
        $pageTitle = 'Notifications Sent to ' . $user->username;
        $logs      = NotificationLog::where('user_id', $id)->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs', 'user'));
    }

    public function makeEligible()
    {
        $pageTitle = 'Make Eligible ';
        $plans     = Plan::active()->with('currency')->get();
        return view('admin.users.eligible', compact('pageTitle', 'plans'));
    }

    public function makeEligibleStore(Request $request)
    {
        $request->validate([
            'username'  => 'required',
            'amount'    => 'numeric|gt:0',
            'back_days' => 'required|integer|gte:0',
            'plan_id'   => 'required|integer'
        ]);

        $user = User::where('username', $request->username)->first();
        if (!$user) {
            $notify[] = ['error', 'User not found'];
            return back()->withNotify($notify);
        }

        $plan = Plan::findOrFail($request->plan_id);
        if (!$plan) {
            $notify[] = ['error', 'Plan not found'];
            return back()->withNotify($notify);
        }

        $withdrawal                = new Withdrawal();
        $withdrawal->user_id       = $user->id;
        $withdrawal->plan_id       = $plan->id;
        $withdrawal->currency_id   = $plan->currency->id;
        $withdrawal->investment_id = 0;
        $withdrawal->date_priority = now()->subDays($request->back_days);
        $withdrawal->amount        = $request->amount;
        $withdrawal->type          = Status::WITHDRAW_ELIGIBLE;
        $withdrawal->remain_amount = $request->amount;
        $withdrawal->save();
        $notify[] = ['success', 'Made eligible successfully'];
        return back()->withNotify($notify);
    }

    public function investment($id)
    {
        $user        = User::findOrFail($id);
        $pageTitle   = "User Investment : " . $user->username;
        $investments = Investment::where('user_id', $user->id)->orderBy('id', 'DESC')->with('currency', 'plan', 'user')->paginate(getPaginate());
        return view('admin.investments.list', compact('pageTitle', 'investments'));
    }

    public function withdraw($id)
    {
        $user        = User::findOrFail($id);
        $pageTitle   = "User Withdrawal : " . $user->username;
        $withdrawals = Withdrawal::where('user_id', $user->id)->orderBy('id', 'DESC')->with('user', 'currency')->paginate(getPaginate());
        return view('admin.withdrawals.list', compact('pageTitle', 'withdrawals'));
    }

    public function referral($id)
    {
        $user      = User::findOrFail($id);
        $pageTitle = "Referral User List : " . $user->username;
        $users     = User::where('ref_by', $user->id)->latest()->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function transactions(Request $request, $id)
    {
        $user         = User::findOrFail($id);
        $transactions = $user->transactions()->searchable(['trx'])->with('user', 'currency')->orderBy('id', 'desc')->paginate(getPaginate());
        $pageTitle    = 'User Transactions : ' . $user->username;
        return view('admin.reports.transactions', compact('pageTitle', 'user', 'transactions'));
    }

    public function checkUser(Request $request)
    {
        $exist['data'] = false;
        if ($request->username) {
            $exist['data'] = User::where('username', $request->username)->exists();
        }
        return response()->json(
            $exist
        );
    }
}
