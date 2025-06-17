@extends('layouts.master')

@section('title', 'Order Placed Successfully')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="mb-4">Thank You for Your Order!</h2>
                    <p class="lead mb-4">Your order has been placed successfully and is being processed.</p>
                    
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span>Order ID:</span>
                                <span class="fw-bold">{{ $lastOrder->id }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Order Date:</span>
                                <span>{{ $lastOrder->created_at->format('F d, Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Order Status:</span>
                                <span class="badge bg-warning text-dark">{{ ucfirst($lastOrder->status) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Shipping Address:</span>
                                <span>{{ $lastOrder->shipping_address }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Payment Method:</span>
                                <span>{{ ucfirst($lastOrder->payment_method) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span>${{ number_format($lastOrder->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Order Items</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lastOrder->items as $item)
                                            <tr>
                                                <td>{{ $item->product->name }}</td>
                                                <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-primary me-2">View All Orders</a>
                        <a href="{{ route('products.list') }}" class="btn btn-outline-secondary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection