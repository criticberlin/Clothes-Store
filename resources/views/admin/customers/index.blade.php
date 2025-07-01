@extends('layouts.admin')

@section('title', 'Customer Management')

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">Customer Management</h1>
            <p class="text-secondary mb-0">Manage all customers in your store</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-2"></i> Add New Customer
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <span>Customers List</span>
            <span class="badge bg-primary">{{ $customers->total() }} Customers</span>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered</th>
                            <th>Orders</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if(isset($customer->orders_count))
                                        <span class="badge bg-info">{{ $customer->orders_count }}</span>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.users.edit', $customer) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="document.getElementById('delete-customer-{{ $customer->id }}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-customer-{{ $customer->id }}" 
                                          action="{{ route('admin.users.delete', $customer) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No customers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $customers->links() }}
            </div>
        </div>
    </div>

    <div class="admin-card mt-4">
        <div class="admin-card-header">
            <span>Customer Insights</span>
        </div>
        <div class="admin-card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon purple">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stats-value">{{ $customers->total() }}</div>
                        <div class="stats-label">Total Customers</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon green">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div class="stats-value">
                            {{ $customers->where('created_at', '>=', now()->subDays(30))->count() }}
                        </div>
                        <div class="stats-label">New Customers (30 days)</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon orange">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="stats-value">
                            @php
                                $lastMonth = $customers->where('created_at', '>=', now()->subDays(60))->where('created_at', '<', now()->subDays(30))->count();
                                $thisMonth = $customers->where('created_at', '>=', now()->subDays(30))->count();
                                $growth = $lastMonth > 0 ? round(($thisMonth - $lastMonth) / $lastMonth * 100) : 0;
                            @endphp
                            {{ $growth }}%
                        </div>
                        <div class="stats-label">Customer Growth</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 