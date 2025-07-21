#!/bin/bash

# Laravel Project Root (update this if running from outside)
PROJECT_ROOT=$(pwd)

echo "🔧 Fixing Laravel storage and cache permissions..."

# Fix directory permissions
find "$PROJECT_ROOT/storage" -type d -exec chmod 755 {} \;
find "$PROJECT_ROOT/bootstrap/cache" -type d -exec chmod 755 {} \;

# Fix file permissions
find "$PROJECT_ROOT/storage" -type f -exec chmod 644 {} \;
find "$PROJECT_ROOT/bootstrap/cache" -type f -exec chmod 644 {} \;

# Set correct ownership (adjust user:group for your server setup, e.g., www-data)
chown -R www-data:www-data "$PROJECT_ROOT/storage"
chown -R www-data:www-data "$PROJECT_ROOT/bootstrap/cache"

# Fix symlink permissions
if [ -L "$PROJECT_ROOT/public/storage" ]; then
    chmod 755 "$PROJECT_ROOT/public/storage"
    echo "✅ public/storage symlink permissions set to 755"
else
    echo "⚠️  public/storage symlink not found. Run: php artisan storage:link"
fi

echo "✅ Permissions successfully updated."
