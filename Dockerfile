FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-install intl pdo_mysql pdo_pgsql opcache zip \
    && rm -rf /var/lib/apt/lists/*

COPY . .

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
  && rm composer-setup.php

# Install PHP dependencies (sans dev)
RUN composer install --no-dev --optimize-autoloader --prefer-dist || true

# Compile assets Symfony
RUN php bin/console asset-map:compile --env=prod || true

# Clear Symfony cache
RUN php bin/console cache:clear --env=prod || true

# Ajouter l'entrypoint (IMPORTANT)
COPY docker-entrypoint.sh /app/docker-entrypoint.sh
RUN chmod +x /app/docker-entrypoint.sh

# Commande lancée automatiquement sur Render
CMD ["/app/docker-entrypoint.sh"]
