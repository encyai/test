<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller {
    public function index() {
        $pageTitle  = 'All Currencies';
        $currencies = Currency::orderBy('id', 'desc')->searchable(['name', 'code'])->paginate(getPaginate());
        return view('admin.currency.index', compact('pageTitle', 'currencies'));
    }

    public function create() {
        $pageTitle = "Add New Currency";
        return view('admin.currency.create', compact('pageTitle'));
    }

    public function edit($id) {
        $pageTitle = 'Edit Currency';
        $currency  = Currency::with('form')->findOrFail($id);
        $form      = $currency->form;
        return view('admin.currency.create', compact('pageTitle', 'currency', 'form'));
    }

    public function save(Request $request, $id = 0) {
        $validation = [
            'name'            => 'required|max:40',
            'code'            => 'required|max:40',
            'symbol'          => 'required|max:40',
            'activation_fees' => 'required|numeric|gt:0'
        ];

        $formProcessor       = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $validation          = array_merge($validation, $generatorValidation['rules']);
        $request->validate($validation, $generatorValidation['messages']);

        if ($id == 0) {
            $generate = $formProcessor->generate('currency');
            $currency = new Currency();
            $notifyMessage = 'Currency added successfully';
        } else {
            $currency = Currency::findOrFail($id);
            $generate = $formProcessor->generate('currency', true, 'id', $currency->form_id);
            $notifyMessage = 'Currency updated successfully';
        }

        $currency->name            = $request->name;
        $currency->code            = $request->code;
        $currency->symbol          = $request->symbol;
        $currency->activation_fees = $request->activation_fees;
        $currency->form_id         = @$generate->id ?? 0;
        $currency->save();
        $notify[] = ['success', $notifyMessage];

        return back()->withNotify($notify);
    }

    public function status($id) {
        return Currency::changeStatus($id);
    }
}
