#!/bin/bash
php artisan down
php artisan clear-compiled
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
git pull --ff-only origin
php composer.phar install --no-interaction --no-dev --prefer-dist --optimize-autoloader
php artisan migrate
npm install
npm run prod
php artisan up
