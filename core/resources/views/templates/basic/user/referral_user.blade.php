@extends($activeTemplate.'layouts.master')
@section('content')
<table class="custom-table">
    <thead>
        <tr>
            <th>@lang('User')</th>
            <th>@lang('Invested')</th>
            <th>@lang('Refer More')</th>
            <th>@lang('Status')</th>
            <th>@lang('Joined at')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($referralUsers as $referralUser)
            <tr>
                <td>
                    <span>{{$referralUser->fullname}}</span>
                </td>
                <td>
                    <a href="{{route('user.referral.investment', $referralUser->id)}}" class="btn btn--primary btn-sm text-white">@lang('View All')</a>
                </td>
                <td>
                    {{$referralUser->referralCount()}}
                </td>
                <td>
                    @if($referralUser->activation == 0)
                        <span class="badge badge--warning">@lang('Inactive')</span>
                    @else
                        <span class="badge badge--success">@lang('Active')</span>
                    @endif
                </td>

                <td>
                    <span>{{showDateTime($referralUser->created_at)}}</span><br>
                    {{diffForHumans($referralUser->created_at)}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="100%" class="text-center data-not-found">{{__($emptyMessage)}}</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{  $referralUsers->links() }}
@endsection