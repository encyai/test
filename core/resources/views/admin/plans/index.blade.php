@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Limit')</th>
                                    <th>@lang('Profit')</th>
                                    <th>@lang('Duration')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                    <tr>
                                        <td>
                                            {{ __($plan->name) }}
                                        </td>

                                        <td>
                                            <strong>{{ showAmount($plan->min_investment_limit) }} -
                                                {{ showAmount($plan->max_investment_limit) }}
                                                {{ $plan->currency->code }}</strong>
                                        </td>

                                        <td>
                                            {{ showAmount($plan->profit) }}%
                                        </td>

                                        <td>
                                            {{ $plan->duration }} @lang('days')
                                        </td>

                                        <td>
                                            @php echo $plan->statusBadge @endphp
                                        </td>

                                        <td>
                                            <button class="btn btn-sm btn-outline--primary ml-1 cuModalBtn" data-modal_title="@lang('Update Plan')" data-resource="{{ $plan }}" data-has_status="1"><i class="las la-pen"></i>
                                                @lang('Edit')</button>

                                            @if ($plan->status == Status::ENABLE)
                                                <button class="btn btn-sm btn-outline--danger ml-1 confirmationBtn" data-question="@lang('Are you sure to disable this plan?')" data-action="{{ route('admin.plan.status', $plan->id) }}">
                                                    <i class="las la-eye-slash"></i> @lang('Disable')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--success ml-1 confirmationBtn" data-question="@lang('Are you sure to disable this plan?')" data-action="{{ route('admin.plan.status', $plan->id) }}">
                                                    <i class="las la-eye-slash"></i> @lang('Enable')
                                                </button>
                                            @endif

                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($plans->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($plans) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ADD METHOD MODAL --}}
    <div id="cuModal" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Continent')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.plan.save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name') </label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Currency') </label>
                                    <select name="currency_id" class="form-control selectCurrency" required>
                                        @foreach ($currencies as $currency)
                                            <option @if (old('currency_id') == $currency->id) selected @endif value="{{ $currency->id }}">{{ __($currency->code) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Profit') </label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="profit" value="{{ old('profit') }}" required />
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Duration') </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="duration" value="{{ old('duration') }}" required />
                                        <span class="input-group-text">@lang('Days')</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="border-line-area mt-3">
                            <h6 class="border-line-title">@lang('Investment Limit')</h6>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Minimum') </label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="min_investment_limit" value="{{ old('min') }}" required />
                                        <span class="input-group-text currencyText"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Maximum') </label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="max_investment_limit" value="{{ old('max') }}" required />
                                        <span class="input-group-text currencyText"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/cu-modal.js') }}"></script>
@endpush

@push('breadcrumb-plugins')
    <x-search-form placeholder="Name" />
    <button type="button" class="btn btn-outline--primary h-45 cuModalBtn" data-modal_title="@lang('Add New Plan')">
        <i class="las la-plus"></i> @lang('Add New')
    </button>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            let currencyText = $('[name=currency_id]').find(':selected').text();
            setCurrencyText();

            $('body').on('change', '[name=currency_id]', function() {
                currencyText = $('[name=currency_id]').find(':selected').text();
                setCurrencyText();
            });

            $('.cuModalBtn').on('click', function() {
                let text = $(this).data('resource') ? $(this).data('resource').currency.code : currencyText;
                $('.currencyText').text(text);
            });

            function setCurrencyText() {
                $('.currencyText').text(currencyText);
            }
        })(jQuery);
    </script>
@endpush
