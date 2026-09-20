#!/bin/bash
# Smart Center Deployment Script
# Use this after uploading files to your server

echo "=== Smart Center Deployment ==="
echo "Step 1: Install dependencies..."
composer install --optimize-autoloader --no-dev

echo "Step 2: Generate app key..."
php artisan key:generate

echo "Step 3: Create storage symlink..."
php artisan storage:link

echo "Step 4: Cache configuration..."
php artisan config:cache

echo "Step 5: Cache routes..."
php artisan route:cache

echo "Step 6: Run migrations..."
php artisan migrate --force

echo "✅ Deployment complete!"
echo "Your app is now live at: $APP_URL"
