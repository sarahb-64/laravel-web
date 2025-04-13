<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class EnvironmentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Get the current environment
        $environment = env('APP_ENV', 'local');
        
        // Get the environment-specific configuration
        $config = Config::get('environment.environments.' . $environment);
        
        // Set the environment configuration if it exists
        if ($config) {
            Config::set('environment.current', $config);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}