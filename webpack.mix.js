let mix = require('laravel-mix');

mix.js('resources/js/bootstrap.js', 'public/js/app.js').sourceMaps().version();
mix.sass('resources/sass/bootstrap.scss', 'public/css/app.css').sourceMaps().version();
