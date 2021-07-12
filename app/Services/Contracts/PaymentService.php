<?php

namespace App\Services\Contracts;

interface PaymentService
{
    /**
     * Create a payment request.
     *
     * @param \App\Models\Order $order
     * @return \App\Models\Order
     */
    public function createPaymentRequest($order);

    /**
     * Fetch the payment status for update the order status.
     *
     * @param \App\Models\Order $order
     * @return \App\Models\Order
     */
    public function updateOrderStatus($order);
}
