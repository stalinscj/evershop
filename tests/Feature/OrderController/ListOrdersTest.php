<?php

namespace Tests\Feature\OrderController;

use Tests\TestCase;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListOrdersTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     */
    public function an_user_can_see_all_orders()
    {
        $orders = Order::factory(5)->create();

        $response = $this->get(route('orders.index'))
            ->assertViewIs('orders.index');

        foreach ($orders as $order) {
            $response->assertSeeText($order->id)
                ->assertSeeText($order->id)
                ->assertSeeText($order->customer_name)
                ->assertSeeText($order->customer_email)
                ->assertSeeText($order->customer_mobile)
                ->assertSeeText($order->status);
        }
    }
}
