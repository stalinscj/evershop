@extends('layouts.app')

@section('content')
    <div class="text-center my-4">
        <p class="display-1">Evershop:</p>
        <p class="display-1">The best place to</p>
        <div class="display-1">
            <a href="{{ route('orders.create') }}">Buy</a>
        </div>
    </div>
@endsection
