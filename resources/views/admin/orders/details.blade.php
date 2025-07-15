@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">Order #{{ $order->id }}</h1>
            <p class="text-secondary mb-0">{{ $order->created_at->format('F d, Y h:i A') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.list') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i> Back to Orders
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>Order Summary</span>
                </div>
                <div class="admin-card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Status:</span>
                        <span class="badge bg-{{ 
                            $order->status === 'completed' ? 'success' : 
                            ($order->status === 'processing' ? 'warning' : 
                            ($order->status === 'cancelled' ? 'danger' : 'info')) 
                        }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Payment Status:</span>
                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->payment_status ?? 'pending') }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Payment Method:</span>
                        <span>{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Subtotal:</span>
                        <span class="price-display" data-base-price="{{ $order->subtotal ?? $order->total_amount }}">{{ format_price($order->subtotal ?? $order->total_amount) }}</span>
                    </div>
                    @if(isset($order->discount) && $order->discount > 0)
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Discount:</span>
                        <span class="price-display" data-base-price="{{ -$order->discount }}">{{ format_price(-$order->discount) }}</span>
                    </div>
                    @endif
                    @if(isset($order->tax) && $order->tax > 0)
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Tax:</span>
                        <span class="price-display" data-base-price="{{ $order->tax }}">{{ format_price($order->tax) }}</span>
                    </div>
                    @endif
                    @if(isset($order->shipping_cost) && $order->shipping_cost > 0)
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Shipping:</span>
                        <span class="price-display" data-base-price="{{ $order->shipping_cost }}">{{ format_price($order->shipping_cost) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-3 fw-bold">
                        <span>Total:</span>
                        <span class="price-display" data-base-price="{{ $order->total_amount }}">{{ format_price($order->total_amount) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="admin-card mt-4">
                <div class="admin-card-header">
                    <span>Customer Information</span>
                </div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <h6>{{ $order->user->name }}</h6>
                        <p class="mb-1">{{ $order->user->email }}</p>
                        @if(isset($order->user->phone))
                        <p class="mb-1">{{ $order->user->phone }}</p>
                        @endif
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $order->user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-person me-2"></i> View Customer Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>Order Items</span>
                </div>
                <div class="admin-card-body">
                    <div class="table-responsive">
                        <table class="table admin-datatable">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(isset($item->product) && isset($item->product->image))
                                                    <div class="product-thumbnail">
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="product-img">
                                                    </div>
                                                @else
                                                    <div class="product-thumbnail">
                                                        <div class="product-img-placeholder">
                                                            <i class="bi bi-image"></i>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="ms-3">
                                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                    @if(isset($item->options))
                                                        <small class="text-muted">
                                                            @foreach(json_decode($item->options) as $key => $value)
                                                                {{ ucfirst($key) }}: {{ $value }}
                                                            @endforeach
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="price-display" data-base-price="{{ $item->price }}">{{ format_price($item->price) }}</span></td>
                                        <td>
                                            <span class="status-badge completed">
                                                <i class="bi bi-check-circle"></i> {{ $item->quantity }}
                                            </span>
                                        </td>
                                        <td><span class="price-display" data-base-price="{{ $item->price * $item->quantity }}">{{ format_price($item->price * $item->quantity) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Shipping Information -->
            <div class="admin-card mt-4">
                <div class="admin-card-header">
                    <span>Shipping Information</span>
                </div>
                <div class="admin-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Shipping Address</h6>
                            <address>
                                {{ $order->shipping_name ?? $order->user->name }}<br>
                                {{ $order->shipping_address ?? 'No address provided' }}<br>
                                {{ $order->shipping_city ?? '' }}{{ $order->shipping_city ? ',' : '' }} 
                                {{ $order->shipping_state ?? '' }} {{ $order->shipping_zip ?? '' }}<br>
                                {{ $order->shipping_country ?? '' }}
                            </address>
                        </div>
                        <div class="col-md-6">
                            <h6>Shipping Method</h6>
                            <p>{{ $order->shipping_method ?? 'Standard Shipping' }}</p>
                            
                            @if(isset($order->tracking_number))
                                <h6>Tracking Number</h6>
                                <p>{{ $order->tracking_number }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Actions -->
            <div class="admin-card mt-4">
                <div class="admin-card-header">
                    <span>Order Actions</span>
                </div>
                <div class="admin-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                                @csrf
                                @method('PATCH')
                                <div class="input-group">
                                    <select name="status" class="form-select">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary flex-grow-1">
                                    <i class="bi bi-printer me-2"></i> Print Invoice
                                </a>
                                <a href="#" class="btn btn-outline-secondary flex-grow-1">
                                    <i class="bi bi-envelope me-2"></i> Email Customer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 