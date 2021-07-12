@extends('layouts.app')

@section('title', 'Orders List')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Customer Email</th>
                            <th scope="col">Customer Mobile</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <th scope="row"><a href="{{ route('orders.show', $order) }}">{{ $order->id }}</a></th>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->customer_email }}</td>
                                <td>{{ $order->customer_mobile }}</td>
                                <td>{{ $order->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No data available, please <a href="{{ route('orders.create') }}">Generate</a> some orders.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex">
                    <div class="mx-auto">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
