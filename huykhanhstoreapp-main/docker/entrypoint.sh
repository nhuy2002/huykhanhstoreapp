#!/bin/sh
set -e

APP_DIR=/var/www/html
STORAGE_DIR="$APP_DIR/storage"
DB_DIR="$APP_DIR/database"
DB_FILE="$DB_DIR/database.sqlite"
KEY_FILE="$STORAGE_DIR/app/.app_key"

# storage/ and database/ are mounted as volumes, so their content does not
# come from the image after the first run. Make sure the directory layout
# Laravel expects always exists.
mkdir -p \
    "$STORAGE_DIR/framework/cache/data" \
    "$STORAGE_DIR/framework/sessions" \
    "$STORAGE_DIR/framework/views" \
    "$STORAGE_DIR/framework/testing" \
    "$STORAGE_DIR/app/public" \
    "$STORAGE_DIR/app/private" \
    "$STORAGE_DIR/logs" \
    "$DB_DIR"

# First run: create the SQLite file inside the persisted volume.
# On every later run/rebuild the volume already has it, so it is kept as-is.
if [ ! -f "$DB_FILE" ]; then
    echo "[entrypoint] Creating new SQLite database at $DB_FILE"
    touch "$DB_FILE"
fi

# Keep APP_KEY stable across restarts/rebuilds (needed for sessions/cookies)
# by persisting it inside the storage volume unless it was provided via env.
if [ -z "$APP_KEY" ]; then
    if [ -f "$KEY_FILE" ]; then
        APP_KEY=$(cat "$KEY_FILE")
    else
        APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
        echo "$APP_KEY" > "$KEY_FILE"
        echo "[entrypoint] Generated new APP_KEY, stored at $KEY_FILE"
    fi
fi
export APP_KEY

php artisan storage:link --force >/dev/null 2>&1 || true
php artisan migrate --force

chown -R www-data:www-data "$STORAGE_DIR" "$DB_DIR" "$APP_DIR/bootstrap/cache"

exec "$@"
