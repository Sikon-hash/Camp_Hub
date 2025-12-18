<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Models\Order;
use App\Observers\LedgerObserver;
use App\Observers\OrderObserver;


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
    Paginator::useBootstrap();

    Order::observe(LedgerObserver::class);

    // KAMU HARUS MEMANGGIL FUNGSI INI AGAR DIJALANKAN
    $this->configureRateLimiting(); 
}

protected function configureRateLimiting()
{
    RateLimiter::for('login', function (Request $request) {
        // Catatan: Gunakan perMinute (tanpa 's') jika ingin per menit
        return Limit::perMinute(5)->by($request->ip());
    });
}
}
