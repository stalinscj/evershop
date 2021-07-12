<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function an_order_has_many_payments()
    {
        $order = Order::factory()->hasPayments(2)->create();

        $this->assertInstanceOf(OrderPayment::class, $order->payments->first());
    }

    /**
     * @test
     */
    public function an_order_has_a_last_payment()
    {
        $order = Order::factory()->hasPayments(2)->create();

        $lastPayment = OrderPayment::factory()
            ->for($order)
            ->create();

        $this->assertEquals($lastPayment->id, $order->payment->id);
    }

    /**
     * @test
     */
    public function it_can_set_the_status_order()
    {
        $order = Order::factory()->create();

        $order->setStatus(Order::STATUS_REJECTED);

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => Order::STATUS_REJECTED,
        ]);
    }

    /**
     * @test
     */
    public function it_cannot_set_the_status_order_with_invalid_status()
    {
        $order = Order::factory()->create();

        $order->setStatus('INVALID');

        $this->assertDatabaseMissing('orders', [
            'id'     => $order->id,
            'status' => 'INVALID',
        ]);
    }

    /**
     * @test
     */
    public function it_can_know_if_the_order_has_the_created_status()
    {
        $order = Order::factory()->create();

        $this->assertTrue($order->isCreated());

        $this->assertTrue($order->isPending());
    }

    /**
     * @test
     */
    public function it_can_know_if_the_order_has_the_payed_status()
    {
        $order = Order::factory()->payed()->create();

        $this->assertTrue($order->isPayed());
    }

    /**
     * @test
     */
    public function it_can_know_if_the_order_has_the_rejected_status()
    {
        $order = Order::factory()->rejected()->create();

        $this->assertTrue($order->isRejected());
    }
}
