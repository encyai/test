<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Currency;
use App\Models\WithdrawalInfo;
use Illuminate\Http\Request;

class WithdrawalInfoController extends Controller {

    public function index() {
        $pageTitle = "Withdraw Information";
        $currencies = Currency::where('status', Status::ENABLE)->select('id', 'name', 'symbol', 'code', 'form_id')->with(['withdrawInfo' => function ($q) {
            return $q->where('user_id', auth()->id());
        }, 'form'])->get();
        return view($this->activeTemplate . 'user.currency.information', compact('pageTitle', 'currencies'));
    }

    public function store(Request $request, $id) {

        $user = auth()->user();
        $currency = Currency::where('id', $id)->where('status', Status::ENABLE)->firstOrFail();

        $formData = $currency->form->form_data;

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);

        $userData = $formProcessor->processFormData($request, $formData);

        if ($currency->withdrawInfo) {
            $currencyInfo = WithdrawalInfo::find($currency->withdrawInfo->id);
            $notifyMessage = ucfirst($currency->name) . ' currency account information updated';
        } else {
            $currencyInfo = new WithdrawalInfo();
            $notifyMessage = ucfirst($currency->name) . ' currency account information added';
        }

        $currencyInfo->user_id = $user->id;
        $currencyInfo->currency_id = $currency->id;
        $currencyInfo->info = $userData;
        $currencyInfo->save();

        $notify[] = ['success', $notifyMessage];
        return back()->withNotify($notify);
    }
}
