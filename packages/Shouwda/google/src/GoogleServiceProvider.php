<?php

namespace Shouwda\Google;

use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
                __DIR__ . '/config/google.php' => config_path('google.php'),
            ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/facebook.php', 'facebook');
        $this->app->singleton('facebook', function ($app) {
            $opay = new Facebook($app);
            return $opay;
        });
    }
}
