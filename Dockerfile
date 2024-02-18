FROM php:apache
RUN DEBIAN_FRONTEND=noninteractive

WORKDIR /var/www/html
COPY web .

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql



ENV PORT=8000
EXPOSE ${PORT}

RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf
