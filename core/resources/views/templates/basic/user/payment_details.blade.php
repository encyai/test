@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center gy-4">
        <div class="col-xl-5 col-lg-6">
            <div class="chat-widget  get-height--fix">
                <div class="chat-widget-header">
                    <div class="left">
                        @if ($payUser)
                            <h4 class="title text-white mb-0">@lang('Reciver Details')</h4>
                        @else
                            <h4 class="title text-white mb-0">@lang('Sender Details')</h4>
                        @endif
                    </div>
                    <div class="right">
                        <span class="btn--base text-white border-0 bg--{{ paymentStatus($payment->status)['class'] }}">{{ paymentStatus($payment->status)['text'] }}</span>
                    </div>
                </div>
                <div class="chat-widget-body">
                    <ul class="chat-widget-list">
                        @if ($payUser)
                            <li><span>@lang('Name'):</span> {{ __($payUser->fullname) }}</li>
                            <li><span>@lang('Phone'):</span> {{ __($payUser->mobile) }}</li>
                        @else
                            <li><span>@lang('Name'):</span> {{ __($getUser->fullname) }}</li>
                            <li><span>@lang('Phone'):</span> {{ __($getUser->mobile) }}</li>
                        @endif
                        <li><span>@lang('Amount'):</span> {{ getAmount($payment->amount) }}
                            {{ __($payment->investment->currency->code) }}
                        </li>
                    </ul>

                    @php
                        $withdrawalInfo = App\Models\WithdrawalInfo::where('currency_id', $payment->investment->currency->id)
                            ->where('user_id', $payUser ? $payUser->id : $getUser->id)
                            ->first();
                    @endphp

                    @if (@$withdrawalInfo->info)
                        <h4 class="title text-center">@lang('Details of Payment')</h4>
                        <ul class="chat-widget-list">
                            @foreach ($withdrawalInfo->info as $k => $val)
                                <li><span>{{ __(inputTitle(@$val->name)) }}:</span>
                                    @if (!is_array(@$val->value))
                                        @if ($val->type == 'file')
                                            <a class="text--base" href="{{ route('user.attachment.download', encrypt(getFilePath('verify') . '/' . $val->value)) }}">@lang('Attahment')</a>
                                        @else
                                            {{ @$val->value }}
                                        @endif
                                    @else
                                        {{ implode(', ', @$val->value) }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="chat-widget-footer text-center">
                        <div class="payment-btn">
                            @if ($payUser)
                                @if ($payment->status == 0)
                                    @if ($payment->deadline > now())
                                        <h4 class="title">@lang('Please pay the amount in')</h4>
                                        <div class="time-tracker">
                                            <span class="counter" data-count="{{ showDateTime($payment->deadline, 'M d Y h:i A') }}"></span>
                                        </div>
                                    @endif
                                    <button type="button" class="btn--base mt-30 w-100" data-bs-toggle="modal" data-bs-target="#paymentpaid">@lang('I have paid')</button>
                                @elseif($payment->status == 2)
                                    <div class="payment-info-sender">
                                        <h3 class="title">@lang('Payment information')</h3>
                                        <a href="{{ route('user.payment.information.download', encrypt($payment->id . '|' . auth()->user()->id)) }}" class="btn--base w-100"><i class="las la-download"></i>
                                            @lang('Download')</a>
                                        <h6 class="mt-3 text-center">@lang('Details')</h6>
                                        <p>{{ $payment->info }}</p>
                                    </div>

                                    @if ($payment->confirmation_deadline > now())
                                        <h3 class="title">@lang('Confirm Before')</h3>
                                        <div class="time-tracker">
                                            <span class="counter" data-count="{{ showDateTime($payment->confirmation_deadline, 'M d Y h:i A') }}"></span>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn--danger w-100 text-white" data-bs-toggle="modal" data-bs-target="#reportedPayment">@lang('Report This Payment')</button>
                                    @endif
                                @elseif($payment->status != 0 || $payment->status != 1)
                                    <a href="{{ route('user.payment.information.download', encrypt($payment->id . '|' . auth()->user()->id)) }}" class="btn--base w-100"><i class="fa fa-download"></i>
                                        @lang('Download')</a>
                                    <h4 class="mt-2 text-center">@lang('Details')</h4>
                                    <p>{{ $payment->info }}</p>
                                @endif
                            @else
                                @if ($payment->status == 0)
                                    @if ($payment->deadline > now())
                                        <h4 class="title">{{ $getUser->fullname }} @lang('Will pay the amount in')</h4>
                                        <div class="time-tracker">
                                            <span class="counter" data-count="{{ showDateTime($payment->deadline, 'M d Y h:i A') }}"></span>
                                        </div>
                                    @else
                                        <button type="button" class="btn--base mt-30 w-100" data-bs-toggle="modal" data-bs-target="#paymentnotpaid">@lang('Did Not Paid Yet')</button>
                                    @endif
                                @elseif($payment->status == 2)
                                    <div class="payment-info-reciver">
                                        <h3 class="title">@lang('Payment information')</h3>
                                        <a href="{{ route('user.payment.information.download', encrypt($payment->id . '|' . auth()->user()->id)) }}" class="btn--base w-100"><i class="las la-download"></i>
                                            @lang('Download')</a>
                                        <h6 class="mt-3 text-center">@lang('Details')</h6>
                                        <p>{{ $payment->info }}</p>
                                    </div>
                                    <h3 class="title">@lang('Confirm Before')</h3>
                                    <div class="time-tracker">
                                        <span class="counter" data-count="{{ showDateTime($payment->confirmation_deadline, 'M d Y h:i A') }}"></span>
                                    </div>
                                    <div class="payment-footer-btn-area d-flex flex-wrap align-items-center justify-content-around mt-30">
                                        <button type="button" class="btn btn--base btn-sm bg--danger text-white border-0" data-bs-toggle="modal" data-bs-target="#reportedPayment">@lang('Report This Payment')</button>
                                        <button type="button" class="btn btn--base btn-sm" data-bs-toggle="modal" data-bs-target="#confirmPayment">@lang('Confirm This Payment')</button>
                                    </div>
                                @elseif($payment->status != 0 || $payment->status != 1)
                                    <a href="{{ route('user.payment.information.download', encrypt($payment->id . '|' . auth()->user()->id)) }}" class="btn--base w-100"><i class="fa fa-download"></i>
                                        @lang('Download')</a>
                                    <h4 class="mt-2 text-center">@lang('Details')</h4>
                                    <p>{{ $payment->info }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7 col-lg-6">
            <div class="chat-widget height--fix">
                <div class="chat-widget-header">
                    <div class="left">
                        <h4 class="title text-white mb-0">
                            {{ $payUser ? $payUser->fullname : $getUser->fullname }}
                        </h4>
                    </div>
                    <div class="right">
                        <button class="btn rounded text-white reload" type="button">
                            <i class="las la-sync"></i>
                        </button>
                    </div>
                </div>
                <div class="ps-container position-relative">
                    @if ($messages->count())
                        <div class="message-loader-wrapper">
                            <div class="message-loader mx-auto"></div>
                        </div>
                        <div id="message-area">
                            @include($activeTemplate . 'user.chat.message')
                        </div>
                    @endif
                </div>

                <div class="chat-widget-body p-0">
                    <form action="" method="POST" class="chat-form" enctype="multipart/form-data" id="chat-form">
                        @csrf
                        @if ($payment->status != 1 && $payment->status != 4 && $payment->status != 5)
                            <div class="publisher">
                                <div class="chatbox-message-part">
                                    <textarea class="publisher-input" type="text" name="message" placeholder="@lang('Write something')" autocomplete="off"></textarea>
                                </div>
                                <div class="d-flex justify-content-between message-bottom">
                                    <div class=" trade-chat-file-upload">
                                        <input type="file" id="file" name="file" class="custom-file" accept=".jpg , .png, ,jpeg .pdf">
                                    </div>
                                    <div class="chatbox-send-part">
                                        <button type="button" class="btn--base btn--md message-btn" id="messageBtn">@lang('Send')</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($payUser)
        @if ($payment->status == 0)
            <div class="modal fade custom--modal" id="paymentpaid" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">@lang('Payment Details')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('user.payment.proved', encrypt($payment->id . '|' . auth()->user()->id)) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="formFile" class="form-label">@lang('Payment proof Image')</label>
                                    <input class="form-control" name="image" type="file" id="formFile" required="">
                                    <small>@lang('Supported File : jpeg,jpg,png')</small>
                                </div>

                                <div class="form-group">
                                    <label for="information" class="form-label">@lang('Payment Information')</label>
                                    <textarea class="form-control" id="information" name="information" rows="3" required></textarea>
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
        @endif
    @else
        @if ($payment->status == 0)
            <div class="modal fade custom--modal" id="paymentnotpaid" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="staticBackdropLabel">@lang('Payment Rejected')</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('user.payment.not.paid', encrypt($payment->id . '|' . auth()->user()->id)) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                            <div class="modal-body">
                                <p>@lang('Are you sur to rejected this payment')?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                                <button type="submit" class="btn btn--base">@lang('Yes')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @elseif($payment->status == 2)
            <div class="modal fade custom--modal" id="confirmPayment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="staticBackdropLabel">@lang('Payment Confirmation')</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('user.payment.confirm', encrypt($payment->id . '|' . auth()->user()->id)) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <p>@lang('Are you sure to confirm this payment')?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                                <button type="submit" class="btn btn--base">@lang('Confirm')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <div class="modal fade custom--modal" id="reportedPayment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="staticBackdropLabel">@lang('Report Payment')?</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user.payment.report', $payment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <p>@lang('Are you sure want to report this payment')?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--base">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        var countDown = $('.counter').data('count');
        var countDownDate = new Date(countDown).getTime();
        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            var output =
                `<span>${days}d</span> <span>${hours}h</span> <span>${minutes}m</span> <span>${seconds}s</span>`;
            $(".counter").html(output);
            if (distance < 0) {
                clearInterval(x);
                $(".counter").html('EXPIRED');
            }
        }, 1000);

        $('.reload').on('click', function() {
            loadMore(10);
            scrollHeight();
        });

        $("#messageBtn").on('click', function(e) {
            let formData = new FormData($('#chat-form')[0]);
            $.ajax({
                url: "{{ route('user.send.message', $payment->id) }}",
                method: "POST",
                data: formData,
                async: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.error) {
                        notify('error', response.error);
                    } else {
                        $('#chat-form')[0].reset();
                        $('#message-area').append(response);
                        scrollHeight();
                    }
                }
            });
        });

        var messageCount = 10
        $(".ps-container").on('scroll', function() {
            var count = $("#message-area").find('.message-count').data('message_count');
            if (count != 0) {
                if ($(this).scrollTop() == 0) {
                    messageCount += 10;
                    loadMore(messageCount);
                    $('.ps-container').animate({
                        scrollTop: 100
                    });
                }
            }
        });

        function loadMore(messageCount) {
            $('.message-loader-wrapper').fadeIn(300)
            $.ajax({
                method: "GET",
                data: {
                    payment_id: `{{ @$payment->id }}`,
                    messageCount: messageCount
                },
                url: "{{ route('user.chat.messages') }}",
                success: function(response) {
                    $("#message-area").html(response);
                }
            }).done(function() {
                $('.message-loader-wrapper').fadeOut(500)
            });
        }

        $('.message-loader-wrapper').fadeOut(500);

        function heightFix() {
            var containerHeight = $('.get-height--fix').height();
            var footerHeight = $('.height--fix .chat-widget-header').height();
            var headerHeight = $('.height--fix .chat-form').height();
            scrollHeight();
            return containerHeight - footerHeight - headerHeight + 50
        }

        function scrollHeight() {
            $('.ps-container').animate({
                scrollTop: $('.ps-container')[0].scrollHeight
            });
        }

        heightFix();
        $('.height--fix .ps-container').css('max-height', heightFix)
        $(window).on('resize', heightFix)
    </script>
@endpush
