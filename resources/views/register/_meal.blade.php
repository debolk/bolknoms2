<div class="meal {{ $meal->open_for_registrations() ? '' : 'deadline_passed' }}">
    @if (isset($user) && $user->registeredFor($meal))
        <button class="registered" data-id="<?= $meal->id ;?>">&#10004; Je eet mee</button>
    @else
        <button class="unregistered" data-id="<?= $meal->id ;?>">Aanmelden</button>
    @endif

    <div class="registrations">
        @foreach ($meal->registrations as $registration)
            @if ($registration->username)
                <img src="{{ action('ProfilePicture@photoFor', $registration->username) }}"
                     class="{{ ($user && $registration->user->id === $user->id) ? 'me' : '' }}"
                     title="{{ $user ? $registration->name : null }}">
            @endif
        @endforeach
    </div>

    <h3>{{ $meal }}</h3>

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
    </div>
</div>
