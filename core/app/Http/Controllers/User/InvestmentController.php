<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Investment;
use App\Models\Plan;
use App\Models\RecommitLog;
use Illuminate\Http\Request;

class InvestmentController extends Controller {

    public function investNow() {
        $plans     = Plan::active()->with('currency')->paginate(getPaginate(9));
        $pageTitle = 'Investment Plans';
        $layout    = 'master';
        return view($this->activeTemplate . 'plans', compact('pageTitle', 'plans', 'layout'));
    }

    public function store(Request $request) {
        $request->validate([
            'amount' => 'required|numeric|gt:0'
        ]);
        $user                 = auth()->user();
        $incompleteInvestment = Investment::where('user_id', $user->id)->where('status', 0)->exists();

        if ($incompleteInvestment) {
            $notify[] = ['error', 'You already have an incomplete investment'];
            return back()->withNotify($notify);
        }

        $planId = $request->plan_id;
        $plan   = Plan::active()->findOrFail($planId);

        if ($request->amount < $plan->min_investment_limit || $request->amount > $plan->max_investment_limit) {
            $notify[] = ['error', 'Please follow the investment limit'];
            return back()->withNotify($notify);
        }

        $amount  = $request->amount;
        $general = gs();

        $recommitAmount = $amount * $general->recommit_amount / 100;

        $investment                      = new Investment();
        $investment->plan_id             = $plan->id;
        $investment->currency_id         = $plan->currency->id;
        $investment->user_id             = $user->id;
        $investment->amount              = getAmount($amount, 8);
        $investment->remain_amount       = getAmount($amount, 8);
        $investment->recommit_amount     = getAmount($recommitAmount, 8);
        $investment->recommit_remain     = getAmount($recommitAmount, 8);
        $investment->profit              = $amount * $plan->profit / 100;
        $investment->withdrawable_amount = $amount + $investment->profit;
        $investment->save();

        //// check For Recommitment
        $oldInvestments = Investment::where('user_id', $user->id)
            ->where('currency_id', $plan->currency->id)
            ->where('recommit_remain', '>', 0)
            ->where('id', '!=', $investment->id)
            ->oldest()
            ->get();

        $grossAmount = $amount;  // Invested amount

        foreach ($oldInvestments as $oldInvestment) {
            $weak                            = $oldInvestment->recommit_remain < $grossAmount ? $oldInvestment->recommit_remain : $grossAmount;
            $grossAmount                    -= $weak;
            $oldInvestment->recommit_remain -= $weak;
            $oldInvestment->save();

            $recommitLog                = new RecommitLog();
            $recommitLog->user_id       = $user->id;
            $recommitLog->for_invest_id = $oldInvestment->id;
            $recommitLog->by_invest_id  = $investment->id;
            $recommitLog->amount        = $weak;
            $recommitLog->remain_amount = $weak;
            $recommitLog->save();

            if ($grossAmount <= 0) break;
        }

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = $user->username . ' just wanted to invest ' . getAmount($amount) . ' ' . $investment->currency->code;
        $adminNotification->click_url = urlPath('admin.investment.detail', @$investment->id);
        $adminNotification->save();

        $notify[] = ['success', 'Investment requested successfully!'];
        return to_route('user.invest.history')->withNotify($notify);
    }

    public function history() {
        $pageTitle = 'My Investments';
        $invests   = auth()->user()->investments()->with('currency', 'payments')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.investment.history', compact('pageTitle', 'invests'));
    }

    public function detail($id) {
        $pageTitle  = "Investment details";
        $investment = Investment::with('payment.toUser', 'currency', 'payment.currency', 'payment.investment')->findOrFail($id);
        if ($investment->user_id != auth()->id()) {
            abort(404);
        }
        return view($this->activeTemplate . 'user.investment.details', compact('pageTitle', 'investment'));
    }
}
