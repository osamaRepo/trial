<?php

namespace App\Providers;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\ServiceProvider;

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
    // Load API routes
        Route::middleware('api')
            ->prefix('api') // optional, all routes will start with /api
            ->group(base_path('routes/api.php'));

    }
}
