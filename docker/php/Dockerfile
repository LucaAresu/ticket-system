FROM php:8.2-fpm-bullseye

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    vim \
    git \
    zsh \
    sudo \
    wget

ENV TZ=Europe/Rome

#oh-my-zsh
RUN sh -c "$(wget -O- https://github.com/deluan/zsh-in-docker/releases/download/v1.1.5/zsh-in-docker.sh)" --

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY conf/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY conf/xdebug.ini /usr/local/bin/xdebug/xdebug.ini

COPY conf/shell-config.rc /tmp/shell-custom.rc
RUN cat /tmp/shell-custom.rc >> ~/.zshrc \
    && cat /tmp/shell-custom.rc >> ~/.bashrc
