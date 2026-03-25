FROM dunglas/frankenphp:1-php8.4

# System dependencies
RUN apt-get update && apt-get install -y git && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN install-php-extensions pdo_mysql gd zip exif ftp opcache @composer

# PHP upload limits
RUN echo "upload_max_filesize = 100M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 150M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /app

# Install composer deps
COPY composer.json composer.lock ./
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
    || (sleep 5 && composer install --no-dev --optimize-autoloader --no-interaction --no-scripts)

# Copy application
COPY . .

# Run composer scripts now that app is present
RUN composer run-script post-autoload-dump 2>/dev/null || true

# Permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 755 /app/storage /app/bootstrap/cache

# Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# Entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

CMD ["/entrypoint.sh"]
