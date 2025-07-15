@extends('layouts.admin')

@section('title', 'Support Tickets')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <span>Support Tickets</span>
        @if($tickets->isNotEmpty())
            <span class="badge bg-primary">{{ $tickets->count() }} Tickets</span>
        @endif
    </div>

    <div class="admin-card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($tickets->isEmpty())
            <p class="text-center">No support tickets found.</p>
        @else
            <div class="table-responsive">
                <table class="table admin-datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>#{{ $ticket->id }}</td>
                                <td>{{ $ticket->user->name }}</td>
                                <td>{{ $ticket->subject }}</td>
                                <td>
                                    <span class="status-badge {{ 
                                        $ticket->status === 'open' ? 'cancelled' : 
                                        ($ticket->status === 'in_progress' ? 'pending' : 'completed') 
                                    }}">
                                        <i class="bi bi-{{ 
                                            $ticket->status === 'open' ? 'exclamation-circle' : 
                                            ($ticket->status === 'in_progress' ? 'hourglass-split' : 'check-circle') 
                                        }}"></i>
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td>{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                <td>{{ $ticket->updated_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.support.show', $ticket) }}" class="action-btn" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
    /* Theme-aware table styles */
    .admin-datatable {
        color: var(--text-primary);
        border-color: var(--border);
    }
    
    .admin-datatable thead tr {
        background-color: var(--surface-alt);
    }
    
    .admin-datatable thead th {
        background-color: var(--surface-alt);
        color: var(--text-secondary);
        border-color: var(--border);
        padding: 12px 16px;
        border-bottom-width: 1px;
    }
    
    .admin-datatable thead th:first-child {
        border-top-left-radius: 8px;
    }
    
    .admin-datatable thead th:last-child {
        border-top-right-radius: 8px;
    }
    
    .admin-datatable tbody td {
        border-color: var(--border);
        background-color: var(--surface);
        padding: 12px 16px;
        vertical-align: middle;
    }
    
    .admin-datatable tbody tr:hover td {
        background-color: var(--surface-alt);
    }
    
    /* Action buttons */
    .action-btns {
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    
    .action-btn {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background-color: var(--surface-alt);
        color: var(--text-primary);
        border: 1px solid var(--border);
        transition: all 0.2s ease;
        cursor: pointer;
        font-size: 1rem;
    }
    
    .action-btn:hover {
        background-color: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .action-btn.delete:hover {
        background-color: var(--danger);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
    }
    
    .status-badge.completed {
        background-color: var(--success-light);
        color: var(--success);
    }
    
    .status-badge.pending {
        background-color: var(--warning-light);
        color: var(--warning);
    }
    
    .status-badge.cancelled {
        background-color: var(--danger-light);
        color: var(--danger);
    }
    
    .status-badge i {
        margin-right: 4px;
    }
    
    /* Search bar placeholder */
    .dataTables_filter input {
        background-color: var(--surface);
        color: var(--text-primary);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 10px 14px;
        transition: all 0.2s ease;
    }
    
    .dataTables_filter input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px var(--primary-light);
        outline: none;
    }
    
    .dataTables_filter input::placeholder {
        color: var(--text-secondary);
        opacity: 0.7;
    }
    
    /* Pagination */
    .dataTables_paginate {
        margin-top: 15px;
    }
    
    .dataTables_paginate .paginate_button {
        color: var(--text-primary) !important;
        background-color: var(--surface) !important;
        border-color: var(--border) !important;
        border-radius: 4px;
        margin: 0 2px;
        transition: all 0.2s ease;
    }
    
    .dataTables_paginate .paginate_button.current,
    .dataTables_paginate .paginate_button:hover {
        background-color: var(--primary) !important;
        color: white !important;
        border-color: var(--primary) !important;
    }
    
    .dataTables_paginate .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .dataTables_info {
        color: var(--text-secondary);
        margin-top: 15px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .action-btns {
            flex-direction: column;
            gap: 5px;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
        }
    }
</style>
@endsection
