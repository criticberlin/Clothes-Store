@extends('layouts.admin')

@section('title', 'Support Tickets')
@section('description', 'Manage customer support tickets')

@section('content')
    <div class="admin-header">
        <div>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-funnel me-2"></i> Filter Tickets
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item" href="{{ route('admin.support.index') }}">All Tickets</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.support.index') }}?status=open">Open Tickets</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.support.index') }}?status=pending">Pending Tickets</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.support.index') }}?status=resolved">Resolved Tickets</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <span>Support Tickets</span>
            @if(isset($tickets))
                <span class="badge bg-primary">{{ $tickets->total() ?? 0 }} Tickets</span>
            @else
                <span class="badge bg-primary">0 Tickets</span>
            @endif
        </div>
        <div class="admin-card-body">
            @if(isset($tickets) && $tickets->count() > 0)
                <div class="table-responsive">
                    <table class="table admin-datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject</th>
                                <th>Customer</th>
                                <th>Created</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>#{{ $ticket->id }}</td>
                                    <td>{{ $ticket->subject }}</td>
                                    <td>{{ $ticket->user->name }}</td>
                                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="status-badge {{ 
                                            $ticket->status === 'open' ? 'cancelled' : 
                                            ($ticket->status === 'pending' ? 'pending' : 'completed') 
                                        }}">
                                            <i class="bi bi-{{ 
                                                $ticket->status === 'open' ? 'exclamation-circle' : 
                                                ($ticket->status === 'pending' ? 'hourglass-split' : 'check-circle') 
                                            }}"></i>
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ 
                                            $ticket->priority === 'high' ? 'cancelled' : 
                                            ($ticket->priority === 'medium' ? 'pending' : 'info') 
                                        }}">
                                            <i class="bi bi-{{ 
                                                $ticket->priority === 'high' ? 'exclamation-triangle' : 
                                                ($ticket->priority === 'medium' ? 'dash-circle' : 'info-circle') 
                                            }}"></i>
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
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

                <div class="d-flex justify-content-center mt-4">
                    {{ $tickets->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-ticket-perforated fs-1 text-secondary mb-3"></i>
                    <h5>No Support Tickets Found</h5>
                    <p class="text-muted">There are currently no support tickets in the system.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon purple">
                    <i class="bi bi-ticket"></i>
                </div>
                <div class="stats-value">
                    @if(isset($tickets))
                        {{ $tickets->total() ?? 0 }}
                    @else
                        0
                    @endif
                </div>
                <div class="stats-label">Total Tickets</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon orange">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stats-value">
                    @if(isset($tickets))
                        {{ $tickets->where('status', 'open')->count() ?? 0 }}
                    @else
                        0
                    @endif
                </div>
                <div class="stats-label">Open Tickets</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon green">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-value">
                    @if(isset($tickets))
                        {{ $tickets->where('status', 'resolved')->count() ?? 0 }}
                    @else
                        0
                    @endif
                </div>
                <div class="stats-label">Resolved Tickets</div>
            </div>
        </div>
    </div>
@endsection 