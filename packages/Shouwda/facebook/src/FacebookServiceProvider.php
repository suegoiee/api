<?php

namespace Shouwda\Facebook;

use Illuminate\Support\ServiceProvider;

class FacebookServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
                __DIR__ . '/config/facebook.php' => config_path('facebook.php'),
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
