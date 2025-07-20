@extends('layouts.admin')

@section('title', __('Promo Codes'))
@section('description', __('Manage discount promo codes for your store'))

@section('content')
    <div class="admin-header">
        <div>
            <!-- Empty div for layout consistency -->
        </div>
        <div>
            <a href="{{ route('admin.promo-codes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> {{ __('Add Promo Code') }}
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5 class="mb-0">{{ __('Promo Codes List') }}</h5>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table admin-datatable">
                    <thead>
                        <tr>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Discount') }}</th>
                            <th>{{ __('Usage') }}</th>
                            <th>{{ __('Valid Period') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promoCodes as $promoCode)
                            <tr>
                                <td><strong>{{ $promoCode->code }}</strong></td>
                                <td>{{ $promoCode->description ?? '-' }}</td>
                                <td>
                                    @if($promoCode->type == 'percentage')
                                        {{ $promoCode->value }}%
                                        @if($promoCode->max_discount_amount)
                                            <small class="d-block text-muted">({{ __('Max') }} {{ format_price($promoCode->max_discount_amount) }})</small>
                                        @endif
                                    @else
                                        {{ format_price($promoCode->value) }}
                                    @endif
                                    
                                    @if($promoCode->min_order_amount)
                                        <small class="d-block text-muted">{{ __('Min Order') }}: {{ format_price($promoCode->min_order_amount) }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $promoCode->usage_count }} 
                                    @if($promoCode->usage_limit)
                                        / {{ $promoCode->usage_limit }}
                                    @endif
                                    
                                    @if($promoCode->usage_count > 0)
                                        <form action="{{ route('admin.promo-codes.reset-usage', $promoCode) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary ms-2" title="{{ __('Reset Usage') }}">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    @if($promoCode->start_date && $promoCode->end_date)
                                        {{ $promoCode->start_date->format('M d, Y') }} - {{ $promoCode->end_date->format('M d, Y') }}
                                    @elseif($promoCode->start_date)
                                        {{ __('From') }} {{ $promoCode->start_date->format('M d, Y') }}
                                    @elseif($promoCode->end_date)
                                        {{ __('Until') }} {{ $promoCode->end_date->format('M d, Y') }}
                                    @else
                                        {{ __('No Expiration') }}
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge {{ $promoCode->is_active ? 'completed' : 'cancelled' }}">
                                        <i class="bi bi-{{ $promoCode->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                        {{ $promoCode->is_active ? __('Active') : __('Inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <form action="{{ route('admin.promo-codes.toggle', $promoCode) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="action-btn" title="{{ $promoCode->is_active ? __('Deactivate') : __('Activate') }}">
                                                <i class="bi bi-{{ $promoCode->is_active ? 'toggle-on text-success' : 'toggle-off text-danger' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <a href="{{ route('admin.promo-codes.edit', $promoCode) }}" class="action-btn" title="{{ __('Edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.promo-codes.destroy', $promoCode) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn text-danger" title="{{ __('Delete') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('No promo codes found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add no-pagination class if Laravel pagination exists
        const table = document.querySelector('.admin-datatable');
        if (table) {
            table.classList.add('no-pagination');
        }
        
        // Delete confirmation is now handled by admin-tables.js
        document.querySelectorAll('.delete-form').forEach(form => {
            form.dataset.confirmMessage = "{{ __('Are you sure you want to delete this promo code?') }}";
        });
    });
</script>
@endpush 
 