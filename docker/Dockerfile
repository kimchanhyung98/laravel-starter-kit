FROM php:8.2-fpm
LABEL maintainer="kimchanhyung98@gmail.com"

# Set working directory
WORKDIR /var/www

# Add docker php ext repo
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php extensions
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pdo_mysql zip exif pcntl gd memcached redis

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    git \
    curl \
    lua-zlib-dev \
    libmemcached-dev \
    nginx \
    redis \
    supervisor \
  && apt-get clean

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Create Log Directory
RUN mkdir /var/log/php
RUN touch /var/log/php/access.log && chmod 777 /var/log/php/access.log
RUN touch /var/log/php/error.log && chmod 777 /var/log/php/error.log

RUN touch /var/log/redis/access.log && chmod 777 /var/log/redis/access.log
RUN touch /var/log/redis/error.log && chmod 777 /var/log/redis/error.log


#FROM public.ecr.aws/ecr_name/php-8.2:latest

# Set working directory
#WORKDIR /var/www

# Copy code to /var/www
COPY --chown=www:www-data . /var/www

# add root to www group
RUN chmod -R ug+w /var/www/storage

# Copy nginx,php,supervisor configs
RUN cp supervisor.conf /etc/supervisord.conf
RUN cp php.ini /usr/local/etc/php/conf.d/app.ini
RUN cp nginx.conf /etc/nginx/sites-enabled/default

# Deployment steps
# RUN composer install --optimize-autoloader --no-dev
RUN composer config -g repo.packagist composer https://packagist.org
RUN composer clear-cache
RUN composer install --no-cache
RUN chmod +x /var/www/run.sh

EXPOSE 80
ENTRYPOINT ["/var/www/run.sh"]
