<?php

namespace Tests\Feature\OrderController;

use Tests\TestCase;
use App\Models\Order;
use App\Services\Contracts\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \Mockery\Mock|\App\Services\Contracts\PaymentService
     */   
    protected $paymentService;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->paymentService = $this->mock(PaymentService::class);
    }

    /**
     * @test
     */
    public function an_user_can_see_the_order_detail()
    {
        $order = Order::factory()->create();

        $this->get(route('orders.show', $order))
            ->assertViewIs('orders.show')
            ->assertSeeText($order->id)
            ->assertSeeText($order->customer_name)
            ->assertSeeText($order->customer_email)
            ->assertSeeText($order->customer_mobile)
            ->assertSeeText($order->status);
    }

    /**
     * @test
     */
    public function an_user_can_see_the_button_for_pay_an_order_not_payed()
    {
        $order = Order::factory()->create();

        $this->get(route('orders.show', $order))
            ->assertViewIs('orders.show')
            ->assertSee('btn-pay');

        $order = Order::factory()
            ->rejected()
            ->create();

        $this->get(route('orders.show', $order))
            ->assertViewIs('orders.show')
            ->assertSee('btn-pay');
    }

    /**
     * @test
     */
    public function an_user_cannot_see_the_button_for_pay_an_order_payed()
    {
        $order = Order::factory()
            ->payed()
            ->create();

        $this->get(route('orders.show', $order))
            ->assertViewIs('orders.show')
            ->assertDontSee('btn-pay');
    }

    /**
     * @test
     */
    public function it_should_check_for_status_updates_when_the_order_has_the_created_status()
    {
        $this->paymentService
            ->shouldReceive('updateOrderStatus')
            ->once();

        $order = Order::factory()->hasPayment()->create();

        $this->get(route('orders.show', $order));
    }

    /**
     * @test
     */
    public function it_should_not_check_for_status_updates_when_the_order_does_not_has_the_created_status()
    {
        $this->paymentService
            ->shouldNotReceive('updateOrderStatus');

        $orders = Order::factory(2)
            ->sequence(
                ['status' => Order::STATUS_PAYED],
                ['status' => Order::STATUS_REJECTED]
            )
            ->hasPayment()
            ->create();

        $orders->each(function ($order) {
            $this->get(route('orders.show', $order));
        });
    }
}
