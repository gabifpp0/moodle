FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libxml2-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install \
        pgsql \
        pdo_pgsql \
        intl \
        zip \
        gd \
        soap \
        opcache \
    && a2enmod rewrite \
    && docker-php-ext-enable opcache \
    && printf '%s\n' 'max_input_vars = 5000' > /usr/local/etc/php/conf.d/moodle.ini \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php \
    && sed -ri \
        -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php/moodle-ssl.conf /etc/apache2/sites-available/moodle-ssl.conf
COPY docker/php/entrypoint.sh /usr/local/bin/moodle-entrypoint

WORKDIR /var/www/html

RUN a2ensite moodle-ssl \
    && a2dissite 000-default \
    && chmod +x /usr/local/bin/moodle-entrypoint

ENTRYPOINT ["moodle-entrypoint"]