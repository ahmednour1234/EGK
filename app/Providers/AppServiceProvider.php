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

        \Illuminate\Database\Eloquent\Model::retrieved(function ($model) {
            foreach ($model->getAttributes() as $key => $value) {
                if (is_string($value)) {
                    $model->setAttribute($key, mb_convert_encoding($value, 'UTF-8', 'UTF-8//IGNORE'));
                }
            }
        });
    }
}
