@extends($activeTemplate.'layouts.master')
@section('content')
<ul class="btn__wrapper justify-content-end">
    @foreach($currencies as $currency)
        <li>
            <form action="" method="GET">
                <input type="hidden" name="currency" value="{{$currency->id}}">
                @if(request()->currency == $currency->id)
                    <button type="submit" class="btn--base">{{__($currency->code)}}</button>
                @else
                    <button type="submit" class="btn--dark">{{__($currency->code)}}</button>
                @endif
            </form>
        </li>
    @endforeach
</ul>
<table class="custom-table">
    <thead>
        <tr>
            <th>@lang('Transaction Id')</th>
            <th>@lang('Amount')</th>
            <th>@lang('From / To')</th>
            <th>@lang('Date')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $transaction)
            <tr>
                <td>{{$transaction->trx}}</td>
                <td>
                    <strong @if($transaction->trx_type == '+') class="text--success" @else class="text--danger" @endif>
                    {{getAmount($transaction->amount)}} {{__($transaction->currency->code)}}</strong>
                </td>

                <td>
                    {{$transaction->details}}
                </td>

                <td>
                    <span>{{diffForHumans($transaction->created_at)}}</span><br>
                    <span>{{showDateTime($transaction->created_at)}}</span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="100%" class="text-center data-not-found">{{__($emptyMessage)}}</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{$transactions->links()}}

@endsection