@extends($activeTemplate . 'layouts.master')
@section('content')

    @if (auth()->user()->activation != 2)
        <div class="row justify-content-center gy-4">
            @forelse($currencies as $currency)
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                    <div class="active-account-item section--bg text-center">
                        <h6 class="sub-title text-white">@lang('ACTIVATE WITH') {{ __(strtoupper($currency->name)) }}</h6>
                        <h2 class="title text-white">{{ showAmount($currency->activation_fees) }} {{ __($currency->code) }}</h2>
                        <div class="active-account-form mt-20">
                            <button type="button" class="btn--base w-100 currency" data-name="{{ ucwords($currency->name) }}" data-id="{{ $currency->id }}" data-bs-toggle="modal" data-bs-target="#exampleModal">@lang('PAY & ACTIVATE NOW')</button>
                        </div>
                    </div>
                </div>
            @empty
                <h3 class="text-center">{{ __($emptyMessage) }}</h3>
            @endforelse
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Activate Account With') <span class="currencyname"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('user.activate.request') }}" method="POST">
                        @csrf
                        <input type="hidden" name="currency_id" class="currencyid">
                        <div class="modal-body">
                            <p>@lang('Are you sure to activate your account')?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn--base">@lang('Yes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            <p class="lead text-center pb-0">@lang('Account activation is still pending, please wait.')</p>
        </div>
    @endif

@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.currency').on('click', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('.currencyname').text(name);
                $('.currencyid').val(id);
            });
        })(jQuery)
    </script>
@endpush
