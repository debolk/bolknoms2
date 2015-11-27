@if (session('action_result'))
    <div class="notification {{ session('action_result.status') }}">
        {{ session('action_result.message')}}
    </div>
@endif
