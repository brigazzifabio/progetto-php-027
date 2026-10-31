# Laravel E-commerce — scheletro completo

Struttura e-commerce pronta da applicare sopra un progetto **Laravel 12/13** nuovo (PHP ≥ 8.2).
Blade + Tailwind, nessun pacchetto aggiuntivo richiesto: funziona con il `composer.json` standard di Laravel.

## Installazione rapida

```bash
unzip laravel-ecommerce.zip
cd laravel-ecommerce
chmod +x install.sh
./install.sh mio-shop        # crea il progetto, copia i file, migra, semina, lancia i test
cd mio-shop && composer run dev
```

**Su Windows** (PowerShell/Prompt dei comandi, nella cartella estratta):

```bat
install.bat mio-shop
cd mio-shop
php artisan serve
```

Apri http://localhost:8000 — admin su `/admin`.

| Utente | Email | Password |
|---|---|---|
| Admin | admin@example.com | password |
| Cliente | cliente@example.com | password |

Coupon demo: `BENVENUTO10` (−10%), `SCONTO5` (−5 € sopra 30 €).

### Installazione manuale (progetto esistente)

1. Copia le cartelle `app/ bootstrap/ config/ database/ resources/ routes/ tests/` nel progetto
   (attenzione: sovrascrive `User.php`, `UserFactory.php`, `DatabaseSeeder.php`, `AppServiceProvider.php`,
   `bootstrap/app.php` e `routes/web.php` — se li hai già modificati, unisci a mano).
2. Aggiungi al `.env` le variabili `SHOP_*` (vedi `install.sh`).
3. `php artisan migrate --seed && php artisan storage:link`
4. `php artisan queue:work` per l'invio delle email (o `QUEUE_CONNECTION=sync` in sviluppo).

Senza `npm run build` le pagine usano Tailwind da CDN; con Vite attivo usano `resources/css/app.css`.

## Funzionalità

**Negozio**: home con prodotti in evidenza, catalogo con categorie/sottocategorie, ricerca, filtri e ordinamento,
scheda prodotto, prezzi barrati, avvisi di scorta bassa.

**Carrello**: salvato su database (ospite tramite token in sessione, utente per `user_id`), unione automatica
del carrello ospite al login, controllo giacenze, coupon a percentuale o importo fisso con minimo d'ordine,
scadenza e limite utilizzi.

**Checkout**: ordine creato in transazione con *lock pessimistico* sulle giacenze, snapshot di prezzi e indirizzo,
IVA e spedizione (gratuita sopra soglia) configurabili, pagamento con gateway intercambiabile, email di conferma in coda.

**Area cliente**: registrazione/login (con rate limit), storico ordini e dettaglio.

**Admin** (`/admin`): dashboard (fatturato del mese, ordini da spedire, scorte basse), CRUD prodotti con upload immagine
e prezzi in euro con virgola, CRUD categorie, gestione ordini con macchina a stati
(`pending → paid → shipped → delivered`, annullo/rimborso con ripristino stock) e email di spedizione con tracking.

## Struttura

```
app/
├── Enums/OrderStatus.php              stati ordine + transizioni consentite
├── Exceptions/InsufficientStockException.php
├── Http/
│   ├── Controllers/
│   │   ├── Shop/   Home, Product, Cart, Checkout, AccountOrder, StripeWebhook
│   │   ├── Auth/   AuthenticatedSession, RegisteredUser
│   │   └── Admin/  Dashboard, Product, Category, Order
│   ├── Middleware/EnsureUserIsAdmin.php
│   └── Requests/   CartItem, Checkout, Admin/Product, Admin/Category
├── Mail/           OrderConfirmation, OrderShipped
├── Models/         User, Category, Product, Cart, CartItem, Coupon, Order, OrderItem
├── Policies/OrderPolicy.php
├── Providers/AppServiceProvider.php  binding servizi, gate, direttiva @money
├── Services/
│   ├── Cart/       CartService, CartTotals
│   ├── Payment/    PaymentGateway (interfaccia), FakePaymentGateway, StripePaymentGateway, PaymentResult
│   └── OrderService.php               creazione ordine, pagamento, cambi di stato
└── Support/Money.php                 formattazione e conversione centesimi
config/shop.php                       valuta, IVA, spedizione, driver pagamento
database/   6 migration, factory, seeder demo
resources/views/  layouts, shop, account, auth, admin, mail, partials, components
routes/web.php
tests/      Feature (Cart, Checkout, Admin) + Unit (Coupon)
```

## Scelte di progetto

- **Importi in centesimi (`int`)** ovunque; la conversione avviene solo in input (admin) e output (`@money`).
- **Prezzi IVA esclusa**, IVA calcolata sul subtotale scontato. Se vendi a privati con prezzi IVA inclusa,
  imposta `SHOP_TAX_RATE=0` e inserisci i prezzi lordi (o adatta `CartTotals`).
- **Ordini immutabili**: nome, SKU, prezzo e indirizzo sono copiati nell'ordine; i prodotti usano soft delete.
- **Pagamenti** dietro l'interfaccia `PaymentGateway`: per aggiungere PayPal, Nexi, Satispay ecc. basta una nuova classe
  e una voce nel `match` di `AppServiceProvider`.

## Pagamenti con Stripe

```
SHOP_PAYMENT_DRIVER=stripe
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

Il cliente viene reindirizzato a Stripe Checkout; l'ordine passa a *pagato* tramite il webhook
`POST /webhooks/stripe` (eventi `checkout.session.completed` e `checkout.session.expired`).
In locale: `stripe listen --forward-to localhost:8000/webhooks/stripe`.

Con il driver `fake` il pagamento è sempre approvato; usa `fail@example.com` al checkout per simulare un rifiuto.

## Test

```bash
php artisan test
```

## Prossimi passi suggeriti

Varianti prodotto (taglia/colore), più immagini per prodotto, indirizzi salvati in rubrica, fatturazione elettronica
(codice fiscale/P.IVA/SDI), recensioni, wishlist, verifica email, reset password (puoi aggiungere
`laravel/fortify` per le parti di autenticazione mancanti), sitemap e meta SEO.
