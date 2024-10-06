@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $auth = getContent('register.content', true);
        $policyPages = getContent('policy_pages.element', false, null, true);
    @endphp
    <section class="account-section pt-80 pb-80">
        <div class="container">
            <div class="account-wrapper">
                <div class="row gy-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="account-thumb-wrapper">
                            <img src="{{ getImage('assets/images/frontend/register/' . @$auth->data_values->background_image, '660x450') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="left">
                            <div class="account-middle">
                                <div class="account-form-area">
                                    <form class="account-form verify-gcaptcha" action="{{ route('user.register') }}" method="POST">
                                        @csrf
                                        <div class="row ml-b-20">

                                            @if( session()->has('reference') )
                                                <h6 class="text-center text-muted mb-3">@lang('Referred By') {{ session()->get('reference') }}</h6>
                                            @endif

                                            <div class="col-lg-6 form-group">
                                                <label for="username">@lang('Username')</label>
                                                <input type="text" class="form-control form--control checkUser" id="username" name="username" value="{{ old('username') }}" maxlength="40" required="">
                                                <small class="text-danger usernameExist"></small>
                                            </div>

                                            <div class="col-lg-6 form-group">
                                                <label for="email">@lang('Email Address')</label>
                                                <input type="email" class="form-control form--control checkUser" id="email" name="email" value="{{ old('email') }}" maxlength="40" required="">
                                            </div>

                                            <div class="col-lg-6 form-group">
                                                <label for="country">@lang('Country')</label>
                                                <select name="country" id="country" class="form-control form--control">
                                                    @foreach ($countries as $key => $country)
                                                        <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-6 form-group">
                                                <label for="mobile">@lang('Mobile')</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text mobile-code"></span>
                                                        <input type="hidden" name="mobile_code">
                                                        <input type="hidden" name="country_code">
                                                    </div>
                                                    <input type="number" name="mobile" id="mobile" value="{{ old('mobile') }}" class="form-control form--control checkUser" required="">
                                                </div>
                                                <small class="text-danger mobileExist"></small>
                                            </div>

                                            <div class="col-lg-6 form-group">
                                                <label for="password">@lang('Password')</label>
                                                <input type="password" id="password" class="form-control form--control" name="password" required="">
                                                @if ($general->secure_password)
                                                    <div class="input-popup">
                                                        <p class="error lower">@lang('1 small letter minimum')</p>
                                                        <p class="error capital">@lang('1 capital letter minimum')</p>
                                                        <p class="error number">@lang('1 number minimum')</p>
                                                        <p class="error special">@lang('1 special character minimum')</p>
                                                        <p class="error minimum">@lang('6 character password')</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 form-group">
                                                <label for="confirm_password">@lang('Confirm Password')</label>
                                                <input type="password" class="form-control form--control" id="confirm_password" name="password_confirmation" required="">
                                            </div>

                                            <x-captcha />

                                            @if ($general->agree)
                                                <div class="col-sm-12 form-group">
                                                    <div class="form--check">
                                                        <input class="form-check-input" type="checkbox" name="agree" id="remember" required>
                                                        <div class="form-check-label">
                                                            <label class="" for="remember">@lang('I agree with')</label>
                                                            <span>
                                                                @foreach ($policyPages as $policy)
                                                                    <a class="text--base" target="_blank" href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}">
                                                                        {{ __($policy->data_values->title) }}
                                                                    </a>
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-lg-12 form-group text-center">
                                                <button type="submit" class="submit-btn w-100">@lang('Register Now')</button>
                                            </div>

                                            <div class="col-lg-12 text-center">
                                                <div class="account-item mt-10">
                                                    <label>@lang('Already Have An Account')? <a href="{{ route('user.login') }}" class="text--base">@lang('Login Now')</a></label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="{{ route('user.login') }}" class="btn btn--base">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .country-code .input-group-text {
            background: #fff !important;
        }

        .country-code select {
            border: none;
        }

        .country-code select:focus {
            border: none;
            outline: none;
        }
    </style>
@endpush
@if ($general->secure_password)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
@push('script')
    <script>
        "use strict";
        (function($) {
            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false && response.type == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
