<?php

namespace App\Providers;

use App\Helpers\Interval;
use Illuminate\Support\ServiceProvider;

class DatetimeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Interval::class, function ($app) {
            return new Interval();
        });
    }
}
