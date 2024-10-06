@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body">
                    <div class="row gy-4 justify-content-center">
                        <div class="col-lg-4 col-md-6 col-sm-10">
                            <h5 class="mb-3">@lang('Investment')</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Investment Amount')
                                    <span class="fw-bold">{{ showAmount($investment->amount) }} {{ __($investment->currency->code) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Paid Amount')
                                    <span class="fw-bold">{{ showAmount($investment->success_amount) }} {{ __($investment->currency->code) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Remain Amount')
                                    <span class="fw-bold">{{ showAmount($investment->remain_amount) }} {{ __($investment->currency->code) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Pending Amount')
                                    <span class="fw-bold">{{ showAmount($investment->pendingAmount()) }} {{ __($investment->currency->code) }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-10">
                            <h5 class="mb-3">@lang('Recommitment')</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Recommitment Amount')
                                    <span class="fw-bold">{{ showAmount($investment->recommit_amount) }} {{ __($investment->currency->code) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Paid Amount')
                                    <span class="fw-bold">{{ showAmount($investment->recommit_success) }} {{ __($investment->currency->code) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Remain Amount')
                                    <span class="fw-bold">{{ showAmount($investment->recommit_remain) }} {{ __($investment->currency->code) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Pending Amount')
                                    <span class="fw-bold">{{ showAmount($investment->pendingRecommitAmount()) }} {{ __($investment->currency->code) }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-10">
                            <h5 class="mb-3">@lang('Withdraw')</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Profit')
                                    <span class="fw-bold">{{ showAmount($investment->profit) }} {{ __($investment->currency->code) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Withdrawable Amount')
                                    <span class="fw-bold">{{ showAmount($investment->withdrawal_amount) }} {{ __($investment->currency->code) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Recommitment Status')
                                    @if ($investment->recommit_status == 1)
                                        <span class="badge badge--success">@lang('Completed')</span>
                                    @else
                                        <span class="badge badge--warning">@lang('Waiting')</span>
                                    @endif
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Withdraw Status')
                                    @if ($investment->withdrawn_at)
                                        <span class="badge badge--success">@lang('Completed')</span>
                                    @else
                                        <span class="badge badge--warning">@lang('Waiting')</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-lg-12">
                            <div class="table-responsive--sm table-responsive">
                                <table class="table table--light style--two">
                                    <thead>
                                        <tr>
                                            <th>@lang('From User')</th>
                                            <th>@lang('To User')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Info')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($investment->payments as $payment)
                                            <tr>
                                                <td>
                                                    <span class="fw-bold">{{ $payment->fromUser->fullname }}</span>
                                                    <br>
                                                    <span class="small">
                                                        <a href="{{ route('admin.users.detail', $payment->from_user_id) }}"><span>@</span>{{ $payment->fromUser->username }}</a>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold">{{ $payment->toUser->fullname }}</span>
                                                    <br>
                                                    <span class="small">
                                                        <a href="{{ route('admin.users.detail', $payment->to_user_id) }}"><span>@</span>{{ $payment->toUser->username }}</a>
                                                    </span>
                                                </td>

                                                <td>
                                                    <span class="fw-bold">{{ showAmount($payment->amount) }} {{ __($payment->investment->currency->code) }}</span>
                                                </td>
                                                <td>
                                                    @if ($payment->info)
                                                        {{ __($payment->info) }}
                                                    @else
                                                        @lang('N/A')
                                                    @endif
                                                </td>

                                                <td>
                                                    @php echo $payment->statusBadge @endphp
                                                </td>

                                                <td>
                                                    <a href="{{ route('admin.payment.detail', $payment->id) }}" class="btn btn-sm btn-outline--primary"><i class="las la-desktop"></i> @lang('Details')</a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
