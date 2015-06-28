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
    <img src="images/topeters.svg" alt="">
    <h3>
        <span class="rank"><?= $user->topEatersPositionThisYear(); ?></span>e plaats
        <br>
        <span class="count"><?= $user->numberOfRegistrationsThisYear(); ?></span>x meegegeten
    </h3>
</div>
