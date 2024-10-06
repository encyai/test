@extends($activeTemplate.'layouts.master')
@section('content')
<table class="custom-table">
    <thead>
        <tr>
            <th>@lang('User')</th>
            <th>@lang('Total Amount')</th>
            <th>@lang('Status')</th>
            <th>@lang('Date')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($referInvestments as $referInvestment)
            <tr>
                <td>
                    <span>{{$referInvestment->user->fullname}}</span>
                </td>
                <td>
                    {{getAmount($referInvestment->total)}} {{__($referInvestment->currency->code)}}
                </td>
                   
                <td>
                    @if($referInvestment->status == 0)
                        <span class="badge badge--primary">@lang('Pending')</span>
                    @else
                        <span class="badge badge--success">@lang('Complete')</span>
                    @endif
                </td>

                <td>
                    <span>{{showDateTime($referInvestment->created_at)}}</span><br>
                    {{diffForHumans($referInvestment->created_at)}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="100%" class="text-center data-not-found">{{__($emptyMessage)}}</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{$referInvestments->links()}}
@endsection