#!/bin/sh

cd /var/www || exit

cp .env.example .env

# todo : change the composer command
php artisan cache:clear
php artisan route:cache
php artisan migrate

/usr/bin/supervisord -c /etc/supervisord.conf
