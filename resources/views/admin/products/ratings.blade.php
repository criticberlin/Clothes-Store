@extends('layouts.admin')

@section('title', 'Product Ratings Dashboard')

@section('content')
<style>
/* Dynamic width classes for progress bars */
.w-0 { width: 0%; }
.w-10 { width: 10%; }
.w-20 { width: 20%; }
.w-30 { width: 30%; }
.w-40 { width: 40%; }
.w-50 { width: 50%; }
.w-60 { width: 60%; }
.w-70 { width: 70%; }
.w-80 { width: 80%; }
.w-90 { width: 90%; }
.w-100 { width: 100%; }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Product Ratings Dashboard</h1>
    </div>

    <!-- Overview Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-surface h-100">
                <div class="card-body">
                    <h5 class="card-title">Overall Rating</h5>
                    <div class="d-flex align-items-center">
                        <div class="display-4 fw-bold text-primary me-2">{{ number_format($overallAverage, 1) }}</div>
                        <div>
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($overallAverage))
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-surface h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Reviews</h5>
                    <p class="display-4 fw-bold text-primary mb-0">{{ App\Models\ProductRating::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-surface h-100">
                <div class="card-body">
                    <h5 class="card-title">Products with Reviews</h5>
                    <p class="display-4 fw-bold text-primary mb-0">
                        {{ App\Models\Product::has('ratings')->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-surface h-100">
                <div class="card-body">
                    <h5 class="card-title">Pending Approval</h5>
                    <p class="display-4 fw-bold text-primary mb-0">
                        {{ App\Models\ProductRating::where('is_approved', false)->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Rating Distribution -->
        <div class="col-md-6">
            <div class="card bg-surface h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Rating Distribution</h5>
                </div>
                <div class="card-body">
                    @for ($i = 5; $i >= 1; $i--)
                        @php 
                            $count = $ratingDistribution[$i] ?? 0;
                            $total = array_sum($ratingDistribution);
                            $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                            $widthClass = 'w-' . round($percentage / 10) * 10;
                        @endphp
                        <div class="d-flex align-items-center mb-3">
                            <div class="rating-stars me-2" style="width: 80px;">
                                {{ $i }} <i class="bi bi-star-fill text-warning"></i>
                            </div>
                            <div class="progress flex-grow-1" style="height: 12px;">
                                <div class="progress-bar bg-primary {{ $widthClass }}" role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="rating-count ms-2" style="width: 100px;">
                                <strong>{{ $count }}</strong> ({{ number_format($percentage, 1) }}%)
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        
        <!-- Top Rated Products -->
        <div class="col-md-6">
            <div class="card bg-surface h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Top Rated Products</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">View All Products</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Rating</th>
                                    <th>Reviews</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topRatedProducts as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="product-image me-2">
                                                    @if($product->photo)
                                                        <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" width="40" height="40" class="rounded">
                                                    @else
                                                        <div class="placeholder-image rounded bg-secondary" style="width: 40px; height: 40px;"></div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $product->name }}</div>
                                                    <div class="text-tertiary small">{{ $product->code }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="fw-bold me-1">{{ number_format($product->average_rating, 1) }}</span>
                                                <div>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= round($product->average_rating))
                                                            <i class="bi bi-star-fill text-warning small"></i>
                                                        @else
                                                            <i class="bi bi-star text-warning small"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $product->ratings_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3">No rated products found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reviews -->
    <div class="card bg-surface mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Recent Reviews</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRatings as $rating)
                            <tr>
                                <td>
                                    <a href="{{ route('products.details', $rating->product_id) }}" target="_blank">
                                        {{ $rating->product->name }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $rating->user->profile_photo_url }}" alt="{{ $rating->user->name }}" 
                                             class="rounded-circle me-2" width="30" height="30">
                                        {{ $rating->user->name }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold me-1">{{ $rating->rating }}</span>
                                        <div>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $rating->rating)
                                                    <i class="bi bi-star-fill text-warning small"></i>
                                                @else
                                                    <i class="bi bi-star text-warning small"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;">
                                        {{ $rating->review ?: 'No comment' }}
                                    </div>
                                </td>
                                <td>{{ $rating->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($rating->is_approved)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <form action="{{ route('admin.products.ratings.toggle', $rating->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-{{ $rating->is_approved ? 'warning' : 'success' }}">
                                                {{ $rating->is_approved ? 'Disapprove' : 'Approve' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.products.ratings.delete', $rating->id) }}" method="POST" class="ms-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this review?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">No reviews found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 