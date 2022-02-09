#!/bin/bash

if [ ! -f /var/www/html/.env ]
then
    cp /var/www/html/.env.example /var/www/html/.env
fi

php /var/www/html/artisan key:generate

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
