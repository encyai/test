<div class="media media-chat media-chat-reverse">
    <div class="media-body">
        <div class="message-text">
            <p>{{ $chat->message }}</p>
            @if ($chat->file)
                <div class="d-flex justify-content-end">
                    <a href="{{ route('user.attachment.download', encrypt(getFilePath('conversation') . '/' . $chat->file)) }}"
                        class="text--base mt-2"><i class="las la-file"></i> @lang('Attachment')</a>
                </div>
            @endif
        </div>
        <div class="message-time text-end">{{ diffForHumans($chat->created_at) }}</div>
    </div>
</div>
