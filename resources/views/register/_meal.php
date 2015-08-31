<div class="meal">
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
            Eten om <?= strftime("%H:%M", strtotime($meal->mealtime)); ?> uur
        </span>
        <?php if (in_array($meal->date, ['2015-09-01', '2015-09-02', '2015-09-08', '2015-09-09'])): ?>
                <br>
                <span style="font-size: small; color: darkgreen;"><img src="images/leaf.png" width=16 height=16> Deze maaltijd is veganistisch.
                Door mee te eten help je mee aan een betere wereld!</span>
        <?php endif; ?>
        <br>

    </div>
</div>
