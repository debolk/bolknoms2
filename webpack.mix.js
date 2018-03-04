let mix = require('laravel-mix');

mix.js('resources/assets/js/bootstrap.js', 'public/js/app.js')
   .sass('resources/assets/sass/bootstrap.scss', 'public/css/app.css');
