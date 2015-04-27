<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8"/>
    <title>Bolknoms</title>

    <?php if (isset($javascript)): ?>
        <script type="text/javascript" src="/javascripts/<?=$javascript?>.js"></script>
    <?php endif; ?>

    <link href='http://fonts.googleapis.com/css?family=Cardo:400,400italic,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/stylesheets/app.css">

    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
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
