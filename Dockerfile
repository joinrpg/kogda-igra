FROM alfg/php-apache

RUN apk --update add php7-session && rm -f /var/cache/apk/*

COPY ./public_html /var/www/html

COPY ./etc/apache2/ /etc/apache2/
RUN rm /etc/apache2/sites/local.conf


RUN chmod +x /opt/entrypoint.sh

EXPOSE 80