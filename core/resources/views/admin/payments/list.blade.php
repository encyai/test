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
                                    <th>@lang('From')</th>
                                    <th>@lang('To')</th>
                                    <th>@lang('Trx')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $payment->fromUser->fullname }}</span>
                                            <br />
                                            <a href="{{ route('admin.users.detail', $payment->from_user_id) }}">@lang('@'){{ $payment->fromUser->username }}</a>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $payment->toUser->fullname }}</span>
                                            <br />
                                            <a href="{{ route('admin.users.detail', $payment->to_user_id) }}"> @lang('@'){{ $payment->toUser->username }}</a>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $payment->trx }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ showAmount($payment->amount) }} {{ $payment->investment->currency->code }}</span>
                                        </td>
                                        <td>
                                            @php
                                                echo $payment->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.payment.detail', $payment->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="las la-desktop"></i> @lang('Details')
                                            </a>
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
                @if ($payments->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($payments) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Saerch by Trx" />
@endpush
