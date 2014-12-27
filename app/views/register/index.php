<form action="#" id="form_name">
    <p>
        <a class="button-bolk" href="https://auth.debolk.nl/">
            <img src="images/bolkvis-wit.png" width="32" height="32" alt="Logo De Bolk">
            Aanmelden met je Bolk-account
        </a>
        of <a href="#" id="signin_no_account">aanmelden zonder Bolk-account</a>
    </p>

    <div class="submission_form">
        <p>
            <label for="name">Je naam:</label><br>
            <input type="text" name="name" id="name"><br>
            <span class="error_explanation">Je naam is nodig om je aan te melden</span>
        </p>

        <p>
            <input type="checkbox" name="handicap_checkbox" id="handicap_checkbox">
            <label for="handicap_text">Ik volg een speciaal dieet</label><br>
            <input type="text" name="handicap_text" id="handicap_text">
        </p>

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
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</form>

<h3>Spelregels</h3>
<ol>
    <li>Je kunt niet mee-eten zonder je aan te melden.</li>
    <li>Je kunt je aanmelden tot de vastgestelde sluitingstijd. De sluitingstijd wordt bepaald door het bestuur.</li>
    <li>Je kunt je weer afmelden tot aan de sluitingstijd. Hiervoor moet je zijn aangemeld met je Bolkaccount.</li>
    <li>Als je niet op tijd afmeldt en niet komt opdagen, betaal je de kosten van de maaltijd (meestal &euro; 3,50) per Bolkrekening.</li>
</ol>

<h3>Overige vragen of informatie</h3>
<p>
    Kom je er niet uit, heb je nog vragen of wil er iets niet lukken? Neem dan contact op met het bestuur via 015 212 60 12 of <a href="mailto:bestuur@nieuwedelft.nl">bestuur@nieuwedelft.nl</a>.
</p>
