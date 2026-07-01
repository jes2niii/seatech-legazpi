FROM php:8.2-apache

RUN a2dismod mpm_event mpm_worker mpm_prefork || true && a2enmod mpm_prefork

RUN apt-get update && apt-get install -y libgd-dev libexif-dev libzip-dev && docker-php-ext-install gd exif zip && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app
WORKDIR /app

RUN composer install --optimize-autoloader --no-scripts --no-interaction

CMD ["apache2-foreground"]