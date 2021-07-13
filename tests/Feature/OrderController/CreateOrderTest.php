<?php

namespace Tests\Feature\OrderController;

use Tests\TestCase;
use App\Models\Order;
use Illuminate\Support\Str;
use App\Services\Contracts\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateOrderTest extends TestCase
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
    public function an_user_can_create_an_order()
    {
        $this->paymentService
            ->shouldReceive('createPaymentRequest')
            ->once();

        $this->get(route('orders.create'))
            ->assertViewIs('orders.create');

        $attributes = Order::factory()->raw();

        $this->post(route('orders.store'), $attributes)
            ->assertRedirect(route('orders.show', Order::first()));

        $this->assertDatabaseCount('orders', 1);

        $this->assertDatabaseHas('orders', $attributes);
    }

    /**
     * @test
     */
    public function a_customer_name_is_required()
    {
        $attributes = Order::factory()->raw(['customer_name' => '']);

        $this->post(route('orders.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'customer_name',
            trans('validation.required', ['attribute' => 'customer name'])
        );

        $this->assertDatabaseCount('orders', 0);
    }

    /**
     * @test
     */
    public function a_customer_name_may_not_be_greater_than_80_chars()
    {
        $attributes = Order::factory()->raw(['customer_name' => Str::random(81)]);

        $this->post(route('orders.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'customer_name',
            trans('validation.max.string', ['attribute' => 'customer name', 'max' => 80])
        );

        $this->assertDatabaseCount('orders', 0);
    }

    /**
     * @test
     */
    public function a_customer_email_is_required()
    {
        $attributes = Order::factory()->raw(['customer_email' => '']);

        $this->post(route('orders.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'customer_email',
            trans('validation.required', ['attribute' => 'customer email'])
        );

        $this->assertDatabaseCount('orders', 0);
    }

    /**
     * @test
     */
    public function a_customer_email_must_be_a_valid_email()
    {
        $attributes = Order::factory()->raw(['customer_email' => '*invalid*email*']);

        $this->post(route('orders.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'customer_email',
            trans('validation.email', ['attribute' => 'customer email'])
        );

        $this->assertDatabaseCount('orders', 0);
    }

    /**
     * @test
     */
    public function a_customer_email_may_not_be_greater_than_120_chars()
    {
        $attributes = Order::factory()->raw(['customer_email' => Str::random(121)]);

        $this->post(route('orders.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'customer_email',
            trans('validation.max.string', ['attribute' => 'customer email', 'max' => 120]),
            1
        );

        $this->assertDatabaseCount('orders', 0);
    }

    /**
     * @test
     */
    public function a_customer_mobile_is_required()
    {
        $attributes = Order::factory()->raw(['customer_mobile' => '']);

        $this->post(route('orders.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'customer_mobile',
            trans('validation.required', ['attribute' => 'customer mobile'])
        );

        $this->assertDatabaseCount('orders', 0);
    }

    /**
     * @test
     */
    public function a_customer_mobile_must_be_a_valid_phone_number()
    {
        $attributes = Order::factory()->raw(['customer_mobile' => '**invalid**']);

        $this->post(route('orders.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'customer_mobile',
            trans('validation.digits', ['attribute' => 'customer mobile', 'digits' => 11])
        );

        $this->assertDatabaseCount('orders', 0);
    }

    /**
     * @test
     */
    public function a_customer_mobile_must_have_exactly_11_digits()
    {
        $attributes = Order::factory()->raw(['customer_mobile' => '1234567890']);

        $this->post(route('orders.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'customer_mobile',
            trans('validation.digits', ['attribute' => 'customer mobile', 'digits' => 11])
        );

        $attributes = Order::factory()->raw(['customer_mobile' => '123456789012']);

        $this->post(route('orders.store'), $attributes);

        $this->assertSessionHasErrorKeyValue(
            'customer_mobile',
            trans('validation.digits', ['attribute' => 'customer mobile', 'digits' => 11])

        );

        $this->assertDatabaseCount('orders', 0);
    }
}
