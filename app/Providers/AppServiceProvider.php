<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // patch utk manifest json not found
        // sumber : https://stackoverflow.com/questions/45153738/the-mix-manifest-does-not-exist-when-it-does-exist

        $this->app->bind('path.public', function()
        {
            return base_path();
        });
    }
}
