<?php

namespace App\Providers;

use App\Models\SystemSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer(['layouts.app', 'layouts.print', 'auth.login'], function ($view) {
            $view->with('systemSettings', SystemSetting::current());
        });
    }
}
