<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ArticleFetchingService;

class ArticleFetchingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the service to the service container
        $this->app->singleton(ArticleFetchingService::class, function ($app) {
            return new ArticleFetchingService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
