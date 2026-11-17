#!/usr/bin/env bash
#
# Builds release/auxtech-production.zip: everything the hosting account
# needs, ready to extract. No SSH, Composer or Node is needed on the server.
#
# Usage: bash scripts/build-release.sh
# Requires on this machine: php, composer, node/npm, zip.
# The Pusher secret is read from your local .env (it is never committed).

set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
BUILD="$(mktemp -d)"
OUT="$ROOT/release"
APP="$BUILD/auxtech"
trap 'rm -rf "$BUILD"' EXIT

echo "1/5 Building frontend assets"
(cd "$ROOT" && npm run build >/dev/null)

echo "2/5 Copying application files"
mkdir -p "$APP"
tar -C "$ROOT" \
    --exclude=.git --exclude=./.github --exclude=./node_modules --exclude=./release \
    --exclude=./tests --exclude=./docs --exclude=./scripts \
    --exclude=./.env --exclude=./.env.example --exclude=./.env.production \
    --exclude=./public/uploads --exclude=./public/hot --exclude=./storage/installed \
    --exclude='./storage/logs/*.log' --exclude='./storage/framework/cache/data/*' \
    --exclude='./storage/framework/sessions/*' --exclude='./storage/framework/views/*' \
    --exclude='./storage/framework/testing*' --exclude='./storage/media-library' \
    --exclude='./database/*.sqlite*' --exclude='./bootstrap/cache/*.php' \
    --exclude=./phpunit.xml --exclude=./playwright.config.ts --exclude=./vitest.config.ts \
    --exclude=./eslint.config.js --exclude=./.prettierrc --exclude=./tsconfig.json \
    --exclude=./vite.config.ts --exclude=./package.json --exclude=./package-lock.json \
    --exclude=./CLAUDE.md --exclude=./README.md --exclude=./.editorconfig --exclude=./.gitattributes \
    --exclude=./resources/js --exclude=./resources/css --exclude=./resources/brand \
    -cf - . | tar -C "$APP" -xf -

echo "3/5 Installing production PHP packages"
(cd "$APP" && COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --quiet)
(cd "$APP" && php artisan package:discover --ansi >/dev/null)
rm -f "$APP"/bootstrap/cache/config.php "$APP"/bootstrap/cache/routes-*.php

echo "4/5 Writing the production environment template"
secret="$(grep -E '^PUSHER_APP_SECRET=' "$ROOT/.env" 2>/dev/null | cut -d= -f2- || true)"
if [ -z "$secret" ]; then
    echo "   Warning: PUSHER_APP_SECRET is empty in .env; live admin updates will be off until it is set."
fi
sed -e 's/^APP_ENV=.*/APP_ENV=production/' \
    -e 's/^APP_DEBUG=.*/APP_DEBUG=false/' \
    -e 's/^APP_KEY=.*/APP_KEY=/' \
    -e 's|^APP_URL=.*|APP_URL=https://auxtechbd.com|' \
    -e '/^APP_INSTALLED=/d' \
    -e 's/^LOG_LEVEL=.*/LOG_LEVEL=warning/' \
    -e 's/^MAIL_MAILER=.*/MAIL_MAILER=smtp/' \
    -e '/^ADMIN_/d' \
    -e "s|^PUSHER_APP_SECRET=.*|PUSHER_APP_SECRET=${secret}|" \
    "$ROOT/.env.example" > "$APP/.env.production"

mkdir -p "$APP/public/uploads" "$APP/storage/framework/"{cache/data,sessions,views} "$APP/storage/logs" "$APP/bootstrap/cache"
touch "$APP/public/uploads/.gitkeep"

echo "5/5 Creating the zip"
mkdir -p "$OUT"
rm -f "$OUT/auxtech-production.zip"
(cd "$APP" && zip -qr "$OUT/auxtech-production.zip" . -x '*.DS_Store')

echo
echo "Done: $OUT/auxtech-production.zip ($(du -h "$OUT/auxtech-production.zip" | cut -f1), version $(cat "$ROOT/VERSION"))"
echo "Upload it to the domain folder, extract it, then open https://your-domain/install"
