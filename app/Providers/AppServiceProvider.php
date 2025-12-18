<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

// Models
use App\Models\Order;
use App\Models\Product;

// Observers
use App\Observers\LedgerObserver;
use App\Observers\ProductObserver;
use App\Observers\OrderObserver; // Jika dipakai

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
        // 1. Setup Pagination (Supaya tampilan halaman rapi)
        Paginator::useBootstrap();

        // 2. Setup Observer (Mata-mata Blockchain/Ledger)
        // Pastikan model yang diamati sesuai dengan Observer-nya
        Order::observe(LedgerObserver::class);
        Product::observe(ProductObserver::class);

        // 3. Setup Rate Limiting (Keamanan Brute Force)
        $this->configureRateLimiting();
    }

    /**
     * Konfigurasi Rate Limiter kustom
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}