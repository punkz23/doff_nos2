<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\OnlineSite\Waybill;
use App\Observers\WaybillObserver;
class WaybillModelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Waybill::observe(WaybillObserver::class);
    }
}
