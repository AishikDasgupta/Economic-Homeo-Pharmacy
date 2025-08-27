#!/bin/bash

# Economic Homeo Pharmacy CMS Deployment Script
# This script automates the deployment process for the CMS

echo "Starting deployment process..."

# Update repository
echo "Pulling latest changes from repository..."
git pull origin main

# Install/update PHP dependencies
echo "Installing/updating PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Install/update JavaScript dependencies
echo "Installing/updating JavaScript dependencies..."
npm install

# Build frontend assets
echo "Building frontend assets..."
npm run production

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear caches
echo "Clearing application caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize the application
echo "Optimizing application..."
php artisan optimize

# Ensure storage link is created
echo "Creating storage link..."
php artisan storage:link

# Set proper permissions
echo "Setting proper permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "Deployment completed successfully!"