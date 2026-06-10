#!/usr/bin/env bash

set -euo pipefail

COMPOSE="docker compose"
MODE="${1:-fresh-seed}"

echo ""
echo "=========================================="
echo "  BRIMS — Isolated Database Setup"
echo "=========================================="
echo ""

echo "→ Ensuring services are running..."
$COMPOSE up -d mariadb laravel.test

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

echo ""
echo "→ Running database stage (${MODE})..."
case "${MODE}" in
    fresh-seed)
        $COMPOSE exec laravel.test php artisan migrate:fresh --seed --no-interaction
        ;;
    migrate-seed)
        $COMPOSE exec laravel.test php artisan migrate --seed --no-interaction
        ;;
    *)
        echo "ERROR: Unknown mode '${MODE}'. Use: fresh-seed | migrate-seed"
        exit 1
        ;;
esac

echo ""
echo "=========================================="
echo "  Database stage passed (${MODE})"
echo "=========================================="
echo ""
