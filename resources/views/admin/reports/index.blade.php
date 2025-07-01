@extends('layouts.admin')

@section('title', 'Reports & Analytics')

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">Reports & Analytics</h1>
            <p class="text-secondary mb-0">View detailed sales and performance reports</p>
        </div>
        <div>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="reportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-download me-2"></i> Export Report
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="reportDropdown">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-excel me-2"></i> Export as Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-pdf me-2"></i> Export as PDF</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-text me-2"></i> Export as CSV</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon purple">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stats-value">
                    ${{ number_format($monthlySales->sum('total'), 2) }}
                </div>
                <div class="stats-label">Total Revenue</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon green">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stats-value">
                    @php
                        $currentMonth = $monthlySales->where('month', now()->month)->where('year', now()->year)->first();
                        $lastMonth = $monthlySales->where('month', now()->subMonth()->month)->where('year', now()->subMonth()->year)->first();
                        
                        $currentMonthTotal = $currentMonth ? $currentMonth->total : 0;
                        $lastMonthTotal = $lastMonth ? $lastMonth->total : 0;
                        
                        $growth = $lastMonthTotal > 0 ? round(($currentMonthTotal - $lastMonthTotal) / $lastMonthTotal * 100) : 0;
                    @endphp
                    {{ $growth }}%
                </div>
                <div class="stats-label">Monthly Growth</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon orange">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="stats-value">
                    @php
                        $totalOrders = $monthlySales->count();
                    @endphp
                    {{ $totalOrders }}
                </div>
                <div class="stats-label">Total Orders</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon green">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stats-value">
                    @php
                        $avgOrderValue = $totalOrders > 0 ? number_format($monthlySales->sum('total') / $totalOrders, 2) : 0;
                    @endphp
                    ${{ $avgOrderValue }}
                </div>
                <div class="stats-label">Avg. Order Value</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Monthly Sales Chart -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>Monthly Sales</span>
                </div>
                <div class="admin-card-body">
                    <div class="chart-container" style="height: 300px;">
                        <div class="text-center py-5">
                            <i class="bi bi-bar-chart-line fs-1 text-primary"></i>
                            <p class="mt-3">Monthly sales chart visualization would appear here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>Top Products</span>
                </div>
                <div class="admin-card-body">
                    <div class="list-group">
                        @forelse($topProducts as $product)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                    <small class="text-muted">Product ID: {{ $product->id }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $product->total_sold }} sold</span>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="mb-0">No product data available</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Sales Table -->
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>Monthly Sales Data</span>
                </div>
                <div class="admin-card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Year</th>
                                    <th>Total Sales</th>
                                    <th>Growth</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $previousTotal = 0;
                                @endphp
                                @forelse($monthlySales as $sale)
                                    <tr>
                                        <td>{{ date('F', mktime(0, 0, 0, $sale->month, 1)) }}</td>
                                        <td>{{ $sale->year }}</td>
                                        <td>${{ number_format($sale->total, 2) }}</td>
                                        <td>
                                            @php
                                                $growth = $previousTotal > 0 ? round(($sale->total - $previousTotal) / $previousTotal * 100) : 0;
                                                $previousTotal = $sale->total;
                                            @endphp
                                            <span class="badge bg-{{ $growth >= 0 ? 'success' : 'danger' }}">
                                                {{ $growth }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No sales data available</td>
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