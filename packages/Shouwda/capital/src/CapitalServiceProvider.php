<?php

namespace Shouwda\Capital;

use Illuminate\Support\ServiceProvider;

class CapitalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
                __DIR__ . '/config/capital.php' => config_path('capital.php'),
            ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/capital.php', 'capital');
        $this->app->singleton('capital', function ($app) {
            $opay = new Capital($app);
            return $opay;
        });
    }
}
