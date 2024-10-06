<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Investment;

class InvestmentController extends Controller
{
    public function index()
    {
       $pageTitle   = "All Investments";
       $investments = Investment::with(['user', 'plan', 'currency'])->orderBy('id', 'desc')->paginate(getPaginate());
       return view('admin.investments.list', compact('pageTitle', 'investments')); 
    }

    public function pending()
    {
        $pageTitle   = 'Pending Investments';
        $investments = Investment::pending()
            ->whereHas('user', function ($user) {
                $user->where('status', Status::USER_ACTIVE);
            })->with(['user', 'plan', 'currency'])
            ->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.investments.list', compact('pageTitle', 'investments'));
    }

    public function upcoming()
    {
        $pageTitle    = 'Upcoming Investments';
        $investments  = Investment::pending()
            ->where('remain_amount', '>', 0)
            ->whereHas('user', function ($user) {
                $user->where('status', Status::USER_ACTIVE);
            })->with(['user', 'plan', 'currency'])
            ->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.investments.list', compact('pageTitle', 'investments'));
    }

    public function completed()
    {
        $pageTitle    = 'Completed Investments';
        $investments  = Investment::where('status', 1)->with(['user', 'plan', 'currency'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.investments.list', compact('pageTitle', 'investments'));
    }

    public function detail($id = null)
    {
        $pageTitle    = "Investments Details";
        $investment   = Investment::findOrFail($id);
        return view('admin.investments.details', compact('pageTitle', 'investment'));
    }
}
