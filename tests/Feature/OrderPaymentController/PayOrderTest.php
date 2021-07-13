<?php

namespace Tests\Feature\OrderPaymentController;

use Tests\TestCase;
use App\Models\Order;
use App\Services\Contracts\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PayOrderTest extends TestCase
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
    public function an_user_can_proceed_to_pay_his_pending_order_checking_for_status_updates_before()
    {
        $this->paymentService
            ->shouldReceive('updateOrderStatus')
            ->once();

        $order = Order::factory()
            ->hasPayment()
            ->create();

        $this->post(route('orders.payments.store', $order))
            ->assertRedirect($order->payment->process_url);
    }

    /**
     * @test
     */
    public function an_user_can_proceed_to_pay_his_rejected_order_creating_a_new_payment_before()
    {
        $this->paymentService
            ->shouldReceive('createPaymentRequest')
            ->once();

        $order = Order::factory()
            ->rejected()
            ->hasPayment()
            ->create();

        $this->post(route('orders.payments.store', $order))
            ->assertRedirect($order->payment->process_url);
    }

    /**
     * @test
     */
    public function an_user_can_proceed_to_pay_his_order_without_payment_creating_a_new_payment_before()
    {
        $this->paymentService
            ->shouldReceive('createPaymentRequest', 'updateOrderStatus')
            ->once();

        $order = Order::factory()->create();

        $this->post(route('orders.payments.store', $order))
            ->assertRedirect();
    }

    /**
     * @test
     */
    public function an_user_cannot_proceed_to_pay_his_order_if_it_is_already_payed()
    {
        $this->paymentService
            ->shouldNotReceive('createPaymentRequest', 'updateOrderStatus');

        $order = Order::factory()
            ->payed()
            ->hasPayment()
            ->create();

        $this->post(route('orders.payments.store', $order))
            ->assertRedirect(route('order.status', ['order_id' => $order->id]));
    }

    /**
     * @test
     */
    public function an_user_cannot_proceed_to_pay_his_order_if_it_is_not_have_a_payment()
    {
        $this->paymentService
            ->shouldReceive('createPaymentRequest')
            ->andReturn(null);

        $order = Order::factory()
            ->rejected()
            ->create();

        $this->post(route('orders.payments.store', $order))
            ->assertRedirect(route('order.status', ['order_id' => $order->id]));
    }
}
