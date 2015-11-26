<a href="{{ action($action) }}" class="{{ $current ? 'current' : '' }}">
    <i class="fa fa-fw fa-{{ $icon }}"></i>
    {{ $text }}
</a>

@if (isset($submenu))
    @foreach ($submenu as $entry)
        <a href="{{ action($entry['action']) }}" class="sub {{ $entry['current'] ? 'current' : '' }}">
            <i class="fa fa-fw fa-{{ $entry['icon'] }}"></i>
            {{ $entry['text'] }}
        </a>
    @endforeach
@endif
