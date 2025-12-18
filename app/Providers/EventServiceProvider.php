<?php

use App\Models\Order;
use App\Observers\LedgerObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    // ...

    public function boot(): void
    {
        // Daftarkan Observer untuk Model Order
        Order::observe(LedgerObserver::class);
    }

    // ...
}