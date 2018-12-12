<?php

namespace Shouwda\EcpayInvoice;

use Illuminate\Support\ServiceProvider;

class EcpayInvoiceServiceProvider extends ServiceProvider
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
        $this->app->singleton('ecpayInvoice', function ($app) {
            $opay = new EcpayInvoice($app);
            return $opay;
        });
    }
}
