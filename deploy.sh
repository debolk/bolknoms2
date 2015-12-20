#!/bin/bash
php artisan down
git fetch
git merge origin/master master
php composer.phar install
gulp --production
php artisan migrate
php artisan view:clear
php artisan up
