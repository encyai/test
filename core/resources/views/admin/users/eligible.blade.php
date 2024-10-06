@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <form action="{{ route('admin.eligible.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Username') </label>
                                    <input type="text" class="form-control checkUser" name="username" value="{{ old('username') }}" required />
                                    <small class="userValidationMessage"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Plans') </label>
                                    <select name="plan_id" class="form-control" required>
                                        <option value="">@lang('Select one')</option>
                                        @foreach ($plans as $plan)
                                            <option @if (old('plan_id') == $plan->id) selected @endif value="{{ $plan->id }}" data-currency="{{ $plan->currency->code }}">{{ __($plan->name) }} - {{ __($plan->currency->code) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Amount He\'ll Get') </label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="amount" value="{{ old('amount') }}" required />
                                        <span class="input-group-text currencyText"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Backdate') </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="back_days" value="{{ old('back_days') }}" required />
                                        <span class="input-group-text">@lang('Days')</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            let plans = $('select[name=plan_id]');
            setCurrency();
            plans.on('change', function() {
                setCurrency();
            });

            function setCurrency() {
                $('.currencyText').text(plans.find(':selected').data('currency'));
            }
            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('admin.users.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                var data = {
                    username: value,
                    _token: token
                }
                if (value == '') {
                    $('.userValidationMessage').text('');
                    return false;
                }
                $.post(url, data, function(response) {
                    if (response.data == false) {
                        $('.userValidationMessage').html(`<i class="la la-times-circle"></i> @lang('User not found')`).removeClass('text--success').addClass('text--danger');
                    } else {
                        $('.userValidationMessage').html(`<i class="la la-check-circle"></i> @lang('User found')`).addClass('text--success').removeClass('text--danger');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
