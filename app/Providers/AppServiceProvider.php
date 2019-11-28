<?php

namespace App\Providers;

use App\Edm;
use App\Plan;
use App\OnlineCourse;
use App\PhysicalCourse;
use App\CategoryProduct;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootEloquentMorphs();
    }

    private function bootEloquentMorphs()
    {
        Relation::morphMap([
            OnlineCourse::TABLE => OnlineCourse::class,
            PhysicalCourse::TABLE => PhysicalCourse::class,
            CategoryProduct::TABLE => CategoryProduct::class,
            Plan::TABLE => Plan::class,
            Edm::TABLE => Edm::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
