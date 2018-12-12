<?php

namespace Shouwda\Ecpay;

use Illuminate\Support\ServiceProvider;

class EcpayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
                __DIR__ . '/config/ecpay.php' => config_path('ecpay.php'),
            ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/ecpay.php', 'ecpay');
        $this->app->singleton('ecpay', function ($app) {
            $opay = new Ecpay($app);
            return $opay;
        });
    }
}
