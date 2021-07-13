@extends('layouts.app')

@section('title', 'Order Detail')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6"> 
            <div class="card">
                <div class="card-header">Order #{{ $order->id }}</div>

                <div class="card-body">
                    <ul>
                        <li>
                            <span class="fw-bold">Customer Name:</span> {{ $order->customer_name }}
                        </li>

                        <li>
                            <span class="fw-bold">Customer Email:</span> {{ $order->customer_email }}
                        </li>

                        <li>
                            <span class="fw-bold">Customer Mobile:</span> {{ $order->customer_mobile }}
                        </li>

                        <li>
                            <span class="fw-bold">Status:</span> {{ $order->status }}
                        </li>
                    </ul>

                    @if (!$order->isPayed())
                        <form action="{{ route('orders.payments.store', $order) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-primary" id='btn-pay'>Proceed to Pay</button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
