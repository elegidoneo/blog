#!/usr/bin/env bash

if [[ -v DATABASE_NAME ]];
then
    echo -e "Creating database...\n"
    mysql -e 'CREATE DATABASE IF NOT EXISTS `'${DATABASE_NAME}'` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;'
fi
if [[ -v GITHUB_TOKEN ]];
then
    echo -e "Add github token...\n"
    composer config github-oauth.github.com ${GITHUB_TOKEN}
fi
cp .travis/.env .env
COMPOSER_MEMORY_LIMIT=-1 composer install
mkdir -p build/logs
mkdir -p build/reports/clover
php artisan key:generate
php artisan migrate --force
php artisan db:seed
