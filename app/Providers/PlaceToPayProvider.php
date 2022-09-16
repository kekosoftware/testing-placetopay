<?php

namespace App\Providers;

use Dnetix\Redirection\PlacetoPay;
use Illuminate\Support\ServiceProvider;

class PlaceToPayProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PlacetoPay::class, function ($app) {
            return new PlacetoPay([
                'login' => env('P2P_LOGIN'),
                'tranKey' => env('P2P_TRAN_KEY'),
                'url' => env('P2P_TRAN_URL'),
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     *
     */
    public function boot()
    {
        //
    }
}
