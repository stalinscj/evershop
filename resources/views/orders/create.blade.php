@extends('layouts.app')

@section('title', 'Generate Order')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            <form action="{{ route('orders.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" name="customer_name" value="{{ old('customer_name') }}" required autofocus>
                    @error('customer_name')
                        <span class="invalid-feedback d-inline">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="customer_email" class="form-label">Customer Email</label>
                    <input type="email" class="form-control" name="customer_email" value="{{ old('customer_email') }}" required>
                    @error('customer_email')
                        <span class="invalid-feedback d-inline">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="customer_mobile" class="form-label">Customer Mobile</label>
                    <input type="text" class="form-control" name="customer_mobile" value="{{ old('customer_mobile') }}" required>
                    @error('customer_mobile')
                        <span class="invalid-feedback d-inline">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="customer_mobile" class="form-label">Description</label>
                    <input type="text" class="form-control" value="Product Description" disabled>
                </div>

                <div class="mb-3">
                    <label for="customer_mobile" class="form-label">Product Price</label>
                    <input type="text" class="form-control" value="120 USD" disabled>
                </div>

                <button type="submit" class="btn btn-primary">Generate Order</button>
            </form>
        </div>
    </div>
@endsection
