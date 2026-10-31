# Crea un nuovo progetto Laravel e ci applica sopra lo scheletro e-commerce (versione Windows).
#
# Uso (dal Prompt dei comandi o PowerShell, nella cartella estratta):
#     install.bat mio-shop
# oppure:
#     powershell -ExecutionPolicy Bypass -File install.ps1 mio-shop
#
# Requisiti: PHP >= 8.2 e Composer nel PATH (es. installati con Laravel Herd o XAMPP + Composer).

param(
    [string]$Target = "shop"
)

$ErrorActionPreference = "Stop"
$OverlayDir = $PSScriptRoot

function Run($exe, [string[]]$arguments) {
    & $exe @arguments
    if ($LASTEXITCODE -ne 0) {
        throw "Comando fallito: $exe $($arguments -join ' ')"
    }
}

# Controlli preliminari
foreach ($cmd in @("php", "composer")) {
    if (-not (Get-Command $cmd -ErrorAction SilentlyContinue)) {
        Write-Host "ERRORE: '$cmd' non trovato nel PATH." -ForegroundColor Red
        Write-Host "Installa PHP e Composer (il modo piu semplice: https://herd.laravel.com/windows) e riapri il terminale."
        exit 1
    }
}

if (Test-Path $Target) {
    Write-Host "La cartella '$Target' esiste gia. Scegli un altro nome." -ForegroundColor Red
    exit 1
}

Write-Host "-> Creo il progetto Laravel in '$Target'..." -ForegroundColor Cyan
Run "composer" @("create-project", "laravel/laravel", $Target, "--no-interaction")

Write-Host "-> Copio i file dell'e-commerce..." -ForegroundColor Cyan
foreach ($dir in @("app", "bootstrap", "config", "database", "resources", "routes", "tests")) {
    Copy-Item -Path (Join-Path $OverlayDir "$dir\*") -Destination (Join-Path $Target $dir) -Recurse -Force
}

Remove-Item -ErrorAction SilentlyContinue (Join-Path $Target "tests\Feature\ExampleTest.php")
Remove-Item -ErrorAction SilentlyContinue (Join-Path $Target "tests\Unit\ExampleTest.php")
Remove-Item -ErrorAction SilentlyContinue (Join-Path $Target "resources\views\welcome.blade.php")

Set-Location $Target

Write-Host "-> Configuro .env..." -ForegroundColor Cyan
$envFile = ".env"
$content = Get-Content $envFile -Raw
$content = $content -replace "(?m)^MAIL_MAILER=.*$", "MAIL_MAILER=log"
$content = $content -replace "(?m)^APP_LOCALE=.*$", "APP_LOCALE=it"
$content += @"

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
"@
# UTF-8 senza BOM (il BOM rompe la lettura del .env)
[System.IO.File]::WriteAllText((Join-Path (Get-Location) $envFile), $content, (New-Object System.Text.UTF8Encoding $false))

Write-Host "-> Migrazioni e dati demo..." -ForegroundColor Cyan
Run "php" @("artisan", "migrate:fresh", "--seed", "--force")
Run "php" @("artisan", "storage:link")

Write-Host "-> Eseguo i test..." -ForegroundColor Cyan
& php artisan test

Write-Host ""
Write-Host "Fatto! Avvia il sito con:" -ForegroundColor Green
Write-Host "    cd $Target"
Write-Host "    php artisan serve"
Write-Host "  e in un secondo terminale (per le email):"
Write-Host "    php artisan queue:work"
Write-Host ""
Write-Host "Poi apri http://localhost:8000"
Write-Host "  Admin:   admin@example.com   / password  -> /admin"
Write-Host "  Cliente: cliente@example.com / password"
Write-Host "  Coupon:  BENVENUTO10 (-10%), SCONTO5 (-5 euro sopra 30)"
