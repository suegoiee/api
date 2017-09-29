<?php

namespace Shouwda\Allpay;

use Illuminate\Support\ServiceProvider;

class AllpayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
                __DIR__ . '/config/allpay.php' => config_path('allpay.php'),
            ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/allpay.php', 'allpay');
        $this->app->singleton('allpay', function ($app) {
            $opay = new Allpay($app);
            return $opay;
        });
    }
}
