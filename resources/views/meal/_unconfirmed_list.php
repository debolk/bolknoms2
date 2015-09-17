<?php
    // Show a list of unconfirmed registrations, if any
    $unconfirmed = $meal->registrations()->unconfirmed()->get();
    if (count($unconfirmed) > 0): ?>
    <div class="non_print">
        <h2>Niet-bevestigde aanmeldingen</h2>
        <div class="notification warning">
            <p>
                Er zijn niet bevestigde aanmeldingen voor deze maaltijd. Voor deze aanmeldingen mag niet alsnog gekookt worden, omdat er volgens de <a href="/spelregels">spelregels</a> geen kosten gefactureerd kunnen worden.
            </p>
            <ul>
                <?php foreach ($unconfirmed as $registration): ?>
                    <li><?= $registration->name; ?> (<?= strftime('%R', strtotime($registration->created_at)); ?> uur)</li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
