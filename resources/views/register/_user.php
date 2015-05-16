<div class="user someone">
    <img src="/photo" alt="Foto van Jakob Buis" class="photo">
        <h3 class="name"><?= $user->name; ?></h3>

        <a class="button" href="/logout">Uitloggen</a>
        <div class="details">
            Geen dieetwensen |
            <span class="count"><?= $user->numberOfRegistrationsThisYear(); ?></span>x meegegeten
            <?php if ($rank = $user->topEatersPositionThisYear() !== null): ?>
                (<a href="/top-eters">#<?= $rank; ?></a>)
            <?php endif; ?>
            | <a href="/profiel">Mijn profiel</a>
        </div>
</div>
