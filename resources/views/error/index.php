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
        <a href="/">Aanmelden</a> |
        <a href="/top-eters">Top eters</a> |
        <a href="/disclaimer">Disclaimer</a> |
        <a href="/privacy">Privacy</a> |
        <a href="/administratie">Administratie</a>
    </nav>

    <section>
        <div class="content">
            <p class="error_message">
                <strong>Foutmelding:</strong>
                <?php echo $code; ?>
            </p>
            <p>
                Vragen? <a href="mailto:<?=env('MAIL_ADMIN_MAIL');?>">Mail de admin</a>.
            </p>
        </div>
    </section>

    <?php echo View::make('application/_google_analytics'); ?>
</body>
</html>
