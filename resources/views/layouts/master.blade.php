<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel=icon href="/images/favicon.png" sizes="196x196" type="image/png">

    <link href='//fonts.googleapis.com/css?family=Cardo:400,400italic,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/app.css">

    <title>@yield('title') - Bolknoms</title>
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
        @yield('content')
    </section>

    <script type="text/javascript" src="/js/libs.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/frontend.js"></script>
    <script type="text/javascript" src="/js/administration.js"></script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-445432-25', 'auto');
        ga('send', 'pageview');
        ga('set', 'anonymizeIp', true);
        ga('set', 'forceSSL', true);
    </script>
</body>
</html>
