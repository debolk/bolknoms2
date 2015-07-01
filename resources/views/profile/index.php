<h1>Mijn profiel</h1>

<div class="profile">
    <h2>Dieetwensen</h2>

    <?php if ($user->handicap): ?>
        <h3 id="handicap" data-handicap="<?= $user->handicap; ?>">&ldquo;<?= $user->handicap; ?>&rdquo;</h3>
    <?php else: ?>
        <h3 id="handicap" data-handicap="" class="no_diet">Geen dieet ingesteld</h3>
    <?php endif; ?>

    <button id="set_profile_handicap">
        Dieetwensen instellen
    </button>
</div>

<div class="profile">
    <h2>Ranking dit collegejaar</h2>

    <?php
        // Calculate colour of prize
        $rank = $user->topEatersPositionThisYear();
        $medal = '';
        if ($rank === null) {
            $medal = 'gray';
        }
        elseif ($rank <= 10) {
            $medal = 'gold';
        }
        elseif ($rank <= 20) {
            $medal = 'silver';
        }
    ?>
    <div class="medal <?= $medal; ?>">
        <?= file_get_contents(public_path() . "/images/topeters.svg"); ?>
    </div>

    <?php if ($rank !== null): ?>
        <h3>
            <?= $rank; ?>e plaats
            <br>
            <?= $user->numberOfRegistrationsThisYear(); ?>x meegegeten
        </h3>
    <?php else: ?>
        <h3>
            0x meegegeten
        </h3>
    <?php endif; ?>
</div>

<div class="profile">
    <h2>Maaltijden waar je hebt gegeten</h2>
    <ul>
        <?php foreach ($user->registrations()->join('meals', 'registrations.meal_id', '=', 'meals.id')->orderBy('date', 'desc')->get() as $registration): ?>
            <li><?= $registration->longDate(); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
