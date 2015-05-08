<h1>Aanmelden voor de maaltijd</h1>

<div class="notification success">
    Hoi <?= $user; ?>, leuk dat je weer mee-eet. <br>
    <small>Niet <?= $user; ?>? <a href="/logout">Uitloggen</a></small>
</div>

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

<h3>Spelregels</h3>
<ol>
    <li>Je kunt niet mee-eten zonder je aan te melden.</li>
    <li>Je kunt je aanmelden tot de vastgestelde sluitingstijd (meestal 15:00 uur). De sluitingstijd wordt bepaald door het bestuur.</li>
    <li>Je kunt je weer afmelden tot aan de sluitingstijd. Hiervoor moet je zijn aangemeld met je Bolkaccount.</li>
    <li>Als je niet op tijd afmeldt en niet komt opdagen, betaal je de kosten van de maaltijd (meestal &euro; 3,50) per Bolkrekening.</li>
</ol>

<h3>Overige vragen of informatie</h3>
<p>
    Kom je er niet uit, heb je nog vragen of wil er iets niet lukken? Neem dan contact op met het bestuur via 015 212 60 12 of <a href="mailto:bestuur@nieuwedelft.nl">bestuur@nieuwedelft.nl</a>.
</p>
