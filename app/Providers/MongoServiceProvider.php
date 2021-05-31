<?php

namespace App\Providers;

use MongoDB\Client;
use Illuminate\Support\ServiceProvider;

class MongoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            return new Client("mongodb://localhost:27017");
        });
    }
}
