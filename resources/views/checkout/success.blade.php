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
                                <span class="fw-bold">{{ $order->id }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Order Date:</span>
                                <span>{{ $order->created_at->format('F d, Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Order Status:</span>
                                <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Payment Status:</span>
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Shipping Address:</span>
                                <span class="text-end">
                                    @if($order->address)
                                        {{ $order->address->full_name }}<br>
                                        {{ $order->address->street_address }}<br>
                                        {{ $order->address->city->name }}, 
                                        {{ $order->address->governorate->name }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Payment Method:</span>
                                <span>{{ $order->paymentMethod ? $order->paymentMethod->name : 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Shipping Method:</span>
                                <span>{{ $order->shippingMethod ? $order->shippingMethod->name : 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping Cost:</span>
                                <span>${{ number_format($order->shipping_cost, 2) }}</span>
                            </div>
                            @if($order->payment_fee > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Payment Fee:</span>
                                <span>${{ number_format($order->payment_fee, 2) }}</span>
                            </div>
                            @endif
                            @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span class="text-success">-${{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between fw-bold mt-2 pt-2 border-top">
                                <span>Total:</span>
                                <span>${{ number_format($order->total, 2) }}</span>
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
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->product->name }}
                                                    @if($item->color)
                                                        <br><small class="text-muted">Color: {{ $item->color->name }}</small>
                                                    @endif
                                                    @if($item->size)
                                                        <br><small class="text-muted">Size: {{ $item->size->name }}</small>
                                                    @endif
                                                </td>
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