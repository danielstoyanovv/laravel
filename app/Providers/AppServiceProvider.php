<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Jobs\ProcessFlight;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bindMethod([ProcessFlight::class, 'handle'], function ($job, $app) {
            return $job->handle();
        });
    }
}
