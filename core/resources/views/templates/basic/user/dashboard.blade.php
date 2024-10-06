@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $kycInfo = getContent('kyc_info.content', true);
    @endphp
    <div class="row justify-content-center gy-4">

        @if (auth()->user()->kv == 0)
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                    <h5 class="alert-heading mt-0">@lang('KYC Verification required')</h5>
                    <hr>
                    <p class="mb-0">{{ __($kycInfo->data_values->verification_content) }} <a class="text--base"
                            href="{{ route('user.kyc.form') }}">@lang('Click Here to Verify')</a>
                    </p>
                </div>
            </div>
        @elseif(auth()->user()->kv == 2)
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    <h5 class="alert-heading mt-0">@lang('KYC Verification pending')</h5>
                    <hr>
                    <p class="mb-0">{{ __($kycInfo->data_values->pending_content) }} <a class="text--base"
                            href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>
                </div>
            </div>
        @endif

        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="dashboard-item">
                <a href="{{ route('user.invest.history') }}" class="dash-btn">@lang('View all')</a>
                <div class="dashboard-content">
                    <div class="dashboard-icon">
                        <i class="las la-wallet"></i>
                    </div>
                    <h5 class="title">@lang('Total') <span class="text--base">@lang('Investment')</span></h5>
                    <h4 class="num mb-0">{{ $widget['investments'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="dashboard-item">
                <a href="{{ route('user.withdraw.history') }}" class="dash-btn">@lang('View all')</a>
                <div class="dashboard-content">
                    <div class="dashboard-icon">
                        <i class="las la-credit-card"></i>
                    </div>
                    <h5 class="title">@lang('Total') <span class="text--base">@lang('Withdraw')</span></h5>
                    <h4 class="num mb-0">{{ $widget['withdrawals'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="dashboard-item">
                <a href="{{ route('user.referral.log') }}" class="dash-btn">@lang('View all')</a>
                <div class="dashboard-content">
                    <div class="dashboard-icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <h5 class="title">@lang('Referral') <span class="text--base">@lang('User')</span></h5>
                    <h4 class="num mb-0">{{ $widget['referred'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="dashboard-item">
                <a href="{{ route('user.transactions.history') }}" class="dash-btn">@lang('View all')</a>
                <div class="dashboard-content">
                    <div class="dashboard-icon">
                        <i class="las la-coins"></i>
                    </div>
                    <h5 class="title">@lang('Total') <span class="text--base">@lang('Transaction')</span></h5>
                    <h4 class="num mb-0">{{ $widget['transactions'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="referral-area ptb-80">
        <div class="input-group">
            <input type="text" name="key" value="{{ route('home') }}?reference={{ auth()->user()->username }}"
                class="form-control form--control referralURL" readonly="" id="key">
            <button type="button" class="input-group-text copytext" id="copyBoard"> <i class="fa fa-copy"></i> </button>
        </div>
    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th>@lang('Time')</th>
                <th>@lang('TRX')</th>
                <th>@lang('Type')</th>
                <th>@lang('Details')</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>
                        <span title="{{ $transaction->created_at->diffForHumans() }}">
                            {{ showDateTime($transaction->created_at) }}
                        </span>
                    </td>
                    <td class="fw-bold">{{ $transaction->trx }}</td>
                    <td class="@if ($transaction->trx_type == '+') text--success @else text--danger @endif fw-bold">
                        {{ $transaction->trx_type }}{{ showAmount($transaction->amount) }}
                        {{ @$transaction->currency->code }}</td>
                    <td>{{ $transaction->details }}</td>

                </tr>
            @empty
                <tr>
                    <td class="text-center data-not-found" colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@push('style')
    <style>
        .copied::after {
            background-color: #{{ $general->base_color }};
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#copyBoard').click(function() {
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
        })(jQuery);
    </script>
@endpush
