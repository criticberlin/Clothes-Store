@extends('layouts.admin')

@section('title', __('Cities'))
@section('description', __('Manage Egyptian cities in the system'))

@section('content')
    <div class="admin-header">
        <div>
            <form action="{{ route('admin.shipping.cities') }}" method="GET" class="d-flex align-items-center">
                <div class="me-3">
                    <select name="governorate_id" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ __('All Governorates') }}</option>
                        @foreach($governorates as $governorate)
                            <option value="{{ $governorate->id }}" {{ $governorateId == $governorate->id ? 'selected' : '' }}>
                                {{ $governorate->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-filter me-1"></i> {{ __('Filter') }}
                </button>
            </form>
        </div>
        <div>
            <a href="{{ route('admin.shipping.cities.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> {{ __('Add City') }}
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5 class="mb-0">{{ __('Cities List') }}</h5>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table admin-datatable">
                    <thead>
                        <tr>
                            <th>{{ __('Name (English)') }}</th>
                            <th>{{ __('Name (Arabic)') }}</th>
                            <th>{{ __('Governorate') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cities as $city)
                            <tr>
                                <td>{{ $city->name_en }}</td>
                                <td>{{ $city->name_ar }}</td>
                                <td>
                                    <a href="{{ route('admin.shipping.cities', ['governorate_id' => $city->governorate->id]) }}">
                                        {{ $city->governorate->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="status-badge {{ $city->is_active ? 'completed' : 'cancelled' }}">
                                        <i class="bi bi-{{ $city->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                        {{ $city->is_active ? __('Active') : __('Inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.shipping.cities.edit', $city) }}" class="action-btn" title="{{ __('Edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.shipping.cities.destroy', $city) }}" method="POST" class="d-inline delete-form">
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
                                <td colspan="5" class="text-center">{{ __('No cities found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $cities->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add no-pagination class to the table to disable DataTables pagination
        document.querySelector('.admin-datatable').classList.add('no-pagination');
        
        // Delete confirmation is now handled by admin-tables.js
        document.querySelectorAll('.delete-form').forEach(form => {
            form.dataset.confirmMessage = "{{ __('Are you sure you want to delete this city?') }}";
        });
    });
</script>
@endpush 
 