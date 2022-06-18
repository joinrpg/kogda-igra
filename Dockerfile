FROM alfg/php-apache

RUN apk --update add php7-session php7-pgsql && rm -f /var/cache/apk/*

COPY ./public_html /var/www/html

COPY ./etc/apache2/ /etc/apache2/
RUN rm /etc/apache2/sites/local.conf

COPY ./etc/php7/php.ini /etc/php7/php.ini


RUN chmod +x /opt/entrypoint.sh

EXPOSE 80