<?php

namespace App\Providers;

use Dnetix\Redirection\PlacetoPay;
use App\Services\WebCheckoutService;
use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\PaymentService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentService::class, function ($app) {

            $placetoPay = new PlacetoPay(config('services.web_checkout'));

            return new WebCheckoutService($placetoPay);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
