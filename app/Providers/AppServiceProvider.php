<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GooglePositionService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GooglePositionService::class, function ($app) {
            return new GooglePositionService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
