FROM php:8.1-fpm

WORKDIR /app

RUN apt-get update && \
    apt-get install --yes --force-yes \
    cron g++ gettext libicu-dev openssl \
    libc-client-dev libkrb5-dev  \
    libxml2-dev libfreetype6-dev \
    libgd-dev libmcrypt-dev bzip2 \
    libbz2-dev libtidy-dev libcurl4-openssl-dev \
    libz-dev libmemcached-dev libxslt-dev libpq-dev \
    libzip4 libzip-dev libwebp-dev npm nano

# PHP Configuration
RUN docker-php-ext-install bcmath bz2 calendar  dba exif gettext iconv intl soap tidy xsl zip&&\
    docker-php-ext-install mysqli pgsql pdo pdo_mysql &&\
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp &&\
    docker-php-ext-install gd &&\
    docker-php-ext-configure imap --with-kerberos --with-imap-ssl &&\
    docker-php-ext-install imap &&\
    docker-php-ext-configure hash --with-mhash &&\
    pecl install xdebug && docker-php-ext-enable xdebug &&\
    pecl install mongodb && docker-php-ext-enable mongodb &&\
    pecl install redis && docker-php-ext-enable redis && \
    curl -sS https://getcomposer.org/installer | php \
            && mv composer.phar /usr/bin/composer

COPY . /app

RUN composer install --optimize-autoloader --ignore-platform-reqs

RUN npm install --quiet && npm run build --quiet

#RUN php /app/src/cronjob.php
RUN echo "0 0 * * * /usr/local/bin/php /app/bin/console app:update-currency-rates >> /var/log/cron.log 2>&1" > /etc/cron.d/my-cron
RUN chmod 0644 /etc/cron.d/my-cron
RUN crontab /etc/cron.d/my-cron

EXPOSE 9000

#CMD ["cron", "-f"]