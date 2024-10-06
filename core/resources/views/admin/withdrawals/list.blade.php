@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Waiting Merge')</th>
                                    <th>@lang('In Transit')</th>
                                    <th>@lang('Paid')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Withdraw at')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals as $withdrawal)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $withdrawal->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $withdrawal->user_id) }}"><span>@</span>{{ $withdrawal->user->username }}</a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ getAmount($withdrawal->amount) }} {{ __($withdrawal->currency->code) }}</span>
                                        </td>
                                        <td>
                                            {{ getAmount($withdrawal->remain_amount) }} {{ __($withdrawal->currency->code) }}
                                        </td>
                                        <td>
                                            {{ getAmount($withdrawal->amount - $withdrawal->remain_amount - $withdrawal->success_amount) }} {{ __($withdrawal->currency->code) }}
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ getAmount($withdrawal->success_amount) }} {{ __($withdrawal->currency->code) }}</span>
                                        </td>

                                        <td>
                                            @if ($withdrawal->status == 1)
                                                <span class="badge badge--success">@lang('Completed')</span>
                                            @else
                                                <span class="badge badge--primary">@lang('Pending')</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ showDateTime($withdrawal->created_at) }}<br>
                                            {{ diffForHumans($withdrawal->created_at) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($withdrawals->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($withdrawals) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
