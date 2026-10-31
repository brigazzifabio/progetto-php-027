<?php

namespace App\Providers;

use App\Models\Order;
use App\Policies\OrderPolicy;
use App\Services\Cart\CartService;
use App\Services\Payment\FakePaymentGateway;
use App\Services\Payment\PaymentGateway;
use App\Services\Payment\StripePaymentGateway;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Un'istanza per richiesta (sicuro anche con Octane).
        $this->app->scoped(CartService::class);

        $this->app->bind(PaymentGateway::class, function () {
            return match (config('shop.payment_driver')) {
                'stripe' => new StripePaymentGateway((string) config('shop.stripe.secret')),
                default => new FakePaymentGateway,
            };
        });
    }

    public function boot(): void
    {
        Gate::policy(Order::class, OrderPolicy::class);

        Gate::define('admin', fn ($user) => $user->isAdmin());

        // @money($cents) → "€ 19,90"
        Blade::directive('money', fn (string $expression) => "<?php echo e(\\App\\Support\\Money::format((int) ({$expression}))); ?>");

        Paginator::useTailwind();

        // Numero articoli nel carrello disponibile nell'header di ogni pagina shop.
        View::composer('layouts.app', function ($view) {
            $view->with('cartCount', app(CartService::class)->count());
        });
    }
}
