<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8"/>
    <title>Bolknoms</title>

    <link href='//fonts.googleapis.com/css?family=Cardo:400,400italic,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/stylesheets/app.css">

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png?v=wA2NloRwlG">
    <link rel="icon" type="image/png" href="/favicon-32x32.png?v=wA2NloRwlG" sizes="32x32">
    <link rel="icon" type="image/png" href="/android-chrome-192x192.png?v=wA2NloRwlG" sizes="192x192">
    <link rel="icon" type="image/png" href="/favicon-96x96.png?v=wA2NloRwlG" sizes="96x96">
    <link rel="icon" type="image/png" href="/favicon-16x16.png?v=wA2NloRwlG" sizes="16x16">
    <link rel="manifest" href="/manifest.json?v=wA2NloRwlG">
    <link rel="shortcut icon" href="/favicon.ico?v=wA2NloRwlG">
    <meta name="apple-mobile-web-app-title" content="Bolknoms">
    <meta name="application-name" content="Bolknoms">
    <meta name="msapplication-TileColor" content="#b91d47">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png?v=wA2NloRwlG">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
    <header>
        <h1>Bolknoms</h1>
    </header>
    <nav>
        <?= \App\Http\Helpers\Navigation::show(); ?>
    </nav>

    <section>
        <div class="content">
            <h1>Foutmelding</h1>
            <p>
                Er is een fatale fout opgetreden. De pagina kon niet worden gevonden, of de actie die je probeerde is niet uitgevoerd. De foutmelding wordt, indien bekend, hieronder toegelicht:
            </p>
            <p class="error_message">
                <?php echo $code; ?>
            </p>
            <p>
                Als je ingelogd bent, helpt het soms om <a href="/logout">uit te loggen</a> en proberen je aan te melden zonder Bolkaccount. Heb je nog vragen? Je kunt altijd contact opnemen: <a href="mailto:<?=env('MAIL_ADMIN_MAIL');?>">mail de admin</a>.
            </p>
        </div>
    </section>

    <?php echo View::make('application/_google_analytics'); ?>
</body>
</html>
