#!/bin/bash
php artisan down
php artisan clear-compiled
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
git fetch
git merge origin/master master
php composer.phar install
gulp --production
php artisan migrate
php artisan up
