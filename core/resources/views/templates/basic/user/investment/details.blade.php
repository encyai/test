@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 justify-content-center">
        <div class="col-lg-4">
            <div class="card custom--card mb-3">
                <div class="card-header">
                    <h4 class="mb-0 text-white">@lang('Investment Info')</h4>
                </div>
                <div class="card-body p-0">
                    <ul class="withdraw-list-wrapper">
                        <li class="withdraw-list">
                            <strong>@lang('Invested Amount')</strong>
                            <span class="float-right">{{ getAmount($investment->amount) }} {{ __($investment->currency->code) }}</span>
                        </li>
                        <li class="withdraw-list">
                            <strong>@lang('Paid Amount')</strong>
                            <span class="float-right">{{ getAmount($investment->success_amount) }} {{ __($investment->currency->code) }}</span>
                        </li>
                        <li class="withdraw-list">
                            <strong>@lang('Remaining Amount')</strong>
                            <span class="float-right">{{ getAmount($investment->remain_amount) }} {{ __($investment->currency->code) }}</span>
                        </li>
                        <li class="withdraw-list">
                            <strong>@lang('Pending Amount')</strong>
                            <span class="float-right">{{ $investment->pendingAmount() }} {{ __($investment->currency->code) }}</span>
                        </li>
                        <li class="withdraw-list">
                            <strong>@lang('Profit')</strong>
                            <span class="float-right">{{ getAmount($investment->profit) }} {{ __($investment->currency->code) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            @if (!$investment->is_activation)
                <div class="card custom--card mb-3">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h4 class="mb-0 text-white">@lang('Recommitment Info')</h4>
                        @if ($investment->recommit_status == 1)
                            <span class="badge badge--success">@lang('Completed')</span>
                        @else
                            <span class="badge badge--warning">@lang('Waiting')</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <ul class="withdraw-list-wrapper">
                            <li class="withdraw-list">
                                <strong>@lang('Total Amount')</strong>
                                <span class="float-right">{{ getAmount($investment->recommit_amount) }} {{ __($investment->currency->code) }}</span>
                            </li>
                            <li class="withdraw-list">
                                <strong>@lang('Paid Amount')</strong>
                                <span class="float-right">{{ getAmount($investment->recommit_success) }} {{ __($investment->currency->code) }}</span>
                            </li>
                            <li class="withdraw-list">
                                <strong>@lang('Remain Amount')</strong>
                                <span class="float-right">{{ getAmount($investment->recommit_remain) }} {{ __($investment->currency->code) }}</span>
                            </li>
                            <li class="withdraw-list">
                                <strong>@lang('Pending Amount')</strong>
                                <span class="float-right">{{ $investment->pendingRecommitAmount() }} {{ __($investment->currency->code) }}</span>
                            </li>

                        </ul>
                    </div>
                </div>
            @endif

            <div class="card custom--card">
                <div class="card-header d-flex flex-wrap justify-content-between">
                    <h4 class="mb-0 text-white">@lang('Withdrawal Info')</h4>
                    @if ($investment->is_activation)
                        <span class="badge badge--danger">@lang('Not Withdrawable')</span>
                    @elseif ($investment->withdrawn_at)
                        <span class="badge badge--success">@lang('Completed')</span>
                    @elseif($investment->withdrawable_time < now())
                        <span class="badge badge--primary">@lang('Withdrawable')</span>
                    @else
                        <span class="badge badge--warning">@lang('Waiting')</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <ul class="withdraw-list-wrapper">
                        <li class="withdraw-list">
                            <strong>@lang('Withdrawable Amount')</strong>
                            <span class="float-right">{{ getAmount($investment->withdrawable_amount) }} {{ __($investment->currency->code) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>@lang('Merged At')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Pay to')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($investment->payment as $payment)
                        <tr>
                            <td>
                                <div class="div">
                                    <span>{{ diffForHumans($payment->created_at) }}</span><br />
                                    <span>{{ showDateTime($payment->created_at) }}</span>
                                </div>
                            </td>

                            <td>
                                <span>{{ getAmount($payment->amount) }} {{ __($payment->investment->currency->code) }}</span>
                            </td>

                            <td>
                                <span>{{ $payment->toUser->fullname }}</span>
                            </td>

                            <td>
                                @php echo $payment->statusBadge @endphp
                            </td>

                            <td>
                                <a href="{{ route('user.payment.detail', $payment->id) }}" class="btn btn--base"><i class="la la-desktop"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="data-not-found text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
