<div class="user someone">
    <img src="/photo" alt="Foto van Jakob Buis" class="photo">
        <h3 class="name"><?= $user->name; ?></h3>

        <a class="button" href="/logout">Uitloggen</a>
        <div class="details">
            <?php if ($user->getHandicap()): ?>
                <span id="handicap_text">Dieet: <?= $user->getHandicap(); ?></span>
            <?php else: ?>
                <span id="handicap_text">Geen dieetwensen</span>
            <?php endif; ?>
            (<a href="#" id="set_profile_handicap">instellen</a>)
            |
            <span class="count"><?= $user->numberOfRegistrationsThisYear(); ?></span>x meegegeten
            <?php if ($rank = $user->topEatersPositionThisYear() !== null): ?>
                (<a href="/top-eters">#<?= $rank; ?></a>)
            <?php endif; ?>
        </div>
</div>
