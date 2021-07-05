#!/bin/sh

composer update
php artisan migrate:refresh
php artisan db:seed
npm install
npm run watch