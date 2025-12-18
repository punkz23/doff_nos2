<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Blade;
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

        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
            \URL::forceRootUrl(\Config::get('app.url'));
        }


        Blade::if('has_doff_account', function () {
            return auth()->user()->contact->doff_account!=null;
        });

        

    }
}
