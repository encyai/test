<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $pageTitle  = "All Plans";
        $plans      = Plan::orderBy('id', 'desc')->searchable(['name'])->with('currency')->paginate(getPaginate());
        $currencies = Currency::get();
        return view('admin.plans.index', compact('pageTitle', 'plans', 'currencies'));
    }

    public function save(Request $request, $id = 0)
    {
        $this->validation($request, $id);
        if ($id == 0) {
            $plan          = new Plan();
            $notifyMessage = 'Plan added successfully';
        } else {
            $plan          = Plan::findOrFail($id);
            $notifyMessage = 'Plan updated successfully';
        }
        
        $plan->currency_id          = $request->currency_id;
        $plan->name                 = $request->name;
        $plan->min_investment_limit = $request->min_investment_limit;
        $plan->max_investment_limit = $request->max_investment_limit;
        $plan->profit               = $request->profit;
        $plan->duration             = $request->duration;
        $plan->save();

        $notify[] = ['success', $notifyMessage];
        return back()->withNotify($notify);
    }

    protected function validation($request, $id)
    {
        $validationRules = [
            'name'                 => 'required|max:255|unique:plans,name,' . $id,
            'currency_id'          => 'required|exists:currencies,id',
            'min_investment_limit' => 'required|numeric|gt:0',
            'max_investment_limit' => 'required|numeric|gt:0',
            'profit'               => 'required|numeric|gt:0',
            'duration'             => 'required|numeric|gt:0',
        ];
        $request->validate($validationRules);
    }

    public function status($id)
    {
        return Plan::changeStatus($id);
    }
}
