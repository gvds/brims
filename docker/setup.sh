#!/usr/bin/env bash

# =============================================================================
# BRIMS Docker first-time setup script
# =============================================================================
# Run this once after cloning the repository:
#
#   bash docker/setup.sh
#
# =============================================================================

set -e

COMPOSE="docker compose"

echo ""
echo "=========================================="
echo "  BRIMS — Docker Development Setup"
echo "=========================================="
echo ""

# 1. Copy .env if it doesn't exist
if [ ! -f .env ]; then
    echo "→ Copying .env.docker to .env..."
    cp .env.docker .env
else
    echo "→ .env already exists, skipping copy."
fi

# 2. Build the Docker image
echo ""
echo "→ Building Docker image (this may take several minutes on first run)..."
$COMPOSE build

# 3. Start services
echo ""
echo "→ Starting services..."
$COMPOSE up -d

# 4. Wait for MariaDB to be healthy
echo ""
echo "→ Waiting for MariaDB to be ready (timeout: 60s)..."
MAX_RETRIES=30
RETRIES=0
until $COMPOSE exec mariadb healthcheck.sh --connect --innodb_initialized > /dev/null 2>&1; do
    RETRIES=$((RETRIES + 1))
    if [ $RETRIES -ge $MAX_RETRIES ]; then
        echo ""
        echo "ERROR: MariaDB did not become healthy in time. Check logs with:"
        echo "  docker compose logs mariadb"
        exit 1
    fi
    printf '.'
    sleep 2
done
echo " ready!"

# 5. Install PHP dependencies
echo ""
echo "→ Installing PHP dependencies..."
$COMPOSE exec laravel.test composer install

# 6. Install Node dependencies and build frontend assets
echo ""
echo "→ Installing Node dependencies and building assets..."
$COMPOSE exec laravel.test npm install
$COMPOSE exec laravel.test npm run build

# 7. Generate application key
echo ""
echo "→ Generating application key..."
$COMPOSE exec laravel.test php artisan key:generate

# 8. Run migrations and seed through isolated DB stage script
echo ""
echo "→ Running database migrations and seed..."
bash docker/setup-db.sh fresh-seed

# 9. Create storage symlink
echo ""
echo "→ Creating storage symlink..."
$COMPOSE exec laravel.test php artisan storage:link

echo ""
echo "=========================================="
echo "  Setup complete!"
echo "=========================================="
echo ""
echo "  Application:  http://localhost"
echo "  phpMyAdmin:   http://localhost:8080"
echo "  Mailpit:      http://localhost:8025"
echo ""
echo "  To view logs:    docker compose logs -f"
echo "  To run artisan:  docker compose exec laravel.test php artisan <command>"
echo "  To run tests:    docker compose exec laravel.test php artisan test"
echo ""
