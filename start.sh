#!/bin/bash
set -e

# Make sure .env file exists
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        touch .env
    fi
fi

# Get current user ID and group ID and add to .env file
echo "Adding UID and GID to .env file..."
grep -q "UID=" .env || echo UID=$(id -u) >> .env
grep -q "GID=" .env || echo GID=$(id -g) >> .env

# Create database directory if it doesn't exist
mkdir -p database
touch database/database.sqlite
chmod 666 database/database.sqlite

echo "Starting Docker services..."
docker compose up -d

echo "Waiting for services to initialize..."
sleep 5

echo "Running database migrations..."
docker compose exec app php artisan migrate --force || echo "Migration failed - services might still be starting. Please try again in a moment."

echo "Application is ready!"
echo "Visit: http://localhost:8084"
