<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use ReflectionClass;
use App\Models\Order;
use Dnetix\Redirection\PlacetoPay;
use App\Services\WebCheckoutService;
use Illuminate\Support\Facades\Session;
use App\Services\Contracts\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WebCheckoutServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \Mockery\Mock|PlacetoPay 
     */   
    protected $placetoPay;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->placetoPay = $this->mock(PlacetoPay::class);
    }

    /**
     * @test
     */
    public function must_implements_payment_service_interface()
    {
        $webCheckOutService = $this->createMock(WebCheckoutService::class);

        $this->assertInstanceOf(PaymentService::class, $webCheckOutService);
    }

    /**
     * @test
     */
    public function must_have_a_valid_config_defined()
    {
        $config = config('services.web_checkout');

        $this->assertIsArray($config);

        $this->assertArrayHasKey('login', $config);
        $this->assertArrayHasKey('tranKey', $config);
        $this->assertArrayHasKey('url', $config);

        $this->assertNotEmpty($config['login']);
        $this->assertNotEmpty($config['tranKey']);
        $this->assertNotEmpty($config['url']);
    }

    /**
     * @test
     */
    public function must_have_a_valid_payload_request()
    {
        $webCheckoutService = new WebCheckoutService($this->placetoPay);

        $order = Order::factory()->create();

        $reflection = new ReflectionClass(WebCheckoutService::class);
        $method = $reflection->getMethod('makePayload');
        $method->setAccessible(true);

        $payload = $method->invokeArgs($webCheckoutService, [$order]);

        $payloadExpected = [
            'payment' => [
                'reference'   => $order->id,
                'description' => 'Product Description',
                'amount'      => [
                    'currency' => 'USD',
                    'total'    => 120,
                ],
            ],
            'expiration' => date('c', strtotime('+10 minutes')),
            'returnUrl'  => route('home', ['order_id' => $order->id]),
        ];

        $this->assertEquals($payloadExpected, $payload);
    }

    /**
     * @test
     */
    public function it_can_create_payment_from_successful_request()
    {
        $this->placetoPay
            ->shouldReceive('request->isSuccessful')
            ->once()
            ->andReturn(true);

        $this->placetoPay
            ->shouldReceive('request->requestId')
            ->once()
            ->andReturn(123);

        $this->placetoPay
            ->shouldReceive('request->processUrl')
            ->once()
            ->andReturn('localhost');

        $webCheckoutService = new WebCheckoutService($this->placetoPay);

        $order = Order::factory()->create();

        $webCheckoutService->createPaymentRequest($order);

        $this->assertDatabaseCount('order_payments', 1);

        $this->assertDatabaseHas('order_payments', [
            'order_id'    => $order->id,
            'request_id'  => 123,
            'process_url' => 'localhost',
        ]);
    }

    /**
     * @test
     */
    public function it_cannot_create_payment_from_unsuccessful_request()
    {
        $this->placetoPay
            ->shouldReceive('request->isSuccessful')
            ->once()
            ->andReturn(false);

        $webCheckoutService = new WebCheckoutService($this->placetoPay);

        $order = Order::factory()->create();

        $webCheckoutService->createPaymentRequest($order);

        $this->assertDatabaseCount('order_payments', 0);

        $this->assertEquals('Error: Cannot proceed to pay at this time.', Session::get('danger_message'));
    }

    /**
     * @test
     */
    public function it_can_update_order_status_to_payed()
    {
        $this->placetoPay
            ->shouldReceive('query->isSuccessful')
            ->once()
            ->andReturn(true);

        $this->placetoPay
            ->shouldReceive('query->isApproved')
            ->once()
           ->andReturn(true);

        $webCheckoutService = new WebCheckoutService($this->placetoPay);

        $order = Order::factory()
            ->hasPayment()
            ->create();

        $webCheckoutService->updateOrderStatus($order);

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => Order::STATUS_PAYED,
        ]);
    }

    /**
     * @test
     */
    public function it_can_update_order_status_to_rejected()
    {
        $this->placetoPay
            ->shouldReceive('query->status->status')
            ->once()
            ->andReturn(Order::STATUS_REJECTED);

        $this->placetoPay
            ->shouldReceive('query->isSuccessful')
            ->once()
            ->andReturn(true);

        $this->placetoPay
            ->shouldReceive('query->isApproved')
            ->once()
            ->andReturn(false);

        $webCheckoutService = new WebCheckoutService($this->placetoPay);

        $order = Order::factory()
            ->hasPayment()
            ->create();

        $webCheckoutService->updateOrderStatus($order);

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => Order::STATUS_REJECTED,
        ]);
    }

    /**
     * @test
     */
    public function it_cannot_update_order_status_without_payments()
    {
        $this->placetoPay->shouldNotReceive('query');

        $webCheckoutService = new WebCheckoutService($this->placetoPay);

        $order = Order::factory()->create();

        $webCheckoutService->updateOrderStatus($order);

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => Order::STATUS_CREATED,
        ]);
    }

    /**
     * @test
     */
    public function it_cannot_update_order_status_with_unsuccessful_request()
    {
        $this->placetoPay
            ->shouldReceive('query->isSuccessful')
            ->once()
            ->andReturn(false);

        $webCheckoutService = new WebCheckoutService($this->placetoPay);

        $order = Order::factory()
            ->hasPayment()
            ->create();

        $webCheckoutService->updateOrderStatus($order);

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => Order::STATUS_CREATED,
        ]);

        $this->assertEquals('Error: Cannot check the payment status at this time.', Session::get('danger_message'));
    }
}
