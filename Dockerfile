FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libonig-dev \
    && docker-php-ext-install intl pdo_mysql opcache zip \
    && rm -rf /var/lib/apt/lists/*

COPY . .

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
  && rm composer-setup.php

RUN composer install --no-dev --optimize-autoloader --prefer-dist || true

RUN php bin/console cache:clear --env=prod || true

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV PORT=10000

CMD php -S 0.0.0.0:$PORT -t public
