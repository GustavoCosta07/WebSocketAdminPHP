FROM php:8.0-cli-alpine

# Instalação do autoconf, g++, make e mariadb-client
RUN apk --no-cache add autoconf g++ make mariadb-client

# Instalação do Swoole e MySQLi
RUN pecl install swoole && \
    docker-php-ext-enable swoole && \
    docker-php-ext-install mysqli pdo_mysql

WORKDIR /usr/src/myapp
COPY . /usr/src/myapp

RUN ls
CMD [ "php", "./websocket_server.php" ]
