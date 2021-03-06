<?php

namespace App\Providers;

use App\Admin;
use Carbon\Carbon;
use App\Policies\AdminPolicy;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Admin::class => AdminPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(Carbon::now()->addHours(24));

        Passport::refreshTokensExpireIn(Carbon::now()->addHours(72));

        Passport::tokensCan([
			'product'=>'create update delete product',
			'user-product'=>'create update user product',
			'order'=>'update',
			'tag'=>'create update delete',
            'message'=>'get update delete',
            'company'=>'create update delete',
            'article'=>'create update delete',
            'promocode'=>'create update delete',
            'notificationMessage'=>'create update delete',
            'edm'=>'create update delete',
            'user'=>'update',
            'event'=>'create update delete',
            ]);
            
        Gate::define('permission', 'App\Policies\AdminPolicy@permission');
    }
}
