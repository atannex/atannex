#!/bin/bash
set -e

echo "🚀 Starting Laravel + Vite build (dev/prod with hashed assets)..."

# -----------------------------
# 1️⃣ Load nvm and Node
# -----------------------------
export NVM_DIR="$HOME/.nvm"
if [ -s "$NVM_DIR/nvm.sh" ]; then
    \. "$NVM_DIR/nvm.sh"
else
    echo "📥 Installing nvm..."
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash
    \. "$NVM_DIR/nvm.sh"
fi

NODE_VERSION="22.12"
echo "📦 Installing/using Node $NODE_VERSION..."
nvm install $NODE_VERSION
nvm use $NODE_VERSION
echo "✅ Node: $(node -v), NPM: $(npm -v)"

# -----------------------------
# 2️⃣ Project root
# -----------------------------
PROJECT_DIR="$(pwd)"
echo "📂 Project directory: $PROJECT_DIR"
cd "$PROJECT_DIR"

# -----------------------------
# 3️⃣ Clean old build (optional)
# -----------------------------
echo "🧹 Cleaning old public/build..."
rm -rf public/build

# -----------------------------
# 4️⃣ Install dependencies
# -----------------------------
echo "📦 Installing project dependencies..."
if [ -f package-lock.json ]; then
    npm ci
else
    npm install
fi

# -----------------------------
# 5️⃣ Detect environment
# -----------------------------
ENVIRONMENT=$(php artisan env)
echo "🌐 Current environment: $ENVIRONMENT"

# -----------------------------
# 6️⃣ Local development
# -----------------------------
if [ "$ENVIRONMENT" = "local" ]; then
    echo "💻 Running Vite dev server..."

    # Ensure .env has dev server URL
    if ! grep -q "VITE_DEV_SERVER_URL" "$PROJECT_DIR/.env"; then
        echo "VITE_DEV_SERVER_URL=http://127.0.0.1:5173" >> "$PROJECT_DIR/.env"
        echo "✅ Added VITE_DEV_SERVER_URL to .env"
    fi

    echo "🟢 Starting npm run dev (Ctrl+C to stop)..."
    npm run dev

# -----------------------------
# 7️⃣ Production build
# -----------------------------
else
    echo "🏗️ Building production assets..."
    npm run build

    echo "🎉 Production build complete! Vite hashed assets are in public/build."
fi
