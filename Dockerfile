FROM composer:2.7 as build

WORKDIR /app

COPY composer.json composer.lock ./
# Tambahkan baris ini:
COPY .env.example .env

RUN composer install --no-dev --prefer-dist --optimize-autoloader

COPY . .

RUN apt-get update && apt-get install -y nodejs npm
RUN npm install
RUN npm run build

FROM php:8.2-apache

WORKDIR /var/www/html

RUN a2enmod rewrite

COPY --from=build /app /var/www/html
COPY .render/apache.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
