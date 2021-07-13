<?php

namespace Tests\Feature\OrderStatusController;

use Tests\TestCase;
use App\Models\Order;
use App\Services\Contracts\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckOrderStatusTest extends TestCase
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
    public function an_user_can_check_the_order_status_by_order_id()
    {
        $this->paymentService
            ->shouldReceive('updateOrderStatus');

        $orders = Order::factory(3)
            ->sequence(
                [],
                ['status' => Order::STATUS_PAYED],
                ['status' => Order::STATUS_REJECTED]
            )
            ->hasPayment()
            ->create();

        $orders->each(function ($order) {
            $this->get(route('order.status', ['order_id' => $order->id]))
                ->assertViewIs('order_status.show')
                ->assertViewHas(compact('order'))
                ->assertSeeText($order->status);
        });
    }

    /**
     * @test
     */
    public function an_user_cannot_check_the_order_status_with_invalid_order_id()
    {
        $notFoundText = 'No Order found.';

        $this->get(route('order.status', ['order_id' => 'INVALID']))
            ->assertViewIs('order_status.show')
            ->assertSeeText($notFoundText);

        $this->get(route('order.status'))
            ->assertViewIs('order_status.show')
            ->assertSeeText($notFoundText);
    }

    /**
     * @test
     */
    public function it_should_check_for_status_updates_when_the_order_status_is_created()
    {
        $this->paymentService
            ->shouldReceive('updateOrderStatus')
            ->once();

        $order = Order::factory()->hasPayment()->create();

        $this->get(route('order.status', ['order_id' => $order->id]));
    }

    /**
     * @test
     */
    public function it_should_not_check_for_status_updates_when_the_order_status_is_not_created()
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
            $this->get(route('order.status', ['order_id' => $order->id]));
        });
    }

    /**
     * @test
     */
    public function an_user_can_see_the_button_for_pay_an_order_not_payed()
    {
        $this->paymentService
            ->shouldReceive('updateOrderStatus');

        $order = Order::factory()->create();

        $this->get(route('order.status', ['order_id' => $order->id]))
            ->assertViewIs('order_status.show')
            ->assertSee('btn-pay');

        $order = Order::factory()
            ->rejected()
            ->create();

        $this->get(route('order.status', ['order_id' => $order->id]))
            ->assertViewIs('order_status.show')
            ->assertSee('btn-pay');
    }

    /**
     * @test
     */
    public function an_user_cannot_see_the_button_for_pay_an_order_payed()
    {
        $this->paymentService
            ->shouldNotReceive('updateOrderStatus');

        $order = Order::factory()
            ->payed()
            ->create();

        $this->get(route('order.status', ['order_id' => $order->id]))
            ->assertViewIs('order_status.show')
            ->assertDontSee('btn-pay');
    }
}
