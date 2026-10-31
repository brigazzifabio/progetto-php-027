#!/usr/bin/env bash
#
# Crea un nuovo progetto Laravel e ci applica sopra lo scheletro e-commerce.
#
# Uso:   ./install.sh [nome-cartella]
# Req.:  PHP >= 8.2, Composer, (opzionale) Node/npm per compilare gli asset
#
set -euo pipefail

TARGET="${1:-shop}"
OVERLAY_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

if [ -e "$TARGET" ]; then
  echo "La cartella '$TARGET' esiste già. Scegli un altro nome." >&2
  exit 1
fi

echo "→ Creo il progetto Laravel in '$TARGET'…"
composer create-project laravel/laravel "$TARGET" --no-interaction

echo "→ Copio i file dell'e-commerce…"
for dir in app bootstrap config database resources routes tests; do
  cp -R "$OVERLAY_DIR/$dir/." "$TARGET/$dir/"
done

# Il test di esempio di Laravel verifica "/" con 200: con il nostro negozio continua a funzionare,
# ma lo rimuoviamo per tenere la suite pulita.
rm -f "$TARGET/tests/Feature/ExampleTest.php" "$TARGET/tests/Unit/ExampleTest.php"
# La welcome page non serve più.
rm -f "$TARGET/resources/views/welcome.blade.php"

cd "$TARGET"

echo "→ Configuro .env…"
cat >> .env <<'ENV'

# --- E-commerce ---
SHOP_NAME="Laravel Shop"
SHOP_CURRENCY=EUR
SHOP_TAX_RATE=22
SHOP_SHIPPING_CENTS=690
SHOP_FREE_SHIPPING_CENTS=5000
SHOP_PAYMENT_DRIVER=fake
SHOP_ADMIN_EMAIL=admin@example.com
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=
ENV

# Le email in sviluppo finiscono in storage/logs/laravel.log
sed -i.bak -e 's/^MAIL_MAILER=.*/MAIL_MAILER=log/' -e 's/^APP_LOCALE=.*/APP_LOCALE=it/' .env && rm -f .env.bak
# Per i messaggi di validazione in italiano: composer require laravel-lang/common --dev && php artisan lang:add it

echo "→ Migrazioni e dati demo…"
php artisan migrate:fresh --seed --force
php artisan storage:link

echo "→ Eseguo i test…"
php artisan test

cat <<DONE

✅ Fatto! Avvia il server con:

    cd $TARGET
    composer run dev        # server + queue worker + vite
    # oppure solo: php artisan serve  (e in un altro terminale: php artisan queue:work)

Accessi demo (password: password)
  Admin:   admin@example.com   → /admin
  Cliente: cliente@example.com
Coupon:   BENVENUTO10 (-10%), SCONTO5 (-5€ sopra 30€)

DONE
