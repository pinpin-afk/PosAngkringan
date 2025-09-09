# Stage 1: Build environment
FROM composer:2.7 as build

WORKDIR /app

# Copy composer files & install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Copy the rest of the application
COPY . .

# Install Node.js, npm, and build assets
RUN apt-get update && apt-get install -y nodejs npm
RUN npm install
RUN npm run build

# Stage 2: Production environment with Apache
FROM php:8.2-apache

WORKDIR /var/www/html

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application from build
COPY --from=build /app /var/www/html

# Copy custom Apache config for Laravel
COPY .render/apache.conf /etc/apache2/sites-available/000-default.conf

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
