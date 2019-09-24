FROM php:7.3-apache

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

RUN apt-get update && \
apt-get install -y gnupg zip unzip git
RUN apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql opcache
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
RUN if [ "${http_proxy}" != "" ]; then \
  # Needed for pecl to succeed
pear config-set http_proxy ${http_proxy} \
;fi
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN chown -R www-data:www-data /var/www/html/

RUN a2enmod rewrite

COPY etc/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY etc/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
