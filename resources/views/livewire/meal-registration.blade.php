<div class="meal {{ $meal->open_for_registrations() ? '' : 'deadline_passed' }}">
    @if ($user && $user->registeredFor($meal))
        <button class="registered" data-id="<?= $meal->id ;?>">&#10004; Je eet mee</button>
    @else
        <button class="unregistered" data-id="<?= $meal->id ;?>">Aanmelden</button>
    @endif

    <div class="registrations">
        @foreach ($meal->registrations as $registration)
            @if ($registration->username)
                <img src="{{ route('photo.src', $registration->username) }}"
                     class="{{ ($user && $registration->user->id === $user->id) ? 'me' : '' }}"
                     title="{{ $user ? $registration->name : null }}">
            @endif
        @endforeach
    </div>

    <h3>{{ $meal->longDate() }}</h3>

    @if ($meal->event)
        <h4>{{ $meal->event }}</h4>
    @endif

    <div class="details">
        <span class="{{ !$meal->normalDeadline() ? 'attention' : '' }}">
            <i class="fa fa-fw fa-clock-o"></i>
            Aanmelden tot {{ $meal->deadline() }}
        </span>
        <br>
        <span class="{{ !$meal->normalMealTime() ? 'attention' : '' }}">
            <i class="fa fa-fw fa-cutlery"></i>
            Eten om {{ $meal->meal_timestamp->format('H:i') }} uur
        </span>
        <br>
        <span>
            <i class="fa fa-fw fa-list"></i>
            {{ $meal->registrations()->count() }}
            @if ($meal->capacity !== null)
                / {{ $meal->capacity }}
            @endif
            {{ ($meal->registrations()->count() === 1 && $meal->capacity === null) ? 'eter' : 'eters' }}
        </span>
    </div>
</div>
