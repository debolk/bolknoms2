<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8"/>
    <title>bolknoms</title>

    <!-- <link rel="stylesheet" href="/stylesheets/jquery-ui.css" type="text/css"/>
    <link rel="stylesheet" href="/stylesheets/application.css" type="text/css"/>
    <script type="text/javascript" src="/javascripts/jquery.js"></script>
    <script type="text/javascript" src="/javascripts/jquery-ui.js"></script>
    <script type="text/javascript" src="/javascripts/date-format.js"></script>
    <script type="text/javascript" src="/javascripts/application.js"></script> -->
    <script type="text/javascript" src="/javascripts/app.js"></script>

    <link href='http://fonts.googleapis.com/css?family=Cardo:400,400italic,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="stylesheets/app.css">

    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
</head>
<body>
    <header>
        <h1>bolknoms</h1>
    </header>

    <section>
        <?php echo $content; ?>
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