<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\Contracts\PaymentService;

class OrderPaymentController extends Controller
{
    
    /**
     * @var \App\Services\Contracts\PaymentService
     */
    protected $paymentService;

    /**
     * @param \App\Services\Contracts\PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService) {
        $this->paymentService = $paymentService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Order $order)
    {
        if ($order->isPending()) {
            $this->paymentService->updateOrderStatus($order);
        }

        if ($order->isRejected() || !$order->payment) {
            $order->setStatus(Order::STATUS_CREATED);

            $this->paymentService->createPaymentRequest($order);
        }

        if ($order->isPayed() || !$order->payments->count()) {
            return redirect()->route('order.status', ['order_id' => $order->id]);
        }

        return redirect($order->payment->process_url);
    }
}
