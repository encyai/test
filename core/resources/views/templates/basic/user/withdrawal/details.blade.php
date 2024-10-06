@extends($activeTemplate.'layouts.master')
@section('content')
<div class="row justify-content-center gy-4">
    <div class="col-lg-4 col-md-12">
        <div class="card custom--card">
            <div class="card-header">
                <h4 class="mb-0 text-white">@lang('Withdraw')</h4>
            </div>
            <div class="card-body p-0">
                <ul class="withdraw-list-wrapper">
                    <li class="withdraw-list">
                        <strong>@lang('Withdraw Amount')</strong>
                        <span class="float-right">{{getAmount($withdraw->amount)}} {{__($withdraw->currency->code)}}</span>
                    </li>
                    <li class="withdraw-list">
                        <strong>@lang('Paid Amount')</strong>
                        <span class="float-right">{{getAmount($withdraw->success_amount)}} {{__($withdraw->currency->code)}}</span>
                    </li>
                    <li class="withdraw-list">
                        <strong>@lang('Remain Amount')</strong>
                        <span class="float-right">{{getAmount($withdraw->remain_amount)}} {{__($withdraw->currency->code)}}</span>
                    </li>
                    <li class="withdraw-list">
                        <strong>@lang('Pending Amount')</strong>
                        <span class="float-right">{{$withdraw->amount - $withdraw->remain_amount - $withdraw->success_amount}} {{__($withdraw->currency->code)}}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-12">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>@lang('Merged at')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Pay From')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdraw->payments as $payment)
                        <tr>
                        <td>{{ showDateTime($payment->created_at) }}</td>
                        <td>{{ getAmount($payment->amount )}} {{__($withdraw->currency->code)}}</td>
                        <td class="fw-bold">{{ $payment->fromUser->fullname}}</td>
                        <td>
                            <span class="badge badge--{{paymentStatus($payment->status)['class']}}">{{paymentStatus($payment->status)['text']}}</span>
                        </td>
                        <td>
                            <a href="{{route('user.payment.detail', $payment->id )}}" class="btn btn--base">@lang('Details')</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center data-not-found">{{__($emptyMessage)}}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

