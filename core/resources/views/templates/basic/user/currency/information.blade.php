@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="d-flex flex-wrap justify-content-md-start justify-content-center gap-3">
        @foreach ($currencies as $currency)
            <div class="information-wrapper">
                <div class="information-box">
                    <span class="symbol">{{ $currency->symbol }}</span>
                    <span class="currency-name">{{ $currency->code }}</span>
                </div>
                <button class="updateBtn" data-bs-toggle="modal" data-bs-target="#{{ $currency->code }}Modal">
                    @if ($currency->withdrawInfo)
                        <i class="la la-pencil"></i> @lang('Update')
                    @else
                        <i class="la la-plus"></i> @lang('Add')
                    @endif
                </button>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="{{ $currency->code }}Modal" tabindex="-1" aria-labelledby="{{ $currency->code }}ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ $currency->code }} @lang('Information')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form action="{{ route('user.withdraw.information.store', $currency->id) }}" method="post" role="form" enctype="multipart/form-data">
                            @csrf

                            @php
                                $withdrawInfo = $currency->withdrawInfo;
                                $form = $currency->form;
                                
                                $info = @$withdrawInfo->info;
                            @endphp

                            @if ($currency->form)
                                <div class="modal-body">

                                    @foreach ($form->form_data as $key => $data)
                                        @php
                                            $valueData = collect($info);
                                            $value = $valueData->where('name', $data->name)->first()->value ?? null;
                                        @endphp
                                        <div class="form-group form-generator">
                                            <label class="form-label">{{ __($data->name) }}</label>
                                            @if ($data->type == 'text')
                                                <input type="text" class="form-control form--control" name="{{ $data->label }}" value="{{ old($data->label, $value) }}" @if ($data->is_required == 'required') required @endif>
                                            @elseif($data->type == 'textarea')
                                                <textarea class="form-control form--control" name="{{ $data->label }}" @if ($data->is_required == 'required') required @endif>{{ old($data->label, $value) }}</textarea>
                                            @elseif($data->type == 'select')
                                                <select class="form-control form--control" name="{{ $data->label }}" @if ($data->is_required == 'required') required @endif>
                                                    <option value="">@lang('Select One')</option>
                                                    @foreach ($data->options as $item)
                                                        <option value="{{ $item }}" @selected($item == old($data->label, $value))>
                                                            {{ __($item) }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif($data->type == 'checkbox')
                                                @foreach ($data->options as $key => $option)
                                                    <div class="form--check">
                                                        <input class="form-check-input" name="{{ $data->label }}[]" type="checkbox" value="{{ $option }}" id="{{ $data->label }}_{{ titleToKey($option) }}" @checked(old($data->label[$key], $value) == $option)>
                                                        <label class="form-check-label" for="{{ $data->label }}_{{ titleToKey($option) }}">{{ $option }}</label>
                                                    </div>
                                                @endforeach
                                            @elseif($data->type == 'radio')
                                                @foreach ($data->options as $option)
                                                    <div class="form--radio">
                                                        <input class="form-check-input" name="{{ $data->label }}" type="radio" value="{{ $option }}" id="{{ $data->label }}_{{ titleToKey($option) }}" @checked(old($data->label, $value) == $option)>
                                                        <label class="form-check-label" for="{{ $data->label }}_{{ titleToKey($option) }}">{{ $option }}</label>
                                                    </div>
                                                @endforeach
                                            @elseif($data->type == 'file')
                                                <input type="file" class="form-control form--control" name="{{ $data->label }}" @if ($data->is_required == 'required') required @endif accept="@foreach (explode(',', $data->extensions) as $ext) .{{ $ext }}, @endforeach">
                                                <pre class="text--base mt-1">@lang('Supported mimes'): {{ $data->extensions }}</pre>
                                            @endif
                                        </div>
                                    @endforeach

                                </div>

                                <div class="modal-footer">
                                    <div class="col-xl-12">
                                        <button type="submit" class="submit-btn w-100">@lang('Submit')</button>
                                    </div>
                                </div>
                            @else
                                <div class="modal-body">
                                    <h5 class="text-center">@lang('Nothing to update here')</h5>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
