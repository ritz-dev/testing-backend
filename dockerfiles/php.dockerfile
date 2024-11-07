# Use the official PHP 8.2 image with Alpine Linux for a lightweight build
FROM php:8.2-fpm-alpine

# Set environment variables for non-interactive installation
ENV DEBIAN_FRONTEND=noninteractive

# Update package index and install system dependencies
RUN apk update && apk add --no-cache \
    autoconf \
    dpkg-dev dpkg \
    file \
    g++ \
    gcc \
    libc-dev \
    make \
    pkgconf \
    re2c

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy custom PHP configuration file
COPY php/php.ini /usr/local/etc/php/

# Set user permission
RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

# Change file owner permission 
RUN chown -R laravel:laravel /var/www/html

# Switch to the laravel user
USER laravel

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
