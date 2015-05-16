<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8"/>
    <title>Bolknoms</title>

    <script type="text/javascript" src="/javascripts/zepto.js"></script>
    <?php if (isset($javascript)): ?>
        <script type="text/javascript" src="/javascripts/<?=$javascript?>.js"></script>
    <?php endif; ?>

    <link href='http://fonts.googleapis.com/css?family=Cardo:400,400italic,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/stylesheets/app.css">

    <link rel="apple-touch-icon" sizes="57x57" href="/images/favicon/apple-touch-icon-57x57.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/apple-touch-icon-60x60.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-touch-icon-72x72.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/apple-touch-icon-76x76.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-touch-icon-114x114.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/apple-touch-icon-120x120.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/apple-touch-icon-144x144.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/apple-touch-icon-152x152.png?v=wA2NloRwlG">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon-180x180.png?v=wA2NloRwlG">
    <link rel="icon" type="image/png" href="/images/favicon/favicon-32x32.png?v=wA2NloRwlG" sizes="32x32">
    <link rel="icon" type="image/png" href="/images/favicon/android-chrome-192x192.png?v=wA2NloRwlG" sizes="192x192">
    <link rel="icon" type="image/png" href="/images/favicon/favicon-96x96.png?v=wA2NloRwlG" sizes="96x96">
    <link rel="icon" type="image/png" href="/images/favicon/favicon-16x16.png?v=wA2NloRwlG" sizes="16x16">
    <link rel="manifest" href="/images/favicon/manifest.json?v=wA2NloRwlG">
    <link rel="shortcut icon" href="/images/favicon/favicon.ico?v=wA2NloRwlG">
    <meta name="apple-mobile-web-app-title" content="Bolknoms">
    <meta name="application-name" content="Bolknoms">
    <meta name="msapplication-TileColor" content="#b91d47">
    <meta name="msapplication-TileImage" content="/images/favicon/mstile-144x144.png?v=wA2NloRwlG">
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
        <?php echo $content; ?>
    </section>

    <?php echo View::make('application/_google_analytics'); ?>
</body>
</html>
