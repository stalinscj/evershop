<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use App\Services\WebCheckoutService;
use App\Services\Contracts\PaymentService;

class AppServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function the_app_is_using_web_checkout_as_payment_service()
    {
        $paymentService = $this->app->make(PaymentService::class);

        $this->assertInstanceOf(WebCheckoutService::class, $paymentService);
    }
}
