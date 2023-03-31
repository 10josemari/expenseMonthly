FROM ubuntu:22.04

ARG PROJECT_NAME="expenseMonthly"

# Instalacion paquetes en el contenedor
COPY install-packages.sh .
RUN ./install-packages.sh && rm install-packages.sh

# Instalacion paquetes para el desarrollo
# Comentar cuando se use fuera del desarrollo local
COPY install-dev-packages.sh .
RUN ./install-dev-packages.sh && rm install-dev-packages.sh

# s6-overlay
ARG S6_OVERLAY_VERSION=3.1.2.1
ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-noarch.tar.xz /tmp
RUN tar -C / -Jxpf /tmp/s6-overlay-noarch.tar.xz
ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-x86_64.tar.xz /tmp
RUN tar -C / -Jxpf /tmp/s6-overlay-x86_64.tar.xz
ENTRYPOINT ["/init"]

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.4.4

# Add www-data -the user the default php-fpm pool runs as-, to the webmaster group.
# The webmaster has GID 499, and a matching group must exist in the host.
# This allows php-fpm to write to the storage and cache directories.
RUN groupadd -r -g 499 webmaster
RUN usermod -aG webmaster www-data

# Copia el contenido de prebuiltfs al contenedor (observar el contenido si hay dudas)
COPY prebuiltfs/ /

# Install Laravel
WORKDIR /var/www/
RUN composer create-project laravel/laravel ${PROJECT_NAME}
WORKDIR /var/www/${PROJECT_NAME}
COPY conf/env .env

# Permisos necesarios para que Laravel acceda a estos directorios
RUN chown -R www-data.www-data /var/www/${PROJECT_NAME}/storage && chown -R www-data.www-data /var/www/${PROJECT_NAME}/bootstrap/cache/

# Expose HTTP and Xdebug
EXPOSE 80 443 9003
