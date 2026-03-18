FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs    

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

COPY .env.docker .env

RUN npm install
RUN npm run build

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000

CMD ["sh", "-c", "php artisan config:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]