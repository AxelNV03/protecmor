<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Alumno;
use App\Observers\AlumnoObserver;

class AppServiceProvider extends ServiceProvider
{

    
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Alumno::observe(AlumnoObserver::class);
    }
}
