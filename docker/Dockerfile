ARG PHP_VERSION=8.1
FROM composer:2.2.7 AS composer
FROM php:${PHP_VERSION}-cli-buster AS php

FROM php
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN apt-get update && \
    apt-get install --assume-yes --no-install-recommends \
        git \
        libzip-dev \
        libzip4 \
    && \
    docker-php-ext-install zip && \
    pecl install pcov && \
    docker-php-ext-enable pcov && \
    apt-get purge -f -y \
      libzip-dev \
    && \
    rm -rf /var/lib/apt/lists/* && \
    addgroup --gid ${GROUP_ID} user && \
    adduser --system --uid ${USER_ID} --home /home/user --gid ${GROUP_ID} --disabled-password user

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_MEMORY_LIMIT -1

WORKDIR /data/
USER user:user