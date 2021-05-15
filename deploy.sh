#!/bin/bash
set -euo pipefail

function update_code {
    cd /srv/bolknoms2/
    php artisan down
    php artisan clear-compiled
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    git pull --ff-only origin
    composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader
    npm install
    npm run production
    php artisan config:cache
    php artisan route:cache
    php artisan up
    exit
}

ssh jakob@10.99.1.24 "$(typeset -f); update_code"
echo "Done"
