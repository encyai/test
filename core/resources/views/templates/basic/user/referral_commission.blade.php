@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center gy-4">
        @foreach ($user->referralCommissionWallet->where('amount', '!=', 0) as $wallet)
            <div class="col-lg-4 col-md-6">
                <div class="card custom--card">
                    <div class="card-header">
                        <h4 class="mb-0 text-white text-center">@lang('Current Referral Bonus in ') {{ __($wallet->currency->name) }}</h4>
                    </div>
                    <div class="card-body p-3">
                        <ul class="withdraw-list-wrapper">
                            <li class="withdraw-list">
                                <h4>@lang('Amount')</h4>
                                <h6 class="float-right">{{ getAmount($wallet->amount) }} {{ __($wallet->currency->code) }}</h6>
                            </li>
                        </ul>
                        @if ($wallet->amount > 0)
                            <button type="submit" class="submit-btn w-100 referralCommission" data-id="{{ $wallet->id }}" data-name="{{ $wallet->currency->name }}" data-code="{{ $wallet->currency->code }}" data-bs-toggle="modal" data-bs-target="#withdrawModal"><i class="fa fa-paper-plane"></i> @lang('Withdraw Now')</button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-xl-12">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>@lang('Date')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Commission Via')</th>
                        <th>@lang('Description')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commissionLogs as $data)
                        <tr>
                            <td>{{ showDateTime($data->created_at) }}</td>
                            <td> {{ getAmount($data->amount) }} {{ $data->currency->code }}</td>
                            <td>
                                {{ $data->byWho->fullname }}
                            </td>
                            <td>
                                {{ __($data->description) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center data-not-found">{{ __($emptyMessage) }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $commissionLogs->links() }}
        </div>
    </div>

    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">@lang('Withdraw Amount With ') <span class="currencyname"></span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('user.referral.withdraw.commissions') }}">
                    @csrf
                    <input type="hidden" name="id" class="walletCommission">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="amount" placeholder="@lang('Enter Amount')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <span class="input-group-text currencytext" id="basic-addon2"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--base">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.referralCommission').on('click', function() {
                var id = $(this).data('id');
                var code = $(this).data('code');
                var name = $(this).data('name');
                console.log(name);
                $('.currencyname').text(name);
                $('.currencytext').text(code);
                $('.walletCommission').val(id);
            });
        })(jQuery)
    </script>
@endpush
