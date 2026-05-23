<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

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
        // Strict mode Eloquent di luar production: tangkap lazy loading & missing attribute lebih awal
        Model::shouldBeStrict(! $this->app->isProduction());
    }
}
