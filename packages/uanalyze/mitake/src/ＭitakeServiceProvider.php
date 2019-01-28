<?php
namespace Uanalyze\Mitake;

use Illuminate\Support\ServiceProvider;

class MitakeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/mitake.php' => config_path('mitake.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/mitake.php', 'mitake'
        );

        $this->app->singleton('mitake', function () {
            return new Mitake;
        });
    }
}
