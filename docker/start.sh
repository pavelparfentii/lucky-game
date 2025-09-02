#!/bin/bash
set -e

echo "Starting Laravel application..."

# Set proper permissions for Laravel directories
echo "Setting proper permissions..."
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache
[ -d /var/www/html/database ] && chmod -R 777 /var/www/html/database

# Install Composer dependencies if vendor directory doesn't exist
if [ ! -d /var/www/html/vendor ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --no-progress
fi

# Check if .env exists, if not copy from .env.example
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env file from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env || echo "No .env.example found, creating empty .env file..." && touch /var/www/html/.env
    chmod 666 /var/www/html/.env
fi

# Ensure database file exists
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "Creating SQLite database file..."
    mkdir -p /var/www/html/database
    touch /var/www/html/database/database.sqlite
    chmod 777 /var/www/html/database/database.sqlite
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear caches and optimize
echo "Clearing cache and optimizing..."
php artisan optimize:clear

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

echo "Laravel application is ready!"

# Execute the main command
exec "$@"
