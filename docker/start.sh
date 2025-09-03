#!/bin/bash
set -e

echo "Starting Laravel application..."

# Set proper permissions for Laravel directories
echo "Setting proper permissions..."
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/database

# Check vendor directory
echo "Checking vendor directory..."
if [ -z "$(ls -A /var/www/html/vendor 2>/dev/null)" ]; then
    echo "Installing Composer dependencies..."
    cd /var/www/html && composer install --no-interaction --no-progress --prefer-dist
else
    echo "Composer dependencies already installed"
fi

# Check if .env exists, if not copy from .env.example
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env file from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env || echo "No .env.example found, creating empty .env file..." && touch /var/www/html/.env
    chmod 666 /var/www/html/.env
fi

# Create separate database directory with proper permissions
echo "Setting up database..."
# Remove existing database directory and create a fresh one
rm -rf /var/www/html/database/database.sqlite
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/database
chmod -R 777 /var/www/html/database
chmod 666 /var/www/html/database/database.sqlite
ls -la /var/www/html/database/

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run migrations first
echo "Running database migrations..."
# Give a second for the file system to catch up
sleep 2
sqlite3 /var/www/html/database/database.sqlite ".tables" || echo "Testing SQLite connection failed"
php artisan migrate --force

# Clear caches and optimize 
echo "Clearing cache and optimizing..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo "Laravel application is ready!"

# Execute the main command
exec "$@"
