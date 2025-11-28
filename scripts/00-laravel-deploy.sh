
#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Publishing cloudinary provider..."
php artisan vendor: publish --provider="Cloudinary Labs\Cloudinary Laravel\CloudinaryServiceProvider" --tag="cloudinary-laravel-config"
