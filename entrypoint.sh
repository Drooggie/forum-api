#!/bin/bash

# Enable verbose mode to show all commands being executed
set -x

chown -R www-data:www-data /var/www/storage


if [ ! -d "vendor" ]; then
    composer install --no-interaction --prefer-dist
fi

if [ ! -f ".env" ]; then
    cp .env.example .env
fi

php artisan key:generate

if  php artisan migrate:status | grep -q "No migrations found."; then
    php artisan migrate:fresh --seed --force
fi

exec php-fpm