FROM php:8.2-apache

# Instala dependências e PDO MySQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql

# Instala e habilita Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configurações básicas do Xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copia aplicação
COPY ./src /var/www/html/

# Permissões
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80