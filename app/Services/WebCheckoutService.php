<?php

namespace App\Services;

use App\Models\Order;
use Dnetix\Redirection\PlacetoPay;
use Dnetix\Redirection\Entities\Status;
use App\Services\Contracts\PaymentService;

class WebCheckoutService implements PaymentService
{
    /**
     * @var \Dnetix\Redirection\PlacetoPay
     */
    protected $placetopay;

    /**
     * @param \Dnetix\Redirection\PlacetoPay
     */
    public function __construct(PlacetoPay $placetoPay)
    {
        $this->placetopay = $placetoPay;
    }

    /**
     * Create a payment request.
     *
     * @param \App\Models\Order $order
     * @return \App\Models\Order
     */
    public function createPaymentRequest($order)
    {
        $payload = $this->makePayload($order);

        $response = $this->placetopay->request($payload);

        if (!$response->isSuccessful()) {
            session()->flash('danger_message', 'Error: Cannot proceed to pay at this time.');

            return $order;
        }

        $order->payments()
            ->create([
                'request_id'  => $response->requestId(),
                'process_url' => $response->processUrl(),
            ]);

        return $order;
    }

    /**
     * Fetch the payment status for update the order status.
     *
     * @param \App\Models\Order $order
     * @return \App\Models\Order
     */
    public function updateOrderStatus($order)
    {
        if (!$order->payment) {
            return $order;
        }

        $response = $this->placetopay->query($order->payment->request_id);

        if (!$response->isSuccessful()) {
            session()->flash('danger_message', 'Error: Cannot check the payment status at this time.');

            return $order;
        }

        if ($response->isApproved()) {
            $order->setStatus(Order::STATUS_PAYED);
        } else if ($response->status()->status() == Status::ST_REJECTED) {
            $order->setStatus(Order::STATUS_REJECTED);
        }

        return $order;
    }

    /**
     * Make payload for create a payment request
     *
     * @param \App\Models\Order $order
     * @return array
     */
    protected function makePayload($order)
    {
        $reference =  $order->id;

        $payload = [
            'payment' => [
                'reference'   => $reference,
                'description' => 'Product Description',
                'amount'      => [
                    'currency' => 'USD',
                    'total'    => 120,
                ],
            ],
            'expiration' => date('c', strtotime('+10 minutes')),
            'returnUrl'  => route('order.status', ['order_id' => $reference]),
        ];

        return $payload;
    }
}
