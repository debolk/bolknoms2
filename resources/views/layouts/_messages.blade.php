<div class="flash_messages">
    @foreach ($messages as $message)
        <div class="notification {{ $message['type'] }}">
            {{ $message['message'] }}
        </div>
    @endforeach
</div>
