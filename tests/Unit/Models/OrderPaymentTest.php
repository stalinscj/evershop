<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderPaymentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function an_order_payment_belongs_to_an_order()
    {
        $orderPayment = OrderPayment::factory()->create();

        $this->assertInstanceOf(Order::class, $orderPayment->order);
    }
}
