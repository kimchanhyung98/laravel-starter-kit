#!/bin/sh

cd /var/www || exit

php artisan cache:clear
php artisan route:cache

cp .env.example .env

php artisan migrate

/usr/bin/supervisord -c /etc/supervisord.conf