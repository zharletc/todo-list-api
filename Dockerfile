FROM php:8.2-fpm
WORKDIR /var/www
# Arguments defined in docker-compose.yml
# ARG user
# ARG uid

# Install system dependencies
RUN apt update && apt install -y
RUN apt update && apt install git -y
RUN apt update && apt install curl -y
RUN apt update && apt install libpng-dev -y
RUN apt update && apt install libonig-dev -y
RUN apt update && apt install libxml2-dev -y
RUN apt update && apt install zip -y
RUN apt update && apt install unzip -y
RUN apt update && apt install libzip-dev -y
RUN apt update && apt install vim -y
RUN apt update && apt install nano -y
RUN apt update && apt install libpq-dev -y

# Clear cache
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip pdo_pgsql

# COPY . .

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/azhar azhar
RUN mkdir -p /home/azhar/.composer && \
    chown -R azhar:azhar /home/azhar

RUN chown -R azhar:azhar /var/www && chmod -R 777 /var/www

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

RUN sed -i 's/^\(upload_max_filesize\s*=\s*\).*/upload_max_filesize = 50M/' /usr/local/etc/php/php.ini && \
    sed -i 's/^\(post_max_size\s*=\s*\).*/post_max_size = 50M/' /usr/local/etc/php/php.ini && \
    sed -i 's/^\(memory_limit\s*=\s*\).*/memory_limit = 1024M/' /usr/local/etc/php/php.ini


USER azhar

RUN composer install
USER root

# CMD php artisan serve --host=0.0.0.0 --port 80
