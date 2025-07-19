#!/bin/bash

###############################################################################
# 🚀 Laravel Shared Hosting Deployment Script (HostGator-compatible)
# Moves Laravel out of /public and prepares full deployable package
# Upload contents of 'deploy' folder to public_html on HostGator
###############################################################################

DEPLOY_DIR="deploy"
PUBLIC_DIR="public"

# ✅ Asset folders you want to copy from /public
ASSET_FOLDERS=("css" "js" "fonts" "assets")
ROOT_FILES=("favicon.ico" "robots.txt" "mix-manifest.json")

echo "🔁 Cleaning old deploy folder..."
rm -rf "$DEPLOY_DIR"
mkdir -p "$DEPLOY_DIR"

echo "📦 Copying Laravel backend files..."
cp -R bootstrap config database routes app artisan composer.* "$DEPLOY_DIR/"
cp -R vendor "$DEPLOY_DIR/vendor"

echo "🧠 Copying and patching public/index.php..."
cp "$PUBLIC_DIR/index.php" "$DEPLOY_DIR/index.php"

# Update paths in index.php to work from root
sed -i '' 's|/../vendor|/vendor|' "$DEPLOY_DIR/index.php" 2>/dev/null || sed -i 's|/../vendor|/vendor|' "$DEPLOY_DIR/index.php"
sed -i '' 's|/../bootstrap|/bootstrap|' "$DEPLOY_DIR/index.php" 2>/dev/null || sed -i 's|/../bootstrap|/bootstrap|' "$DEPLOY_DIR/index.php"

echo "🎨 Copying asset folders..."
for folder in "${ASSET_FOLDERS[@]}"; do
    if [ -d "$PUBLIC_DIR/$folder" ]; then
        cp -R "$PUBLIC_DIR/$folder" "$DEPLOY_DIR/$folder"
        echo "  ✅ $folder/"
    fi
done

echo "📄 Copying root public files..."
for file in "${ROOT_FILES[@]}"; do
    if [ -f "$PUBLIC_DIR/$file" ]; then
        cp "$PUBLIC_DIR/$file" "$DEPLOY_DIR/$file"
        echo "  ✅ $file"
    fi
done

# Optional: Copy root .htaccess to deploy
if [ -f ".htaccess" ]; then
    cp .htaccess "$DEPLOY_DIR/.htaccess"
    echo "🔐 Copied .htaccess"
fi

echo ""
echo "✅ Laravel app is ready in ./$DEPLOY_DIR/"
echo "📤 Upload all contents inside '$DEPLOY_DIR' to HostGator's public_html"
echo "💡 Make sure your .env file is correctly configured in production."
