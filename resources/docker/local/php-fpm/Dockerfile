FROM php:8.4-fpm

ENV PHP_IDE_CONFIG "serverName=venging_machine"
ENV XDEBUG_MODE "debug"
ENV XDEBUG_START_WITH_REQUEST "trigger"
ENV XDEBUG_DISCOVER_CLIENT_HOST "1"
ENV OPCACHE_ENABLED=1
ENV OPCACHE_REVALIDATE_FREQ=0
ENV OPCACHE_VALIDATE_TIMESTAMPS=1
ENV OPCACHE_MAX_ACCELERATED_FILES=20000
ENV OPCACHE_MEMORY_CONSUMPTION=256
ENV OPCACHE_INTERNED_STRINGS_BUFFER=16
ENV OPCACHE_FAST_SHUTDOWN=1
ENV OPCACHE_PRELOAD_USER=www-data

RUN apt-get update

RUN apt-get install -y vim  \
    git \
    librabbitmq-dev \
    libssl-dev \
    libzip-dev \
    zip \
    unzip

RUN apt-get clean; rm -rf /var/www/html /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN curl -sSL https://github.com/fabpot/local-php-security-checker/releases/download/v2.0.6/local-php-security-checker_2.0.6_linux_386 --output /usr/bin/local-php-security-checker \
    && chmod +x /usr/bin/local-php-security-checker

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');"

RUN mv composer.phar /usr/local/bin/composer

RUN pecl install xdebug-3.4.0
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-install zip

CMD ["php-fpm"]

WORKDIR "/var/www/venging_machine"
