<h1>Aanmelden voor de maaltijd zonder Bolkaccount</h1>

<?php if ($meal->count() > 0 && $meal->isToday()): ?>
    <?php if ($meal->open_for_registrations()): ?>
        <p class="notification success">
            Je kunt je nog aanmelden voor de maaltijd van vandaag (<?= $meal?>) tot <?= $meal->locked; ?> uur. Bel het bestuur op <a href="tel:+31152126012">015 212 60 12</a> om je aan te melden.
        </p>
    <?php else: ?>
        <p class="notification error">
            De sluitingstijd voor vandaag was <?= $meal->locked; ?> uur. Je kunt je wel aanmelden voor andere dagen door te bellen met het bestuur op <a href="tel:+31152126012">015 212 60 12</a>.
        </p>
    <?php endif; ?>
<?php else: ?>
    <p class="notification warning">
        Vandaag is er geen maaltijd. Je kunt je wel aanmelden voor andere dagen door te bellen met het bestuur op <a href="tel:+31152126012">015 212 60 12</a>.
    </p>
<?php endif; ?>

<h2>Spelregels</h2>
<p>
    De eettafel is open voor iedereen. Als niet-lid kun je je aanmelden door te bellen met het bestuur: <a href="tel:+31152126012">015 212 60 12</a>. Je kunt je aanmelden tot de vastgestelde sluitingstijd (meestal 15:00 uur). Als de maaltijd van vandaag nog open is, dan staat dat hieronder vermeld. Je kunt je ook weer afmelden tot de sluitingstijd door nog een keer te bellen. Als je je niet op tijd afmeldt en niet komt opdagen, betaal je toch de kosten van de maaltijd (meestal &euro; 3,50). De maaltijd begint om 18:30 uur, kom op tijd!
</ol>

<h2>Maar ik ben wel lid!</h2>
<p>
    Dan heb je een Bolkaccount; het is technisch onmogelijk om in de ledenadministratie te staan zonder een Bolkaccount. <br> <a href="https://www.thuisbezorgd.nl">Eigenlijk heb ik wel een Bolkaccount maar ik ben te schraal even in te loggen</a>.
</p>