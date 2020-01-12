@inject('navigation', 'App\Http\Helpers\Navigation')

<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel=icon href="/images/favicon.png" sizes="196x196" type="image/png">

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">

    <title>@yield('title') - Bolknoms</title>
</head>
<body>
    <header>
        <h1>Bolknoms</h1>
    </header>

    <nav>
        <i class="fa fa-fw fa-2x fa-bars hamburger"></i>
        @if (isset($user) && $user !== null)
            @include('user/_user', ['user' => $user])
        @else
            @include('user/_no_user')
        @endif

        {!! $navigation->show() !!}
    </nav>

    <section>
        @include('layouts/_result_notification')

        @yield('content')
    </section>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
</body>
</html>
