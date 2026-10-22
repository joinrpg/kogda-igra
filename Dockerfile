FROM php:8.5-apache

# Системные библиотеки для PHP-расширений
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP-расширения (json, session, curl — встроены в PHP 8.x)
RUN docker-php-ext-install pgsql

# OPcache (встроен в PHP 8.5): на проде без проверки mtime, локально переопределяется в docker-compose.yml
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0
COPY ./etc/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Лимит памяти на запрос меньше лимита контейнера: тяжёлый запрос падает сам, под живёт
COPY ./etc/php/memory.ini /usr/local/etc/php/conf.d/memory.ini

# Apache-модули: rewrite для .htaccess, negotiation для MultiViews,
# remoteip — реальный IP клиента из X-Forwarded-For от traefik
RUN a2enmod rewrite negotiation remoteip

# Лимиты prefork: по умолчанию до 150 процессов, а памяти у контейнера 128Mi
COPY ./etc/apache2/mpm_prefork.conf /etc/apache2/mods-available/mpm_prefork.conf

# Порт 8080 вместо стандартного 80 (совместимость с docker-compose и K8s)
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf

# Конфиг сайта (Debian-путь: sites-available + a2ensite)
COPY ./etc/apache2/sites/kogda.conf /etc/apache2/sites-available/kogda.conf
RUN a2dissite 000-default && a2ensite kogda

# Код приложения
COPY ./public_html /var/www/html

# Версия CSS для сброса кэша браузера: хэш содержимого, считается при сборке,
# в PHP доступна как $_SERVER['CSS_VERSION'] без чтения файла на каждый запрос
RUN echo "SetEnv CSS_VERSION $(md5sum /var/www/html/default.css | cut -c1-8)" \
    > /etc/apache2/conf-enabled/css-version.conf

# Схема БД той же версии, что и код: compat-тесты kogda-igra-net берут её прямо из образа.
# Вне document root — Apache её не отдаёт.
COPY ./db/init /db-init

EXPOSE 8080
