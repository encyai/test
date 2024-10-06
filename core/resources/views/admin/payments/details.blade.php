@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center mt-4">
        <div class="col-xl-4">
            <div class="card  b-radius--5  mb-4">
                <div class="card-body">
                    <h6 class="d-flex justify-content-between mb-3">
                        @lang('Payment') #{{ $payment->trx }}
                        @php
                            echo $payment->statusBadge;
                        @endphp
                    </h6>
                    <ul class="list-group list-group">

                        @if ($payment->status == 3)
                            <li class="list-group-item text--danger fw-bold d-flex justify-content-between">
                                @lang('Reported by')
                                <a href="{{ route('admin.users.detail', $payment->from_user_id) }}" class="text--blue"> @lang('@'){{ $payment->fromUser->username }}</a>
                            </li>
                        @endif

                        @if ($payment->status == 4)
                            <li class="list-group-item text--danger fw-bold d-flex justify-content-between">
                                @lang('Reported by')
                                <a href="{{ route('admin.users.detail', $payment->to_user_id) }}" class="text--cyan"> @lang('@'){{ $payment->toUser->username }}</a>
                            </li>
                        @endif

                        <li class="list-group-item d-flex justify-content-center fw-bold">
                            @lang('Payment Info')
                        </li>

                        <li class="list-group-item d-flex justify-content-between ">
                            @lang('Amount')
                            <span class="fw-bold">{{ showAmount($payment->amount) }} {{ __($payment->investment->currency->code) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between ">
                            @lang('Merged At')
                            <span class="fw-bold">{{ $payment->created_at->diffForHumans() }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between ">
                            @lang('Sender')
                            <span class="fw-bold">
                                <a href="{{ route('admin.users.detail', $payment->from_user_id) }}" class="text--blue">@lang('@'){{ $payment->fromUser->username }}</a>
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between ">
                            @lang('Receiver')
                            <span class="fw-bold">
                                <a href="{{ route('admin.users.detail', $payment->to_user_id) }}" class="text--cyan"> @lang('@'){{ $payment->toUser->username }}</a>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card  b-radius--5  mb-4">
                <div class="card-body">
                    <ul class="list-group list-group mt-3">
                        <li class="list-group-item d-flex justify-content-center fw-bold">
                            @lang('Proof')
                        </li>

                        @if ($payment->status && $payment->info)
                            <li class="list-group-item">
                                <p>
                                    {{ $payment->info }}
                                </p>
                                <a href="{{ getImage(getFilePath('payment') . '/' . @$payment->image, getFileSize('payment')) }}" download="{{ $payment->image }}"> @lang('Attachment')</a>
                            </li>
                        @endif

                        @if (!$payment->status)
                            <li class="list-group-item d-flex justify-content-between">
                                @lang('Time Left')
                                <span class="fw-bold">
                                    @if ($payment->deadline > now())
                                        {{ $payment->deadline->diff(now())->format('%H:%I:%S') }}
                                    @else
                                        <span class="text-danger">@lang('Expired')</span>
                                    @endif
                                </span>
                            </li>
                        @elseif($payment->status == 2)
                            @if ($payment->confirmation_deadline > now())
                                <li class="list-group-item d-flex justify-content-between ">
                                    @lang('Confirmation Countdown')
                                    <span class="fw-bold">
                                        {{ $payment->confirmation_deadline->diff(now())->format('%H:%I:%S') }}
                                    </span>
                                </li>
                            @else
                                <li class="list-group-item d-flex justify-content-between ">
                                    @lang('Confirmation time')
                                    <span class="fw-bold text--danger">
                                        @lang('Expired')
                                    </span>
                                </li>
                            @endif
                        @elseif($payment->status == 5)
                            <li class="list-group-item text--danger fw-bold d-flex justify-content-end">
                                @lang('This payment is rejected.')
                            </li>
                        @elseif($payment->status == 1)
                            <li class="list-group-item text--success fw-bold d-flex justify-content-end">
                                @lang('Payment Completed')
                            </li>
                        @endif

                    </ul>
                </div>
            </div>

            @if ($payment->status == 3 || $payment->status == 4)
                <div class="card  b-radius--5  mb-4">
                    <div class="card-body">
                        <ul class="list-group list-group mt-3">

                            <li class="list-group-item d-flex justify-content-center fw-bold">
                                @lang('Take Action')
                            </li>

                            <li class="list-group-item text-center p-3 d-flex flex-wrap gap-3">

                                <button class="btn inFavourOfReceiver bg--cyan text-white flex-fill">
                                    @lang('In Favour of Receiver')
                                </button>

                                <button class="btn inFavourOfSender bg--blue text-white flex-fill">
                                    @lang('In Favour of Sender')
                                </button>

                                <button class="btn bg--warning text-white setBackAction flex-fill">
                                    @lang('Set Back to Initial Stage')
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-xl-8">
            <div class="col-12">
                <div class="card  b-radius--5  mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-center">@lang('Chat with ') {{ __($payment->toUser->username) }}</h5>
                        <div class="message-box payment-msg-container rounded">
                            @forelse ($payment->chats as $chat)
                                <div class="rounded payment-chat mb-4 single-message @if ($chat->user_id == 0) payment-chat-right @else payment-chat-left @endif">
                                    <div class="div">
                                        <h6 class="title">
                                            @if ($chat->user_id == 0)
                                                <span class="admin-user">@lang('Admin')</span>
                                            @else
                                                @if ($chat->user_id == $payment->from_user_id)
                                                    <span class="from-user">{{ __($chat->user->username) }}</span>
                                                @else
                                                    <span class="to-user">{{ __($chat->user->username) }}</span>
                                                @endif
                                            @endif
                                        </h6>
                                        <div class="chat-msg">
                                            {{ $chat->message }}
                                            @if ($chat->file)
                                                <div class="d-flex justify-content-end">
                                                    <a href="{{ route('admin.download.attachment', encrypt(getFilePath('conversation') . '/' . $chat->file)) }}" class="text--primary mt-2"><i class="las la-file"></i> @lang('Attachment')</a>
                                                </div>
                                            @endif
                                        </div>
                                        <p class="time-text">{{ $chat->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded mb-4 single-message">
                                    <span class="d-flex justify-content-center">
                                        {{ __($emptyMessage) }}
                                    </span>
                                </div>
                            @endforelse
                        </div>
                        @if ($payment->status == 3 || $payment->status == 4)
                        <form action="{{ route('admin.payment.send.message', $payment->id) }}" class="chat-from" method="POST" enctype="multipart/form-data">
                            @csrf
                            <textarea name="message" id="" class="form-control" placeholder="@lang('Say something...')"></textarea>
                            <div class="d-flex justify-content-between flex-wrap mt-4 message-bottom">
                                <div class=" trade-chat-file-upload">
                                    <input type="file" id="file" name="file" class="custom-file" accept=".jpg , .png, ,jpeg .pdf">
                                </div>
                                <div class="chatbox-send-part">
                                    <button type="submit" class="btn btn--primary btn-block message-btn"> <i class="fas fa-paper-plane"></i> @lang('Send')</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($payment->status == 3 || $payment->status == 4)
        <div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="actionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="actionModalLabel">@lang('Confirmation Alert!')</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.payment.action', $payment->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type">
                        <div class="modal-body">
                            <p class="text-muted actionText"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            let modal = $('#actionModal');

            $('.setBackAction').on('click', function() {
                modal.find('.actionText').text(`@lang('Are you sure to set back to initial stage?')`);
                modal.find('[name=type]').val('setBackToInitialStage');
                modal.modal('show');
            });

            $('.inFavourOfSender').on('click', function() {
                modal.find('.actionText').text(`@lang('Are you sure to take action in favour of receiver?')`);
                modal.find('[name=type]').val('inFavourOfSender');
                modal.modal('show');
            });

            $('.inFavourOfReceiver').on('click', function() {
                modal.find('.actionText').text(`@lang('Are you sure to take action in favour of sender?')`);
                modal.find('[name=type]').val('inFavourOfReceiver');
                modal.modal('show');
            });

            var chatWindow = $('.payment-msg-container');
            chatWindow.scrollTop(chatWindow[0].scrollHeight);

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .badge {
            line-height: 16px;
            height: 22px;
        }
    </style>
@endpush
