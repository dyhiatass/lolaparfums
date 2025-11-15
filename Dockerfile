FROM php:8.2-cli

WORKDIR /app

# Extensions nécessaires pour Symfony + MySQL
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libonig-dev \
    && docker-php-ext-install intl zip pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Copie du code
COPY . .

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# Dépendances PHP (sans dev)
RUN composer install --no-dev --optimize-autoloader --prefer-dist || true

# Assets Symfony
RUN php bin/console asset-map:compile --env=prod || true

# Cache Symfony
RUN php bin/console cache:clear --env=prod || true

# Lancement via script
COPY docker-entrypoint.sh /app/docker-entrypoint.sh
RUN chmod +x /app/docker-entrypoint.sh

CMD ["/app/docker-entrypoint.sh"]


