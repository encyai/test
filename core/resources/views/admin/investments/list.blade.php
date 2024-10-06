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
                                    <th>@lang('Plan')</th>
                                    <th>@lang('Amount - Waiting Merge')</th>
                                    <th>@lang('In Transit - Paid')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Invested at')</th>
                                    <th>@lang('Withdraw')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($investments as $investment)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $investment->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $investment->user_id) }}"><span>@</span>{{ $investment->user->username }}</a>
                                            </span>
                                        </td>

                                        <td>
                                            @if ($investment->plan_id)
                                                {{ __($investment->plan->name) }}
                                            @else
                                                <span>@lang('Activation Payment')</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="fw-bold" title="Total investment">{{ getAmount($investment->amount) }} {{ __($investment->currency->code) }}</span><br>
                                            <span class="text--info" title="Waiting to Merge">{{ getAmount($investment->remain_amount) }} {{ __($investment->currency->code) }}</span>
                                        </td>

                                        <td>
                                            {{ showAmount($investment->pendingAmount()) }} {{ __($investment->currency->code) }}
                                            <br>
                                            <span class="fw-bold text--success">{{ showAmount($investment->success_amount) }} {{ __($investment->currency->code) }}</span>
                                        </td>

                                        <td>
                                            @if ($investment->status == 1)
                                                <span class="badge badge--success">@lang('Completed')</span>
                                            @else
                                                <span class="badge badge--primary">@lang('Pending')</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ showDateTime($investment->created_at) }}<br>
                                            <span>{{ $investment->created_at->diffForHumans() }}</span>
                                        </td>

                                        <td>
                                            @if ($investment->is_activation == 1)
                                                <span class="badge badge--primary">@lang('Activation Payment')</span>
                                            @else
                                                @if ($investment->withdrawn_at != null)
                                                    <span class="badge badge--success">@lang('Completed')</span><br>
                                                    {{ $investment->withdrawn_at }}<br>
                                                    <span>{{ $investment->withdrawn_at->diffForHumans() }}</span>
                                                @else
                                                    @if ($investment->recommit_status == 1)
                                                        @if ($investment->withdrawal_available < Carbon\Carbon::now())
                                                            <span class="badge badge--primary">@lang('Available')</span>
                                                        @else
                                                            <span class="badge badge--dark">{{ $investment->withdrawal_available->diffForHumans() }}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge--warning">@lang('Re Commitment')</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('admin.investment.detail', $investment->id) }}" class="btn btn-sm btn-outline--primary"><i class="las la-desktop"></i> @lang('Details')</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($investments->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($investments) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
