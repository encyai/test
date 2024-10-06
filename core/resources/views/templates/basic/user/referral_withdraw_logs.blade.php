@extends($activeTemplate.'layouts.master')
@section('content')
<table class="custom-table">
    <thead>
        <tr>
            <th>@lang('Date')</th>
            <th>@lang('Amount')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($refWithdrawLogs as $data)
            <tr>
                <td>
                    {{showDateTime($data->created_at)}}
                </td>
                <td>
                    {{getAmount($data->amount)}} {{$data->currency->code}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="100%" class="text-center data-not-found">{{__($emptyMessage)}}</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{$refWithdrawLogs->links()}}
@endsection