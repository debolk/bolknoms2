<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>bolknoms</title>

        <link rel="stylesheet" href="/stylesheets/jquery-ui.css" type="text/css"/>
        <link rel="stylesheet" href="/stylesheets/application.css" type="text/css"/>
        <link rel="stylesheet" href="/stylesheets/april.css" type="text/css"/>

        <script type="text/javascript" src="/javascripts/jquery.js"></script>
        <script type="text/javascript" src="/javascripts/jquery-ui.js"></script>
        <script type="text/javascript" src="/javascripts/date-format.js"></script>
        <script type="text/javascript" src="/javascripts/application.js"></script>

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    </head>
    <body class="<?php echo RequestHelper::url_classes(); ?>">
        <div id="container" class="clearfix">
            <div class="content clearfix">
                <?php echo $content; ?>
            </div>
        </div>
        <div id="sidebar">
            <?php echo $promoted_meals; ?>
            <?php echo $top_eaters; ?>
        </div>
        <?php echo View::make('application/_google_analytics'); ?>
    </body>
</html>
