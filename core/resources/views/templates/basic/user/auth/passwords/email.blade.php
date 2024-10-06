@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="account-section pt-80 pb-80">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-5 col-md-8">                
                <div class="account-wrapper">
                    <div class="account-middle">
                        <p class="f-16 mb-3">@lang('To recover your account please provide your email or username to find your account.')</p>
                        <div class="account-form-area">
                            <form class="account-form" method="POST" action="{{ route('user.password.email') }}">
                                @csrf
                                <div class="row mb-10">
                                    <div class="col-lg-12 form-group">
                                        <label for="value" class="my_value">@lang('Email or Username')</label>
                                        <input type="text" class="form-control form--control" id="value"
                                            name="value" value="{{ old('value') }}" required autofocus="off">
                                    </div>
                                    <div class="col-lg-12 text-center">
                                        <button type="submit" class="submit-btn w-100">@lang('Send Password Code')</button>
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
@endsection
