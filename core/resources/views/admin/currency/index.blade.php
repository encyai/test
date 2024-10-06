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
                                    <th>@lang('Code')</th>
                                    <th>@lang('Symbol')</th>
                                    <th>@lang('Activation Fees')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($currencies as $currency)
                                    <tr>
                                        <td>{{ $currency->name }}</td>
                                        <td>{{ $currency->code }}</td>
                                        <td>{{ $currency->symbol }}</td>
                                        <td>{{ showAmount($currency->activation_fees) }} {{ $currency->code }}</td>
                                        <td>
                                            @php echo $currency->statusBadge @endphp
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.currency.edit', $currency->id) }}"><button class="btn btn-sm btn-outline--primary ml-1"><i class="las la-pen"></i>@lang('Edit')</button></a>
                                            @if ($currency->status == Status::ENABLE)
                                                <button class="btn btn-sm btn-outline--danger ml-1 confirmationBtn" data-question="@lang('Are you sure to disable this currency?')" data-action="{{ route('admin.currency.status', $currency->id) }}">
                                                    <i class="las la-eye-slash"></i>@lang('Disable')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--success ml-1 confirmationBtn" data-question="@lang('Are you sure to disable this currency?')" data-action="{{ route('admin.currency.status', $currency->id) }}">
                                                    <i class="las la-eye-slash"></i>@lang('Enable')
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
                @if ($currencies->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($currencies) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Name / Code" />
    <a class="btn btn-outline--primary btn-lg h-45" href="{{ route('admin.currency.create') }}"><i class="las la-plus"></i>@lang('Add New')</a>
@endpush
