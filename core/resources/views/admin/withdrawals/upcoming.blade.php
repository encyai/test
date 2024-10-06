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
                                    <th>@lang('Amount Invested')</th>
                                    <th>@lang('Withdrawal Amount')</th>
                                    <th>@lang('Withdraw Available')</th>
                                    <th>@lang('Invested at')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingWithdrawals as $upcomingWithdrawal)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $upcomingWithdrawal->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $upcomingWithdrawal->user_id) }}"><span>@</span>{{ $upcomingWithdrawal->user->username }}</a>
                                            </span>
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ getAmount($upcomingWithdrawal->amount) }} {{ __($upcomingWithdrawal->currency->code) }}</span>
                                        </td>

                                        <td>
                                            {{ getAmount($upcomingWithdrawal->withdrawal_amount) }} {{ __($upcomingWithdrawal->currency->code) }}
                                        </td>

                                        <td>
                                            @if ($upcomingWithdrawal->withdrawable_time < Carbon\Carbon::now())
                                                <span class="badge badge--primary">@lang('Available')</span>
                                            @else
                                                <span class="badge badge--warning">{{ diffForHumans($upcomingWithdrawal->withdrawable_time) }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ showDateTime($upcomingWithdrawal->created_at) }}<br>
                                            <span class="fw-bold">{{ diffForHumans($upcomingWithdrawal->created_at) }}</span>
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
                @if ($upcomingWithdrawals->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($upcomingWithdrawals) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
