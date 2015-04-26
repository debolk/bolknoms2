<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8"/>
    <title>Bolknoms</title>

    <link rel="stylesheet" href="/stylesheets/app.css" type="text/css"/>
    <link href='http://fonts.googleapis.com/css?family=Cardo:400,400italic,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="stylesheets/app.css">

    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
</head>
<body>
    <header>
        <h1>Bolknoms</h1>
    </header>

    <section>
        <div class="content">
            <h1>Foutmelding</h1>
            <p>
                Er is iets fout gegaan. Onze excuses voor het ongemak. Je kunt het nog een keer proberen.
            </p>
            <p>
                <?php if ($reported_automatically): ?>
                    Het probleem is automatisch gemeld bij de ICTcom. Als je dat wilt, kun je zelf contact opnemen via <a href="mailto:ictcom@nieuwedelft.nl">ictcom@nieuwedelft.nl</a>.
                <?php else: ?>
                    Omdat dit soort problemen meestal aan een fout van de gebruiker liggen (en zo'n 10x per dag gebeuren) is het probleem <strong>niet</strong> automatisch gemeld bij de ICTcom.
                    Ligt het echt niet aan jou? Neem dan zelf contact opnemen via <a href="mailto:ictcom@nieuwedelft.nl">ictcom@nieuwedelft.nl</a>, dan helpen we je graag.
                <?php endif; ?>
            </p>
            <p class="error_message">
                <?php echo $code; ?>
            </p>
        </div>
    </section>

    <footer>
        <nav>
            <a href="/">Aanmelden</a> |
            <a href="/administratie">Administratie</a> |
            <a href="/disclaimer">Disclaimer</a> |
            <a href="/privacy">Privacy</a> |
            <a href="/top-eters">Top eters</a> |
        </nav>
    </footer>

    <?php echo View::make('application/_google_analytics'); ?>
</body>
</html>
