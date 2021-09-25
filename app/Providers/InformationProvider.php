<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Stat\Cards;

class InformationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Cards::class,function($app){
            return new Cards($app);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
