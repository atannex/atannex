#!/bin/bash

echo "🔧 Fixing Laravel storage and cache permissions..."

# Fix directory permissions
find storage -type d -exec chmod 755 {} \;
find bootstrap/cache -type d -exec chmod 755 {} \;

# Fix file permissions
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;

# Fix symlink permissions
if [ -L public/storage ]; then
    chmod 755 public/storage
    echo "✅ public/storage symlink permissions set to 755"
else
    echo "⚠️  public/storage symlink not found. Run: php artisan storage:link"
fi

echo "✅ Permissions successfully updated (user-level)."
