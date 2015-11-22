<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8"/>
    <title>Bolknoms</title>

    <script type="text/javascript" src="/js/all.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=yes" />

    <link href='//fonts.googleapis.com/css?family=Cardo:400,400italic,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/all.css">

    <link rel=icon href="/images/favicon.png" sizes="196x196" type="image/png">
</head>
<body>
    <header>
        <h1>Bolknoms</h1>
    </header>

    <nav>
    <i class="fa fa-fw fa-2x fa-bars hamburger"></i>

        <?php if (isset($user)): ?>
            <?=view('user/_user', ['user' => $user]);?>
        <?php else: ?>
            <?=view('user/_no_user');?>
        <?php endif;?>

        <?=\App\Http\Helpers\Navigation::show();?>
    </nav>

    <section>
        <?php echo $content; ?>
    </section>

    <?php echo View::make('application/_google_analytics'); ?>
</body>
</html>
