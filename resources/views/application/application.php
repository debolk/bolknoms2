<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8"/>
    <title>bolknoms</title>

    <?php if (isset($javascript)): ?>
        <script type="text/javascript" src="/javascripts/<?=$javascript?>.js"></script>
    <?php endif; ?>

    <link href='http://fonts.googleapis.com/css?family=Cardo:400,400italic,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/stylesheets/app.css">

    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
</head>
<body>
    <header>
        <h1>bolknoms</h1>
    </header>
    <nav>
        <a href="/">Aanmelden</a> |
        <a href="/top-eters">Top eters</a> |
        <a href="/disclaimer">Disclaimer</a> |
        <a href="/privacy">Privacy</a> |
        <a href="/administratie">Administratie</a>
    </nav>

    <section>
        <?php echo $content; ?>
    </section>

    <?php echo View::make('application/_google_analytics'); ?>
</body>
</html>
