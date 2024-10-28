# Utilise l'image officielle PHP avec Apache
FROM php:8.3-apache

# Met à jour les dépôts et installe les dépendances nécessaires
RUN apt-get update -y && apt-get install -y \
    openssl \
    zip \
    unzip \
    git \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    mariadb-client \
    libgd-dev \
    libmagickwand-dev \
    libmagickcore-dev \
    imagemagick \
    && docker-php-ext-install pdo_mysql mbstring gd exif

# Installe Imagick à partir de la source
RUN cd /tmp && \
    git clone https://github.com/Imagick/imagick.git && \
    cd imagick && \
    phpize && \
    ./configure && \
    make && \
    make install && \
    docker-php-ext-enable imagick

# Installe Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définit le répertoire de travail
WORKDIR /app

# Copie les fichiers de l'application
COPY . /app

# Définit les permissions pour l'application
RUN chown -R www-data:www-data /app

# Installe les dépendances PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --verbose

# Require additional packages
RUN composer require php-open-source-saver/jwt-auth \
    guzzlehttp/guzzle \
    stichoza/google-translate-php

# Exécute les commandes Artisan pour préparer l'application
CMD php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider" && \
    php artisan storage:link && \
    php artisan key:generate && \
    php artisan migrate:refresh && \
    php artisan jwt:secret && \
    php artisan serve --host=0.0.0.0 --port=8181

# Expose le port de l'application
EXPOSE 8181
