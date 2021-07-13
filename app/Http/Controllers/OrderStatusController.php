<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Contracts\PaymentService;

class OrderStatusController extends Controller
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
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show()
    {
        $order = Order::find(request('order_id'));

        if ($order && $order->isPending()) {
            $this->paymentService->updateOrderStatus($order);
        }

        return view('order_status.show', compact('order'));
    }
}
