@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">Dashboard</h1>
            <p class="text-secondary mb-0">Welcome back, {{ Auth::user()->name }}</p>
        </div>
        <div>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="reportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-calendar3 me-2"></i> {{ date('F Y') }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="reportDropdown">
                    <li><a class="dropdown-item" href="#">Last 7 days</a></li>
                    <li><a class="dropdown-item" href="#">Last 30 days</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">Last Month</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon purple">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stats-value">{{ $userCount }}</div>
                <div class="stats-label">Total Users</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon green">
                    <i class="bi bi-grid"></i>
                </div>
                <div class="stats-value">{{ $productCount }}</div>
                <div class="stats-label">Total Products</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon orange">
                    <i class="bi bi-cart"></i>
                </div>
                <div class="stats-value">{{ $orderCount }}</div>
                <div class="stats-label">Total Orders</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon purple">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stats-value">{{ $pendingOrderCount }}</div>
                <div class="stats-label">Pending Orders</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Sales Chart -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>Recent Sales</span>
                </div>
                <div class="admin-card-body">
                    <div class="chart-container" style="height: 300px;">
                        <div class="text-center py-5">
                            <i class="bi bi-graph-up fs-1 text-primary"></i>
                            <p class="mt-3">Sales chart visualization would appear here</p>
                            <p class="text-muted">Total Revenue: ${{ number_format($revenue, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Support Tickets -->
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>Recent Support Tickets</span>
                    <a href="{{ route('admin.support.index') }}" class="text-primary">View All</a>
                </div>
                <div class="admin-card-body">
                    @php
                        $hasTickets = false;
                        try {
                            $recentTickets = \App\Models\SupportTicket::with('user')->latest()->limit(5)->get();
                            $hasTickets = true;
                        } catch (\Exception $e) {
                            $hasTickets = false;
                        }
                    @endphp

                    @if($hasTickets && isset($recentTickets) && $recentTickets->count() > 0)
                        <div class="list-group">
                            @foreach($recentTickets as $ticket)
                                <a href="{{ route('admin.support.show', $ticket) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $ticket->subject }}</h6>
                                        <small class="text-muted">{{ $ticket->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1 text-truncate">{{ $ticket->message }}</p>
                                    <small class="text-muted">From: {{ $ticket->user->name }}</small>
                                    <span class="badge bg-{{ $ticket->status === 'open' ? 'danger' : ($ticket->status === 'pending' ? 'warning' : 'success') }} float-end">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-ticket-perforated fs-1 text-secondary mb-3"></i>
                            <p class="mb-0">No recent support tickets</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>Recent Orders</span>
                    <a href="{{ route('admin.orders.list') }}" class="text-primary">View All</a>
                </div>
                <div class="admin-card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $recentOrders = \App\Models\Order::with('user')->latest()->limit(5)->get();
                                @endphp
                                
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
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
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .quick-link-card {
        display: block;
        padding: 1.25rem;
        border-radius: 0.5rem;
        background-color: var(--surface-alt);
        text-decoration: none;
        color: var(--text-primary);
        transition: all 0.2s ease;
    }
    
    .quick-link-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .quick-link-card .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .quick-link-card .icon-wrapper.purple {
        background-color: rgba(127, 90, 240, 0.2);
        color: var(--primary-light);
    }
    
    .quick-link-card .icon-wrapper.green {
        background-color: rgba(44, 182, 125, 0.2);
        color: var(--secondary-light);
    }
    
    .quick-link-card .icon-wrapper.orange {
        background-color: rgba(255, 127, 80, 0.2);
        color: var(--accent);
    }
    
    .quick-link-card .icon-wrapper.blue {
        background-color: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
    }
    
    .quick-link-card h6 {
        margin-bottom: 0.5rem;
    }
    
    .quick-link-card p {
        margin-bottom: 0;
    }
</style> 