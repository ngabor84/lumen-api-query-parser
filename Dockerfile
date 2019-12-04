FROM php:7.4-zts-alpine

RUN curl -Ss https://getcomposer.org/installer | php && \
    mv composer.phar /usr/bin/composer

RUN apk add --no-cache $PHPIZE_DEPS &&  \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    echo "xdebug.enable=1" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.remote_host=\"10.20.30.40\"" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/php.ini

RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/php.ini && \
    echo "display_errors = On" >> /usr/local/etc/php/php.ini && \
    echo "display_startup_errors = On" >> /usr/local/etc/php/php.ini

ENV XDEBUG_CONFIG idekey=PHPSTORM
