<?php

namespace App\Providers;

use App\Models\SystemSetting;
use App\Support\CurrentShop;
use App\Support\DefaultShopProvisioner;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        try {
            Cache::remember('bootstrap.default-shop', now()->addMinutes(10), function (): bool {
                DefaultShopProvisioner::ensureDefaultShopExists();

                return true;
            });
        } catch (QueryException) {
            // The database may not be ready yet during first deployment.
        }

        View::composer(['layouts.app', 'layouts.print', 'auth.login'], function ($view) {
            $view->with('systemSettings', SystemSetting::current());
            $view->with('managedShopContext', CurrentShop::contextShop());
        });
    }
}
