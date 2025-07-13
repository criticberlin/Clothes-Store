@extends('layouts.admin')

@section('title', 'Order Management')
@section('description', 'Manage all customer orders')

@section('content')
    <div class="admin-header">
        <div>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-funnel me-2"></i> Filter Orders
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item" href="{{ route('admin.orders.list') }}">All Orders</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.list') }}?status=pending">Pending Orders</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.list') }}?status=processing">Processing Orders</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.list') }}?status=completed">Completed Orders</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.list') }}?status=cancelled">Cancelled Orders</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <span>Orders List</span>
            <span class="badge bg-primary">{{ $orders->count() }} Orders</span>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table admin-datatable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if(isset($order->items_count))
                                        {{ $order->items_count }} items
                                    @elseif(isset($order->order_items))
                                        {{ $order->order_items->count() }} items
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $order->status === 'completed' ? 'success' : 
                                        ($order->status === 'processing' ? 'warning' : 
                                        ($order->status === 'cancelled' ? 'danger' : 'info')) 
                                    }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.details', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="admin-card mt-4">
        <div class="admin-card-header">
            <span>Order Statistics</span>
        </div>
        <div class="admin-card-body">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon green">
                            <i class="bi bi-cart"></i>
                        </div>
                        <div class="stats-value">{{ $orders->count() }}</div>
                        <div class="stats-label">Total Orders</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon orange">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="stats-value">
                            {{ $orders->where('status', 'pending')->count() }}
                        </div>
                        <div class="stats-label">Pending Orders</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon purple">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="stats-value">
                            {{ $orders->where('status', 'processing')->count() }}
                        </div>
                        <div class="stats-label">Processing Orders</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon green">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stats-value">
                            {{ $orders->where('status', 'completed')->count() }}
                        </div>
                        <div class="stats-label">Completed Orders</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 