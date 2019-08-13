<?php

namespace App\Providers;

use App\OnlineCourse;
use App\PhysicalCourse;
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
