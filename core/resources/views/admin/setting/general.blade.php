@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group ">
                                    <label> @lang('Site Title')</label>
                                    <input class="form-control" type="text" name="site_name" required value="{{ $general->site_name }}">
                                </div>
                            </div>

                            <div class="form-group col-md-4 col-sm-6">
                                <label> @lang('Timezone')</label>
                                <select class="select2-basic" name="timezone">
                                    @foreach ($timezones as $timezone)
                                        <option value="'{{ @$timezone }}'">{{ __($timezone) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-sm-6">
                                <label> @lang('Site Base Color')</label>
                                <div class="input-group">
                                    <span class="input-group-text p-0 border-0">
                                        <input type='text' class="form-control colorPicker" value="{{ $general->base_color }}" />
                                    </span>
                                    <input type="text" class="form-control colorCode" name="base_color" value="{{ $general->base_color }}" />
                                </div>
                            </div>
                            <div class="form-group col-xl-3 col-lg-4 col-sm-6">
                                <label> @lang('Site Secondary Color')</label>
                                <div class="input-group">
                                    <span class="input-group-text p-0 border-0">
                                        <input type='text' class="form-control colorPicker" value="{{ $general->secondary_color }}" />
                                    </span>
                                    <input type="text" class="form-control colorCode" name="secondary_color" value="{{ $general->secondary_color }}" />
                                </div>
                            </div>

                            <div class="form-group col-xl-3 col-lg-4 col-sm-6">
                                <label> @lang('Payment Duration')</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="payment_duration" value="{{ $general->payment_duration }}" />
                                    <span class="input-group-text">@lang('Hours')</span>
                                </div>
                            </div>

                            <div class="form-group col-xl-3 col-lg-4 col-sm-6">
                                <label> @lang('Confirm Duration')</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="confirm_duration" value="{{ $general->confirm_duration }}" />
                                    <span class="input-group-text">@lang('Hours')</span>
                                </div>
                            </div>

                            <div class="form-group col">
                                <label> @lang('Recommit Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="recommit_amount" value="{{ $general->recommit_amount }}" />
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function(color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });

            $('select[name=timezone]').val("'{{ config('app.timezone') }}'").select2();
            $('.select2-basic').select2({
                dropdownParent: $('.card-body')
            });
        })(jQuery);
    </script>
@endpush
