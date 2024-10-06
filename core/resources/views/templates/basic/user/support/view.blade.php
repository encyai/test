@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    @if ($layout == 'frontend')
        <div class="ptb-80">
            <div class="container">
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card custom--card mb-4">
                <div class="card-header card-header-bg d-flex flex-wrap justify-content-between align-items-center">
                    <h5 class="card-title mt-0">
                        @php echo $myTicket->statusBadge; @endphp
                        [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                    </h5>
                    @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                        <button class="btn btn-danger close-button btn-sm confirmationBtn" type="button" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}"><i class="la la-lg la-times-circle"></i>
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if ($myTicket->status != 4)
                        <form method="post" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="replayTicket" value="1">
                            <div class="row justify-content-between">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea name="message" class="form-control form--control form-control-lg" id="inputMessage" placeholder="@lang('Your Reply')" rows="4" cols="10" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-between">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="text-end">
                                            <button type="button" class="btn btn--base btn-sm addFile">
                                                <i class="la la-plus"></i> @lang('Add New')
                                            </button>
                                        </div>
                                        <label class="form-label d-inline">@lang('Attachments')</label> <small class="text-danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small>
                                        <input type="file" name="attachments[]" id="inputAttachments" class="form-control form--control mt-1" />
                                        <div id="fileUploadsContainer"></div>
                                        <p class="my-2 ticket-attachments-message text-muted">
                                            @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn--base w-100"><i class="la la-paper-plane"></i> @lang('Reply')</button>
                            </div>
                        </form>
                    @endif

                </div>

            </div>

            <div class="card custom--card">

                <div class="card-body p-0 pb-3">

                    @foreach ($messages as $message)
                        @if ($message->admin_id == 0)
                            <div class="border border--base border-radius-3 m-3 p-3 mb-0 rounded">
                                <div class="row">
                                    <div class="col-md-3 border-right text-right">
                                        <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                    </div>
                                    <div class="col-md-9">
                                        <p class="text-muted fw-bold my-3">
                                            @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                        <p>{{ $message->message }}</p>
                                        @if ($message->attachments->count() > 0)
                                            <div class="mt-2">
                                                @foreach ($message->attachments as $k => $image)
                                                    <a class="text--base" href="{{ route('ticket.download', encrypt($image->id)) }}" class="mr-3"><i class="la la-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="border border-warning border-radius-3 m-3 p-3 rounded" style="background-color: #ffd96729">
                                <div class="row">
                                    <div class="col-md-3 border-right text-right">
                                        <h5 class="my-3">{{ $message->admin->name }}</h5>
                                        <p class="lead text-muted">@lang('Staff')</p>
                                    </div>
                                    <div class="col-md-9">
                                        <p class="text-muted fw-bold my-3">
                                            @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                        <p>{{ $message->message }}</p>
                                        @if ($message->attachments->count() > 0)
                                            <div class="mt-2">
                                                @foreach ($message->attachments as $k => $image)
                                                    <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="mr-3"><i class="la la-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @if ($layout == 'frontend')
        </div>
        </div>
    @endif

    <div class="confirmation">
        <x-confirmation-modal />
    </div>

@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 4) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="input-group my-3">
                        <input type="file" name="attachments[]" class="form-control form--control" required />
                        <button type="button" class="input-group-text btn btn--danger remove-btn"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.input-group').remove();
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .confirmation .btn--primary{
            background-color: hsl(var(--base));
            border-color: hsl(var(--base));
        }
    </style>
@endpush