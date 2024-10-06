@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row gy-4">

                <div class="col-lg-4">
                    <div class="card custom--card h-100">
                        <div class="card-header bg-white">
                            <div class="mt-3 d-flex gap-3 justify-content-center">
                                <span><i class="la la-check-circle text--success"></i> @lang('Email')</span>
                                <span><i class="la la-check-circle text--success"></i> @lang('Mobile')</span>
                                <span><i class="la la-check-circle text--success"></i> @lang('KYC')</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="d-flex align-items-center gap-2"><i class="la la-user"></i> {{ auth()->user()->username }}</p>
                            <p class="d-flex align-items-center gap-2"><i class="la la-user-tie"></i> {{ auth()->user()->fullname }}</p>
                            <p class="d-flex align-items-center gap-2"><i class="la la-envelope"></i> {{ auth()->user()->email }}</p>
                            <p class="d-flex align-items-center gap-2"><i class="la la-mobile"></i> +{{ auth()->user()->mobile }}</p>
                            <p class="d-flex align-items-center gap-2"><i class="la la-globe"></i> {{ auth()->user()->address->country }}</p>
                            <p class="d-flex align-items-center gap-2"><i class="la la-map-marked"></i> {{ auth()->user()->address->address }}</p>

                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card custom--card">
                        <div class="card-body">
                            <form class="register" action="" method="post">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('First Name')</label>
                                            <input type="text" class="form-control form--control" name="firstname" value="{{ $user->firstname }}" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Last Name')</label>
                                            <input type="text" class="form-control form--control" name="lastname" value="{{ $user->lastname }}" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('State')</label>
                                            <input type="text" class="form-control form--control" name="state" value="{{ @$user->address->state }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('City')</label>
                                            <input type="text" class="form-control form--control" name="city" value="{{ @$user->address->city }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Zip Code')</label>
                                            <input type="text" class="form-control form--control" name="zip" value="{{ @$user->address->zip }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Address')</label>
                                            <input type="text" class="form-control form--control" name="address" value="{{ @$user->address->address }}">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
