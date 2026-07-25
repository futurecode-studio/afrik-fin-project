<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Services\ApiCredentials;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        try {
            ApiCredentials::captureBaseConfig();
            ApiCredentials::applyToConfig();
        } catch (\Throwable) {
            // migrations / install en cours
        }
    }
}
