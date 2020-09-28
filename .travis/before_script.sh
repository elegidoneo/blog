#!/usr/bin/env bash

echo -e "Creating database...\n"
mysql -e 'CREATE DATABASE IF NOT EXISTS `services` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;'
cp .travis/.env .env
COMPOSER_MEMORY_LIMIT=-1 composer install
mkdir -p build/logs
mkdir -p build/reports/clover
php artisan key:generate
php artisan migrate --force
php artisan db:seed
