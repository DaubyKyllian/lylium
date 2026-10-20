FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libsqlite3-dev \
        libzip-dev \
        libonig-dev \
        unzip \
        git \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_sqlite zip mbstring \
    && a2enmod rewrite deflate expires headers

# OPcache : PHP ne recompile plus les fichiers à chaque requête
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=64'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.validate_timestamps=1'; \
        echo 'opcache.revalidate_freq=60'; \
        echo 'realpath_cache_size=512k'; \
        echo 'realpath_cache_ttl=300'; \
    } > /usr/local/etc/php/conf.d/zz-perf.ini \
    && docker-php-ext-install opcache

# Autorise le .htaccess du projet (compression, cache, protection de la base)
RUN printf '<Directory /var/www/html>\n    AllowOverride All\n</Directory>\n' > /etc/apache2/conf-available/lylium.conf \
    && a2enconf lylium

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY . .

RUN mkdir -p database \
    && chown -R www-data:www-data /var/www/html \
    && chmod +x entrypoint.sh

EXPOSE 80

ENTRYPOINT ["./entrypoint.sh"]
