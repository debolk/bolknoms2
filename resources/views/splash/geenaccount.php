<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8"/>
    <title>Bolknoms</title>

    <link href='http://fonts.googleapis.com/css?family=Cardo:400,400italic,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/stylesheets/app.css">

    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
</head>
<body>
    <header>
        <h1>Bolknoms</h1>
    </header>
    <nav>
    <ul>&nbsp;</ul>
    </nav>

    <section>
        <h1>Aanmelden voor de maaltijd zonder Bolkaccount</h1>
        <ol>
            <li>De eettafel is open voor iedereen. Leden kunnen zich online aanmelden met hun Bolkaccount, niet-leden moeten even bellen met het bestuur.</li>
            <li>Je kunt je aanmelden tot de vastgestelde sluitingstijd (meestal 15:00 uur). Je kunt je ook weer afmelden tot de sluitingstijd.</li>
            <li>Als je je niet op tijd afmeldt en niet komt opdagen, betaal je toch de kosten van de maaltijd (meestal &euro; 3,50).</li>
            <li>De maaltijd begint om 18:30 uur, kom op tijd!</li>
        </ol>

        <?php if ($meal->count() > 0 && $meal->isToday()): ?>
            <?php if ($meal->open_for_registrations()): ?>
                <p class="notification success">
                    Je kunt je nog aanmelden voor de maaltijd van vandaag (<?= $meal?>) tot <?= $meal->locked; ?> uur. Bel het bestuur op <a href="tel:+31152126012">015 212 60 12</a> om je aan te melden.
                </p>
            <?php else: ?>
                <p class="notification error">
                    De sluitingstijd voor vandaag was <?= $meal->locked; ?> uur. Je kunt je helaas niet meer aanmelden.
                </p>
            <?php endif; ?>
        <?php else: ?>
            <p class="notification warning">
                Vandaag is er geen maaltijd.
            </p>
        <?php endif; ?>

        <h3>Maar ik ben wel lid!</h3>
        <p>
            Dan heb je een Bolkaccount; het is technisch onmogelijk om in de ledenadministratie te staan zonder een Bolk-account. <a href="https://www.thuisbezorgd.nl">Eigenlijk heb ik wel een Bolkaccount maar ik ben te schraal om te willen inloggen</a>.
        </p>

    </section>

    <?php echo View::make('application/_google_analytics'); ?>
</body>
</html>
