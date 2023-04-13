#!/bin/bash

cd /root/source_code/backend
cp .env.example .env
composer update
php artisan key:generate 
php artisan migrate:fresh
php artisan passport:install
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear
php artisan config:cache
#add admin users
php artisan db:seed UsersTableSeeder
php artisan devbro:SADUpdatePerms
php artisan db:seed CompanySeeder
php artisan db:seed StoresTableSeeder
php artisan db:seed WorkflowSeeder
php artisan db:seed ServiceAvailabilitiesTableSeeder

php-fpm

chmod -R 777 ./storage/logs

#npx create-react-app front-end-app
cd /root/source_code/frontend
cp .env.example .env

npm i -g playwright
yarn
npx playwright install
yarn start