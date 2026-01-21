<?php

namespace App\Providers;

use App\Models\TravelerTicket;
use App\Observers\TravelerTicketObserver;
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
        TravelerTicket::observe(TravelerTicketObserver::class);
    }
}
