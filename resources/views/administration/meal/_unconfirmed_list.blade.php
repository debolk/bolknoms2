<?php
    // Show a list of unconfirmed registrations, if any
    $unconfirmed = $meal->registrations()->unconfirmed()->get();
?>

@if (count($unconfirmed) > 0)
    <div class="non_print">
        <h2>Niet-bevestigde aanmeldingen</h2>
        <div class="notification warning">
            <p>
                Er zijn niet bevestigde aanmeldingen voor deze maaltijd. Voor deze aanmeldingen mag niet alsnog gekookt worden, omdat er volgens de <a href="/spelregels">spelregels</a> geen kosten gefactureerd kunnen worden.
            </p>
            <ul>
                <?php foreach ($unconfirmed as $registration): ?>
                    <li>
                        {{ $registration->name }}
                        <a href="mailto:{{ $registration->email }}">{{ $registration->email }}</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
@endif
