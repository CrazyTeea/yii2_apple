FROM php:8.4-fpm-alpine

# Установка системных зависимостей для Yii2
RUN apk update && apk add --no-cache \
    curl \
    git \
    unzip \
    bash \
    zip \
    zlib-dev \
    libpng-dev \
    libwebp-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpq-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    postgresql-dev \
    libxml2-dev \
    libxslt-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install \
        mysqli \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        zip \
        mbstring \
        intl \
        soap \
    && docker-php-ext-enable pdo_mysql

# Установка Composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

# Создание пользователя для безопасности
ARG USER_ID=1000
ARG GROUP_ID=1000
RUN addgroup -g ${GROUP_ID} yii2user && \
    adduser -D -u ${USER_ID} -G yii2user yii2user && \
    chown -R yii2user:yii2user /var/www/html

USER yii2user

WORKDIR /var/www/html