#!/bin/bash
php artisan maintenance on
git fetch
git merge origin/master master
composer install
gulp --production
php artisan migrate
php artisan maintenance off
