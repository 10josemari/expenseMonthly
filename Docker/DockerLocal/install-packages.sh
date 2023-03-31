#!/usr/bin/env bash

# Bash "strict mode", to help catch problems and bugs in the shell script.
set -euo pipefail

# Tell apt-get we're never going to be able to give manual feedback:
export DEBIAN_FRONTEND=noninteractive

# Update the package listing, so we know what package exist:
apt-get update

# Install security updates:
apt-get -y upgrade

apt-get -y install --no-install-recommends \
	php8.1-fpm \
    php8.1-cli \
    php8.1-mysql \
    php8.1-xml \
    php8.1-gd \
    php8.1-mbstring \
    php8.1-bcmath \
    php8.1-zip \
    php8.1-memcached \
    php8.1-curl \
    php8.1-ldap \
    php8.1-xdebug \
    nginx \
    curl \
    ca-certificates \
    vim-tiny \
    gettext-base \
    xz-utils \
    mysql-client \
    mysql-server

# Delete index files we don't need anymore:
rm -rf /var/lib/apt/lists/*
