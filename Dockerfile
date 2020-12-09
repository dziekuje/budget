FROM php:7.2-fpm

# Install NGINX and other packages required for PHP extensions
RUN apt-get update && \
    apt-get install -y nginx libzip-dev

# Configure NGINX
COPY nginx.conf /etc/nginx/sites-enabled/default

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install zip && \
    docker-php-ext-install calendar

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and Yarn
RUN curl -sL https://deb.nodesource.com/setup_15.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g yarn

WORKDIR /var/www
