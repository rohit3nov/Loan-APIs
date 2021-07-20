FROM php:7.4-fpm-alpine

# Install extensions
RUN docker-php-ext-install mysqli pdo_mysql

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html
ARG USER_ID
RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
RUN apk --no-cache add shadow
# && usermod -u ${USER_ID} www-data
CMD ["php-fpm"]

EXPOSE 9000