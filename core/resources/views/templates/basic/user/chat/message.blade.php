<span class="message-count" data-message_count="{{ @$messageCount ?? 1 }}"></span>
@foreach ($messages->reverse() as $message)
    @if ($message->user_id == auth()->user()->id)
        <div class="media media-chat media-chat-reverse">
            <div class="media-body">
                <div class="message-text">
                    <p>{{ $message->message }}</p>
                    @if ($message->file)
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('user.attachment.download', encrypt(getFilePath('conversation') . '/' . $message->file)) }}" class="text--base mt-2"><i class="las la-file"></i> @lang('Attachment')</a>
                        </div>
                    @endif
                </div>
                <div class="message-time text-end">{{ diffForHumans($message->created_at) }}</div>
            </div>
        </div>
    @elseif($message->user_id == 0)
        <div class="media media-chat media-chat-left media-chat-admin">
            <div class="media-body d-flex gap-2">
                <div>
                    <span class="admin-reply">@lang('Admin')</span>
                    <div class="message-text">
                        <p>{{ $message->message }}</p>
                        @if ($message->file)
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('user.attachment.download', encrypt(getFilePath('conversation') . '/' . $message->file)) }}" class="text--base mt-2"><i class="las la-file"></i> @lang('Attachment')</a>
                            </div>
                        @endif
                    </div>
                    <div class="message-time">{{ diffForHumans($message->created_at) }}</div>
                </div>
            </div>
        </div>
    @else
        <div class="media media-chat media-chat-left">
            <div class="media-body d-flex gap-2">
                <div class="client-name">{{ getInitials($message->user->fullname) }}</div>
                <div>
                    <div class="message-text">
                        <p>{{ $message->message }}</p>
                        @if ($message->file)
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('user.attachment.download', encrypt(getFilePath('conversation') . '/' . $message->file)) }}" class="text--base mt-2"><i class="las la-file"></i> @lang('Attachment')</a>
                            </div>
                        @endif
                    </div>
                    <div class="message-time">{{ diffForHumans($message->created_at) }}</div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@push('style')
    <style>
        .admin-reply {
            background-color: #282828;
            padding: 0px 10px;
            border-radius: 3px;
            height: fit-content;
            color: #fff;
            font-weight: 700;
        }
    </style>
@endpush
