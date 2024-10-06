@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('admin.currency.save', @$currency->id) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', @$currency->name) }}" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Code')</label>
                                    <input type="text" class="form-control" name="code" value="{{ old('code', @$currency->code) }}" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Symbol')</label>
                                    <input type="text" class="form-control" name="symbol" value="{{ old('symbol', @$currency->symbol) }}" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Activation Fees') </label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="activation_fees" value="{{ old('activation_fees', getAmount(@$currency->activation_fees)) }}" required />
                                        <span class="input-group-text currencyText">{{ old('code', @$currency->code) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card border--primary mt-3">
                                <div class="card-header bg--primary d-flex justify-content-between">
                                    <h5 class="text-white">@lang('User Data')</h5>
                                    <button type="button" class="btn btn-sm btn-outline-light float-end form-generate-btn"> <i class="la la-fw la-plus"></i>@lang('Add New')</button>
                                </div>
                                <div class="card-body">
                                    <div class="row addedField">
                                        @if (@$form)
                                            @foreach ($form->form_data as $formData)
                                                <div class="col-md-4">
                                                    <div class="card border mb-3" id="{{ $loop->index }}">
                                                        <input type="hidden" name="form_generator[is_required][]" value="{{ $formData->is_required }}">
                                                        <input type="hidden" name="form_generator[extensions][]" value="{{ $formData->extensions }}">
                                                        <input type="hidden" name="form_generator[options][]" value="{{ implode(',', $formData->options) }}">

                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label>@lang('Label')</label>
                                                                <input type="text" name="form_generator[form_label][]" class="form-control" value="{{ $formData->name }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>@lang('Type')</label>
                                                                <input type="text" name="form_generator[form_type][]" class="form-control" value="{{ $formData->type }}" readonly>
                                                            </div>
                                                            @php
                                                                $jsonData = json_encode([
                                                                    'type' => $formData->type,
                                                                    'is_required' => $formData->is_required,
                                                                    'label' => $formData->name,
                                                                    'extensions' => explode(',', $formData->extensions) ?? 'null',
                                                                    'options' => $formData->options,
                                                                    'old_id' => '',
                                                                ]);
                                                            @endphp
                                                            <div class="btn-group w-100">
                                                                <button type="button" class="btn btn--primary editFormData" data-form_item="{{ $jsonData }}" data-update_id="{{ $loop->index }}"><i class="las la-pen"></i></button>
                                                                <button type="button" class="btn btn--danger removeFormData"><i class="las la-times"></i></button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
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
    <x-form-generator />
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.currency.index') }}" />
@endpush

@push('script')
    <script>
        "use strict"
        var formGenerator = new FormGenerator();
        @if( @$form)
            formGenerator.totalField = {{ $form ? count((array) $form->form_data) : 0 }}
        @endif
    </script>

    <script src="{{ asset('assets/global/js/form_actions.js') }}"></script>
    <script>
        "use strict";
        (function($) {
            $('[name=code]').on('input', function() {
                $('.currencyText').text($(this).val());
            });
            
            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.user-data').remove();
            });
        })(jQuery);
    </script>
@endpush