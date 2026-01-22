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
                    $result = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
                    $model->setAttribute($key, $result !== false ? $result : preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value));
                }
            }
        });
    }
}
