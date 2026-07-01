FROM php:8.2-fpm

RUN apt-get update && apt-get install -y libgd-dev libexif-dev && docker-php-ext-install gd exif && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app
WORKDIR /app

RUN composer install --optimize-autoloader --no-scripts --no-interaction

CMD ["php-fpm"]