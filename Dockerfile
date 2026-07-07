FROM php:8.2-fpm

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl \
        nginx \
        supervisor \
        gettext-base \
        libicu-dev \
        libzip-dev \
        zlib1g-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        sqlite3 \
        libsqlite3-dev \
        nodejs \
        npm \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl zip pdo_mysql pdo_sqlite bcmath opcache \
    && docker-php-ext-enable opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader

COPY package.json package-lock.json* ./
RUN npm install && npm run build

COPY . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf.template
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

CMD ["/usr/local/bin/entrypoint.sh"]
