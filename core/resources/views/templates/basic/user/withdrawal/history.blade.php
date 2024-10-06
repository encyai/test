@extends($activeTemplate.'layouts.master')
@section('content')
<table class="custom-table">
    <thead>
        <tr>
            <th>@lang('Withdraw at')</th>
            <th>@lang('Amount')</th>
            <th>@lang('Paid Amount')</th>
            <th>@lang('Action')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($withdrawals as $withdraw)
            <tr>
                <td>
                    <span>{{diffForHumans($withdraw->created_at)}}</span><br>
                    <span>{{showDateTime($withdraw->created_at)}}</span>
                </td>

                <td>
                    <span>{{getAmount($withdraw->amount)}} {{__($withdraw->currency->code)}}</span>
                </td>

                <td>
                    <span>{{getAmount($withdraw->success_amount)}} {{__($withdraw->currency->code)}}</span>
                </td>
                
                <td>
                    <a href="{{route('user.withdraw.details', $withdraw->id)}}" class="btn btn--base"><i class="fa fa-desktop"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="100%" class="text-center data-not-found">{{__($emptyMessage)}}</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{$withdrawals->links()}}
@endsection