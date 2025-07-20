@extends('layouts.admin')

@section('title', __('Governorates'))
@section('description', __('Manage Egyptian governorates in the system'))

@section('content')
    <div class="admin-header">
        <div>
            <!-- Empty div for layout consistency -->
        </div>
        <div>
            <a href="{{ route('admin.shipping.governorates.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> {{ __('Add Governorate') }}
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5 class="mb-0">{{ __('Governorates List') }}</h5>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table admin-datatable">
                    <thead>
                        <tr>
                            <th>{{ __('Name (English)') }}</th>
                            <th>{{ __('Name (Arabic)') }}</th>
                            <th>{{ __('Cities') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($governorates as $governorate)
                            <tr>
                                <td>{{ $governorate->name_en }}</td>
                                <td>{{ $governorate->name_ar }}</td>
                                <td>
                                    <a href="{{ route('admin.shipping.cities', ['governorate_id' => $governorate->id]) }}" class="badge bg-primary">
                                        {{ $governorate->cities_count ?? $governorate->cities()->count() }}
                                    </a>
                                </td>
                                <td>
                                    <span class="status-badge {{ $governorate->is_active ? 'completed' : 'cancelled' }}">
                                        <i class="bi bi-{{ $governorate->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                        {{ $governorate->is_active ? __('Active') : __('Inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.shipping.governorates.edit', $governorate) }}" class="action-btn" title="{{ __('Edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.shipping.governorates.destroy', $governorate) }}" method="POST" class="d-inline delete-form">
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
                                <td colspan="5" class="text-center">{{ __('No governorates found') }}</td>
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
            form.dataset.confirmMessage = "{{ __('Are you sure you want to delete this governorate?') }}";
        });
    });
</script>
@endpush 
 