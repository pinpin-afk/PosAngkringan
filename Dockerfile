FROM composer:2.7 as build

WORKDIR /app

COPY . .
RUN cp .env.example .env

RUN composer install --no-dev --prefer-dist --optimize-autoloader

RUN apk add --no-cache nodejs npm
RUN npm install
RUN npm run build

FROM php:8.2-apache

# Tambahkan baris ini untuk install ekstensi MySQL!
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

RUN a2enmod rewrite

COPY --from=build /app /var/www/html
COPY .render/apache.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]