#!/bin/bash

###############################################################################
# 🚀 Laravel Shared Hosting Deployment Script (HostGator-compatible)
# Moves Laravel out of /public and prepares full deployable package
# Upload contents of 'deploy' folder to public_html on HostGator
###############################################################################

set -e  # Exit on error

DEPLOY_DIR="deploy"
PUBLIC_DIR="public"

# ✅ Asset folders to copy from /public
ASSET_FOLDERS=("css" "js" "fonts" "assets" "images" "storage")
ROOT_FILES=("favicon.ico" "robots.txt" "mix-manifest.json" "index.php")

echo "🔁 Cleaning old '$DEPLOY_DIR/' folder..."
rm -rf "$DEPLOY_DIR"
mkdir -p "$DEPLOY_DIR"

echo "📦 Copying Laravel core files..."
cp -R bootstrap config database routes app artisan composer.* "$DEPLOY_DIR/"
cp -R vendor "$DEPLOY_DIR/vendor"
[ -d "lang" ] && cp -R lang "$DEPLOY_DIR/lang"

echo "🧠 Rewriting and copying 'index.php' to root of deploy folder..."
cp "$PUBLIC_DIR/index.php" "$DEPLOY_DIR/index.php"

# ⚙️ Update index.php paths
sed -i.bak 's|__DIR__.\+../vendor|__DIR__ . "/vendor"|' "$DEPLOY_DIR/index.php"
sed -i.bak 's|__DIR__.\+../bootstrap|__DIR__ . "/bootstrap"|' "$DEPLOY_DIR/index.php"
rm "$DEPLOY_DIR/index.php.bak"

echo "🎨 Copying asset folders..."
for folder in "${ASSET_FOLDERS[@]}"; do
    if [ -d "$PUBLIC_DIR/$folder" ]; then
        cp -R "$PUBLIC_DIR/$folder" "$DEPLOY_DIR/$folder"
        echo "  ✅ Copied: $folder/"
    fi
done

echo "📄 Copying selected root-level public files..."
for file in "${ROOT_FILES[@]}"; do
    if [ -f "$PUBLIC_DIR/$file" ]; then
        cp "$PUBLIC_DIR/$file" "$DEPLOY_DIR/$file"
        echo "  ✅ Copied: $file"
    fi
done

# Optional: copy root .htaccess
if [ -f ".htaccess" ]; then
    cp .htaccess "$DEPLOY_DIR/.htaccess"
    echo "🔐 Copied .htaccess"
fi

# Optional: copy production-ready .env
if [ -f ".env.production" ]; then
    cp .env.production "$DEPLOY_DIR/.env"
    echo "⚙️ Copied .env.production to deploy/.env"
fi

echo ""
echo "✅ Laravel app is ready in '$DEPLOY_DIR/'"
echo "📤 Upload all contents from '$DEPLOY_DIR/' into HostGator's 'public_html/' folder"
echo "🚨 Ensure your database and .env are correctly configured in production"
