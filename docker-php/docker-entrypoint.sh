#!/bin/bash
set -e

echo "Starting Laravel application initialization..."

# Create database directory if not exists
if [ ! -d "/var/www/html/database" ]; then
    mkdir -p /var/www/html/database
    echo "Created database directory"
fi

# Create database file if not exists
if [ ! -f "/var/www/html/database/database.sqlite" ]; then
    touch /var/www/html/database/database.sqlite
    echo "Created database file"
fi

# Check if .env file exists, if not copy from example
if [ ! -f "/var/www/html/.env" ]; then
    if [ -f "/var/www/html/.env.example" ]; then
        cp /var/www/html/.env.example /var/www/html/.env
        echo "Created .env file from example"
    else
        touch /var/www/html/.env
        echo "Created empty .env file"
    fi
fi

# Ensure storage directory and bootstrap/cache have proper permissions
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/bootstrap/cache
echo "Created Laravel storage directories"

# Skip running artisan commands if vendor directory doesn't exist yet
# This prevents errors when the container is first started
if [ ! -d "/var/www/html/vendor" ] || [ -z "$(ls -A /var/www/html/vendor 2>/dev/null)" ]; then
    echo "Vendor directory not found. Installing dependencies..."
    cd /var/www/html
    composer install --no-interaction --no-progress --prefer-dist
    echo "Composer dependencies installed"
fi

# Only run artisan commands if vendor directory exists
if [ -d "/var/www/html/vendor" ] && [ ! -z "$(ls -A /var/www/html/vendor 2>/dev/null)" ]; then
    # Generate application key if not set
    if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
        echo "Generating application key..."
        php artisan key:generate --force
    fi
    
    # Run migrations first
    echo "Running migrations..."
    php artisan migrate --force
    
    # Clear config cache first (safe operations)
    echo "Clearing config caches..."
    php artisan config:clear
    php artisan view:clear
    php artisan route:clear
    
    # Cache clearing that depends on database
    echo "Clearing application cache..."
    php artisan cache:clear || echo "Warning: Could not clear cache table. Continuing anyway."
    echo "Cache operations completed"
fi

echo "Laravel application initialization completed"

# First argument is the command to execute (default: php-fpm)
exec "$@"
