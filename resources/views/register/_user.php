<div class="user someone">
    <img src="/photo" alt="Foto van <?= $user->name; ?>" class="photo">
        <h3 class="name"><?= $user->name; ?></h3>

        <a class="button" href="/logout">Uitloggen</a>
        <div class="details">
            <?php if ($user->handicap): ?>
                <span id="handicap_text" data-handicap="<?= $user->handicap; ?>">Dieet: <?= $user->handicap; ?></span>
            <?php else: ?>
                <span id="handicap_text" data-handicap="">Geen dieetwensen</span>
            <?php endif; ?>
            (<a href="#" id="set_profile_handicap">instellen</a>)
            |
            <span class="count"><?= $user->numberOfRegistrationsThisYear(); ?></span>x meegegeten
            <?php $rank = $user->topEatersPositionThisYear(); ?>
            <?php if ($rank !== null): ?>
                (<a href="/top-eters">#<?= $rank; ?></a>)
            <?php endif; ?>
        </div>
</div>
