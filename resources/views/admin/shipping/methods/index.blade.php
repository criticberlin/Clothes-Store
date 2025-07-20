@extends('layouts.admin')

@section('title', __('Shipping Methods'))
@section('description', __('Manage shipping methods available in your store'))

@section('content')
    <div class="admin-header">
        <div>
            <!-- Empty div for layout consistency -->
        </div>
        <div>
            <a href="{{ route('admin.shipping.methods.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> {{ __('Add Shipping Method') }}
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5 class="mb-0">{{ __('Shipping Methods List') }}</h5>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table admin-datatable">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Cost') }}</th>
                            <th>{{ __('Delivery Time') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shippingMethods as $method)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-truck me-2 fs-5"></i>
                                        <strong>{{ $method->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $method->description ?? '-' }}</td>
                                <td>
                                    @if($method->cost > 0)
                                        {{ format_price($method->cost) }}
                                    @else
                                        <span class="text-success">{{ __('Free') }}</span>
                                    @endif
                                </td>
                                <td>{{ $method->estimated_delivery_time }}</td>
                                <td>
                                    <span class="status-badge {{ $method->is_active ? 'completed' : 'cancelled' }}">
                                        <i class="bi bi-{{ $method->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                        {{ $method->is_active ? __('Active') : __('Inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <form action="{{ route('admin.shipping.methods.toggle', $method) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="action-btn" title="{{ $method->is_active ? __('Deactivate') : __('Activate') }}">
                                                <i class="bi bi-{{ $method->is_active ? 'toggle-on text-success' : 'toggle-off text-danger' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <a href="{{ route('admin.shipping.methods.edit', $method) }}" class="action-btn" title="{{ __('Edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.shipping.methods.delete', $method) }}" method="POST" class="d-inline delete-form">
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
                                <td colspan="6" class="text-center">{{ __('No shipping methods found') }}</td>
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
        // Delete confirmation is now handled by admin-tables.js
        document.querySelectorAll('.delete-form').forEach(form => {
            form.dataset.confirmMessage = "{{ __('Are you sure you want to delete this shipping method?') }}";
        });
    });
</script>
@endpush 
 