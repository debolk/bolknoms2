<a href="{{ $url }}" class="{{ $current ? 'current' : '' }}">
    <i class="fa fa-fw fa-{{ $icon }}"></i>
    {{ $text }}
</a>

@if (isset($submenu))
    @foreach ($submenu as $entry)
        <a href="{{ $url }}{{ $entry['url'] }}" class=sub>
            <i class="fa fa-fw fa-{{ $entry['icon'] }}"></i>
            {{ $entry['text'] }}
        </a>
    @endforeach
@endif
