FROM php:8.1-fpm

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN apt-get update  \
    && apt-get install -y curl git libzip-dev \
    && docker-php-ext-install -j$(nproc) zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g ${GROUP_ID} php-fpm
RUN useradd -s /bin/bash -u ${USER_ID} -g ${GROUP_ID} php-fpm
USER php-fpm

WORKDIR /var/www

CMD ["php-fpm"]
