<div class="meal">
    <?php if (isset($user) && $user->registeredFor($meal)): ?>
        <button class="registered" data-id="<?= $meal->id ;?>">nom! &#10004;</button>
    <?php else: ?>
        <button class="unregistered" data-id="<?= $meal->id ;?>">nom!</button>
    <?php endif; ?>

    <h3><?= $meal; ?></h3>

    <div class="details">
        <span class="count"><?= $meal->registrations->count(); ?></span> eters <br>
        Kosten &euro;3,50
        Aanmelden tot <?= strftime("%H:%M", strtotime($meal->locked)); ?> uur
    </div>
</div>
