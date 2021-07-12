<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\OrderRequest;
use App\Services\Contracts\PaymentService;

class OrderController extends Controller
{
    /**
     * Undocumented variable
     *
     * @var \App\Services\Contracts\PaymentService
     */
    protected $paymentService;

    /**
     * Undocumented function
     *
     * @param \App\Services\Contracts\PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService) {
        $this->paymentService = $paymentService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\OrderRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OrderRequest $request)
    {
        $order = Order::create($request->validated());

        $this->paymentService->createPaymentRequest($order);

        return redirect()->route('orders.show', $order);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Order $order)
    {
        if ($order->isPending()) {
            $this->paymentService->updateOrderStatus($order);
        }

        return view('orders.show', compact('order'));
    }
}
