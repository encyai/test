@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Commission Via')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($referralLogs as $referralLog)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{$referralLog->user->fullname}}</span>
                                        <br>
                                        <span class="small">
                                        <a href="{{ route('admin.users.detail', $referralLog->from_user_id) }}"><span>@</span>{{ $referralLog->user->username }}</a>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{getAmount($referralLog->amount)}} {{__($referralLog->currency->code)}}</span>
                                    </td>

                                    <td>
                                        <span class="fw-bold">{{$referralLog->byWho->fullname}}</span>
                                        <br>
                                        <span class="small">
                                        <a href="{{ route('admin.users.detail', $referralLog->user_id) }}"><span>@</span>{{ $referralLog->byWho->username }}</a>
                                        </span>
                                    </td>

                                    <td>
                                        <span>{{__($referralLog->description)}}</span>
                                    </td>

                                     <td>
                                        <span>{{showDateTime($referralLog->created_at)}}</span><br>
                                        <span class="fw-bold">{{diffForHumans($referralLog->created_at)}}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                @if($referralLogs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($referralLogs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
