@extends('layouts.app')

@section('title', "Check Order Status")

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            <form class="d-flex">
                <input class="form-control me-2" placeholder="Order ID" name="order_id" autofocus>
                <button class="btn btn-success" type="submit">Search</button>
            </form>

            <hr>

            @if ($order)
                <div class="card">
                    <div class="card-header">Order #{{ $order->id }}</div>
                    <div class="card-body">
                        <ul>
                            <li><span class="fw-bold">Status:</span> {{ $order->status }}</li>
                        </ul>
                        @if (!$order->isPayed())
                            <form action="{{ route('orders.payments.store', $order) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary" id='btn-pay'>Proceed to Pay</button>
                            </form>
                        @else
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">View Order Detail</a>
                        @endif
                    </div>
                </div>
            @else
                <span>No Order found.</span>
            @endif
        </div>
    </div>
@endsection
