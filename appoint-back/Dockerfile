FROM php:7.2-apache
RUN docker-php-ext-install pdo pdo_mysql
COPY . /var/www/html/

# docker build -t cms .
# docker run -d --publish 1000:80 --name my-CMS cms
