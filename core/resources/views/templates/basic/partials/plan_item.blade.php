@foreach ($plans as $invest)
    <div class="col-lg-4 col-sm-6 col-10">
        <div class="plan-item">
            <div class="plan-header text-center">
                <h3 class="title">{{ __($invest->name) }}</h3>
            </div>
            <div class="plan-body text-center">
                <ul class="plan-list">
                    <li>
                        <h3 class="title">@lang('Minimum Invest')</h3>
                        <span class="sub-title text--base">{{ showAmount($invest->min_investment_limit) }}
                            {{ $invest->currency->code }}</span>
                    </li>

                    <li>
                        <h3 class="title">@lang('Maximum Invest')</h3>
                        <span class="sub-title text--base">{{ showAmount($invest->max_investment_limit) }}
                            {{ $invest->currency->code }}</span>
                    </li>

                    <li>
                        <h3 class="title">@lang('Profit')</h3>
                        <span class="sub-title text--base">{{ getAmount($invest->profit) }} % @lang('After')
                            {{ $invest->duration }} @lang('Days')</span>
                    </li>
                </ul>
            </div>
            <div class="plan-footer text-center">
                <div class="plan-btn">
                    <button type="button" class="btn--base w-100 investplan" data-plan_id="{{ $invest->id }}" data-code="{{ $invest->currency->code }}" data-bs-toggle="modal" data-bs-target="#investModal">@lang('Invest On') {{ $invest->currency->code }}</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.investplan').on('click', function() {
                var planId = $(this).data('plan_id');
                var code = $(this).data('code');
                $('.currencytext').text(code);
                $('.planid').val(planId);
            });
        })(jQuery)
    </script>
@endpush

@push('modal')
    <div class="modal fade" id="investModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">@lang('Investment Now')</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('user.invest.store') }}">
                    @csrf
                    <input type="hidden" name="plan_id" class="planid">
                    <div class="modal-body">
                        @auth
                            <div class="form-group">
                                <label>@lang('Invest Amount')</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="amount" placeholder="@lang('Enter an Amount')">
                                    <span class="input-group-text currencytext"></span>
                                </div>
                            </div>
                        @endauth
                        @guest
                            <h5>@lang('This action required login.') @lang('You may') <a href="{{ route('user.login') }}" class="text--base">@lang('Login Now')</a></h5>

                        @endguest
                    </div>
                    @auth
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    @endauth
                </form>
            </div>
        </div>
    </div>
@endpush

@push('style')
    <style>
        .plan-footer::before,
        .plan-footer::after {
            background-color: #fff;
        }
    </style>
@endpush
