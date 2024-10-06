@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $auth = getContent('login.content', true);
    @endphp

    <section class="account-section pt-80 pb-80">
        <div class="container">
            <div class="account-wrapper">
                <div class="row gy-5 align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <div class="account-thumb-wrapper">
                            <img src="{{ getImage('assets/images/frontend/login/' . @$auth->data_values->background_image, '660x450') }}"
                                class="mw-100 h-100">
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1 col-md-6">
                        <div class="left float-end">
                            <div class="account-middle">
                                <div class="account-form-area">
                                    <form class="account-form verify-gcaptcha" method="POST"
                                        action="{{ route('user.login') }}">
                                        @csrf
                                        <div class="row ml-b-20">
                                            <div class="col-lg-12 form-group">
                                                <label>@lang('Username or Email')</label>
                                                <input type="text" class="form-control form--control" name="username"
                                                    value="{{ old('username') }}" required>
                                            </div>
                                            <div class="col-lg-12 form-group">
                                                <label>@lang('Password')</label>
                                                <input type="password" class="form-control form--control" name="password"
                                                    required>
                                            </div>
                                            <x-captcha />

                                            <div class="col-lg-12 form-group">
                                                <div class="col-sm-12">
                                                    <div class="d-flex flex-wrap justify-content-between">
                                                        <div class="form--check">
                                                            <input class="form-check-input" name="remember" type="checkbox"
                                                                value="" id="remember">
                                                            <label class="form-check-label"
                                                                for="remember">@lang('Remember me')</label>
                                                        </div>
                                                        <a href="{{ route('user.password.request') }}"
                                                            class="text--base">@lang('Forgot Password')?</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 form-group text-center">
                                                <button type="submit" class="submit-btn w-100">@lang('Login Now')</button>
                                            </div>
                                            <div class="col-lg-12 text-center">
                                                <div class="account-item mt-10">
                                                    <label>@lang("Don't Have An Account")? <a href="{{ route('user.register') }}"
                                                            class="text--base">@lang('Register Now')</a></label>
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
@endsection
