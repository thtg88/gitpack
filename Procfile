web: vendor/bin/heroku-php-apache2 public/
worker: php artisan queue:restart && php artisan queue:work --tries=3 --timeout=20
release: chmod -R 777 storage bootstrap/cache && php artisan migrate --force --seed
