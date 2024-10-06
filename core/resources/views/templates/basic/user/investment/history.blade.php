@extends($activeTemplate.'layouts.master')
@section('content')
    <table class="custom-table">
        <thead>
            <tr>
                <th>@lang('Invested At')</th>
                <th>@lang('Invested Amount')</th>
                <th>@lang('Paid Amount')</th>
                <th>@lang('Remain Amount')</th>
                <th>@lang('Pending Amount')</th>
                <th>@lang('Status')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invests as $invest)
                <tr>
                    <td>
                        <span>{{diffForHumans($invest->created_at)}}</span><br>
                        <span>{{showDateTime($invest->created_at)}}</span>
                    </td>
                    <td>
                        <span>{{getAmount($invest->amount)}} {{__($invest->currency->code)}}</span>
                    </td>
                    <td>
                        <span>{{getAmount($invest->success_amount)}} {{__($invest->currency->code)}}</span>
                    </td>
                    <td>
                        <span>{{getAmount($invest->remain_amount)}} {{__($invest->currency->code)}}</span>
                    </td>
                    <td>
                        <span>{{ $invest->pendingAmount() }} {{__($invest->currency->code)}}</span>
                    </td>
                    <td>
                        @php
                            echo $invest->statusBadge;
                        @endphp
                    </td>
                    <td>
                        <a href="{{ route('user.invest.detail', $invest->id) }}" class="btn btn--base">
                            <i class="la la-desktop"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center data-not-found">{{__($emptyMessage)}}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{$invests->links()}}
@endsection