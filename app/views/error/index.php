<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>bolknoms</title>

        <link rel="stylesheet" href="/stylesheets/jquery-ui.css" type="text/css"/>
        <link rel="stylesheet" href="/stylesheets/application.css" type="text/css"/>

        <script type="text/javascript" src="/javascripts/jquery.js"></script>
        <script type="text/javascript" src="/javascripts/jquery-ui.js"></script>
        <script type="text/javascript" src="/javascripts/date-format.js"></script>
        <script type="text/javascript" src="/javascripts/application.js"></script>

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    </head>
    <body class="error">
        <div id="container" class="clearfix">
            <div class="content clearfix">
                <h1>Foutmelding</h1>
                <p>
                    Er is iets fout gegaan. Onze excuses voor het ongemak. Je kunt het nog een keer proberen. <strong>Het probleem is automatisch gemeld bij de ICTcom.</strong> Als je dat wilt, kun je zelf contact opnemen via <a href="mailto:ictcom@nieuwedelft.nl">ictcom@nieuwedelft.nl</a>.
                </p>
                <p class="error_message">
                    <?php echo $code; ?> - <?php echo $message; ?>
                </p>
            </div>
        </div>
        <?php echo View::make('layouts/_google_analytics'); ?>
    </body>
</html>