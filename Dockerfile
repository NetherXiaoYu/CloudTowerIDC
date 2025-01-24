FROM php:8.4-apache

LABEL author="CloudTowerDev"

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

RUN docker-php-ext-install zip pdo_mysql

# Update apache config setting the document root to the project's public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

COPY . /var/www/html

VOLUME [ "/var/www/html" ]
WORKDIR /var/www/html

# Install Composer & dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev

# Set Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy env and run artisan commands
COPY .env.example /var/www/html/.env
RUN php artisan key:generate
RUN php artisan config:cache