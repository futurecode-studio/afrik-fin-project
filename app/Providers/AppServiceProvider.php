<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Article;
use App\Services\ApiCredentials;
use App\Services\MarketsDataService;

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

        Blade::directive('plain', function ($expression) {
            return "<?php echo e(plain_text($expression)); ?>";
        });

        View::composer('partials.navbar', function ($view) {
            $headlines = cache()->remember('nav.headlines.v1', 90, function () {
                return Article::published()
                    ->orderByDesc('is_featured')
                    ->latest('published_at')
                    ->take(8)
                    ->get(['id', 'titre', 'slug', 'categorie', 'is_featured', 'published_at']);
            });

            $navMarket = cache()->remember('nav.market.strip.v2', 90, function () {
                return app(MarketsDataService::class)->navMarketPanel();
            });

            $view->with([
                'navHeadlines' => $headlines,
                'navMarket' => $navMarket,
            ]);
        });

        try {
            ApiCredentials::captureBaseConfig();
            ApiCredentials::applyToConfig();
        } catch (\Throwable) {
            // migrations / install en cours
        }
    }
}
