FROM php:8.2-fpm

# Install minimal required dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure PHP for development
RUN echo "memory_limit=1G" > /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "max_execution_time=600" > /usr/local/etc/php/conf.d/max-execution-time.ini \
    && echo "upload_max_filesize=100M" > /usr/local/etc/php/conf.d/upload-max-filesize.ini \
    && echo "post_max_size=100M" > /usr/local/etc/php/conf.d/post-max-size.ini

# Set working directory
WORKDIR /var/www/html

# Ensure directories exist
RUN mkdir -p /var/www/html/database \
    && mkdir -p /var/www/html/storage/app/public \
    && mkdir -p /var/www/html/storage/framework/cache/data \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/bootstrap/cache

# Set permissions for Laravel directories
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache

# Copy startup script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/start.sh"]
CMD ["php-fpm"]
