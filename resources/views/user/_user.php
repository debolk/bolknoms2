<div class="user someone">
    <img src="/photo" alt="Foto van <?= $user->name; ?>" class="photo">
    <h3 class="name"><?= $user->name; ?></h3>

    <a href="/logout">
        <?= file_get_contents(public_path() . "/images/logout.svg"); ?>
        Uitloggen
    </a>
</div>
