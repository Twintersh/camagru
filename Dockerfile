FROM php:latest

# Configure APT timeouts without modifying resolv.conf
RUN echo "Acquire::http::Timeout \"240\";" > /etc/apt/apt.conf.d/80-download-time \
    && echo "Acquire::ftp::Timeout \"240\";" >> /etc/apt/apt.conf.d/80-download-time \
    && echo "Acquire::Retries \"3\";" >> /etc/apt/apt.conf.d/80-download-time

RUN apt-get update && apt-get install -y git libpq-dev unzip zip libzip-dev && docker-php-ext-install pdo pdo_pgsql
#Install pdo_pgsql to enable php to connect to postgresql

# RUN apt-get update && apt-get install -y zlib1g-dev libpng-dev && docker-php-ext-install gd
# #Install gd for image manipulation functions

COPY . /var/www/

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/src/Controller
RUN composer install

EXPOSE 8000

CMD ["php","-S","0.0.0.0:8000", "-t", "/var/www/src/Controller"]
# Address 0.0.0.0 instead of localhost enables to accept connections from outside the docker container itself
# (https://stackoverflow.com/questions/25591413/docker-with-php-built-in-server)
