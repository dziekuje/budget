#!/bin/bash

if [ ! -z $BUDGET_SETUP ]; then
  composer install
  cp .env.docker .env
  php artisan key:generate
  php artisan storage:link
  yarn production
fi

# Move these to Supervisor?
service nginx start
php-fpm
