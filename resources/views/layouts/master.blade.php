<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel=icon href="/images/favicon.png" sizes="196x196" type="image/png">

    <link href='//fonts.googleapis.com/css?family=Cardo:400,400italic,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/app.css') }}">

    <title>@yield('title') - Bolknoms</title>
</head>
<body>
    <header>
        <h1>Bolknoms</h1>
    </header>

    <nav>
        <i class="fa fa-fw fa-2x fa-bars hamburger"></i>
        @if (isset($user))
            @include('user/_user', ['user' => $user])
        @else
            @include('user/_no_user')
        @endif

        <?=\App\Http\Helpers\Navigation::show();?>
    </nav>

    <section>
        @include('layouts/_result_notification')

        @yield('content')
    </section>

    <script type="text/javascript" src="{{ elixir('js/libs.js') }}"></script>
    <script type="text/javascript" src="{{ elixir('js/common.js') }}"></script>
    <script type="text/javascript" src="{{ elixir('js/frontend.js') }}"></script>
    <script type="text/javascript" src="{{ elixir('js/administration.js') }}"></script>
</body>
</html>
