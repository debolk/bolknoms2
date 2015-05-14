<h1>Aanmelden voor de maaltijd</h1>

<?php if (isset($user)): ?>
    <div class="user">
        <img src="http://www.jakobbuis.nl/img/avatar.jpg" alt="Foto van Jakob Buis" class="photo">
        <h3 class="name">Jakob Buis</h3>
        <a class="button" href="/logout">Uitloggen</a>
        <div class="details">
            <a href="#" class="handicap">Geen handicap</a> <br>
            <span class="count">37</span>x meegegeten (<a href="/top-eters">#1</a>)
        </div>
    </div>
<?php else: ?>
    <div class="user noone">
        <img src="images/swedishchef.jpg" alt="" class="photo">
        <h3 class="name">Niet ingelogd</h3>
        <a class="button" href="/login">Inloggen met je Bolk-account</a>
        <div class="details">
            <a href="/voordeel-account">Waarom zou ik me aanmelden met m'n Bolkaccount?</a>
        </div>
        <form action="#" class="data">
            <p>
                <label for="name">Voor- en achternaam</label>
                <input name="name" id="name">
            </p>
            <p>
                <label for="email">E-mail</label>
                <input name="email" id="email">
            </p>
            <p>
                <label for="handicap">Dieetwensen</label>
                <input name="handicap" id="handicap">
            </p>
        </form>
    </div>
<?php endif; ?>

<form action="#" id="register_form">
    <p>
        <label for="meals">Dagen om mee te eten:</label>
    </p>

    <?php if (count($meals) == 0): ?>
        <p class="empty">Er zijn geen maaltijden open waarvoor je je kunt aanmelden.</p>
    <?php endif; ?>
    <div class="meals">
        <?php foreach ($meals as $meal): ?>
            <div class="meal">
                <button data-id="<?= $meal->id ;?>">nom!</button>
                <span class="date"><?= $meal; ?></span>
                <?php if ($meal->locked != '15:00:00'): ?>
                    (aanmelden tot <?= $meal->deadline(); ?>)
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</form>

<div id=chef class="notification success">
    <p>Wat leuk dat je mee-eet. Een klein voorproefje van het menu:</p>
    <iframe width="560" height="315" src="" frameborder="0" allowfullscreen></iframe>
</div>
