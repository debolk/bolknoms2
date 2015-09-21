<div class="meal <?= $meal->open_for_registrations() ? '' : 'deadline_passed'; ?>">
    <?php if (isset($user) && $user->registeredFor($meal)): ?>
        <button class="registered" data-id="<?= $meal->id ;?>">&#10004;</button>
    <?php else: ?>
        <button class="unregistered" data-id="<?= $meal->id ;?>">nom!</button>
    <?php endif; ?>

    <h3><?= $meal; ?></h3>

    <div class="details">
        <span class="<?= !$meal->normalDeadline() ? 'attention' : ''; ?>">
            Aanmelden tot <?= $meal->deadline(); ?>
        </span>
        <br>
        <span class="<?= !$meal->normalMealTime() ? 'attention' : ''; ?>">
            Eten om <?= strftime("%H:%M", strtotime($meal->meal_timestamp)); ?> uur
        </span>
    </div>
</div>
