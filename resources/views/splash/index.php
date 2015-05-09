<!doctype html>
<html lang="nl">
    <head>
        <meta charset="utf-8"/>
        <title>Bolknoms</title>

        <link href='http://fonts.googleapis.com/css?family=Cardo:400,400italic,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="stylesheets/splash.css">

        <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />

        <?php echo View::make('application/_google_analytics'); ?>
    </head>

    <body>
        <section>
            <img src="images/logo.png" class="logo  " alt="Logo Bolknoms">
            <h1>Bolknoms</h1>
            <a class="login" href="/aanmelden">Login met je Bolk-account</a>
            <p>
                <a href="geenaccount">Ik heb geen Bolk-account</a>
            </p>
        </section>
        <footer>
            Tom &hearts; Bob
        </footer>
    </body>
</html>
