FROM ghcr.io/joinrpg/join-php-image:0.1.0

COPY ./public_html /var/www/html

COPY ./etc/apache2/sites/ /etc/apache2/sites/

EXPOSE 8080