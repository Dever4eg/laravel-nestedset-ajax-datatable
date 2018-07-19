# Тестовое задание

## Проект сделан с помощью laravel + vue

## Демо 
http://abz-agency-test-dever4eg.herokuapp.com


## Установка:

git clone https://dever4eg@bitbucket.org/dever4eg/abz.agencytest.git

composer install

yarn install || npm install

cp .env.example .env

Настроить url

APP_URL=http://127.0.0.1:8000

Настроить подключение базы данных

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

php artisan key:generate
php artisan storage:link

php artisan migrate

php artisan serve

в папку storage/app/public/avatars 
и storage/app/public/avatars/thumbnails 
нужно поместить файл default.jpg 
