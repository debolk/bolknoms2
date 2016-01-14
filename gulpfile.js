var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass([
        'libs/sweetalert.css',

        'administration.scss',
        'anonymous.scss',
        'form.scss',
        'meal.scss',
        'mobile.scss',
        'navigation.scss',
        'notifications.scss',
        'print.scss',
        'profile.scss',
        'structure.scss',
        'table.scss',
        'typography.scss',
        'user.scss',
        'top.scss',
    ]);

    mix.scripts([
        'libs/zepto.js',
        'libs/zepto.fx.js',
        'libs/zepto.fx_methods.js',
        'libs/sweetalert.min.js',
        'app.js',
        'menu.js',
        'administration.js',
        'frontend.js',
    ], 'public/js/app.js');

    mix.version(['css/app.css', 'js/app.js'])
});
