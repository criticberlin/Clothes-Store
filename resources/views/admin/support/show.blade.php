@extends('layouts.admin')

@section('title', 'Support Ticket Details')

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">Ticket #{{ $ticket->id }}</h1>
            <p class="text-secondary mb-0">{{ $ticket->created_at->format('F d, Y h:i A') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.support.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i> Back to Tickets
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Ticket Information -->
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>Ticket Information</span>
                </div>
                <div class="admin-card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Status:</span>
                        <span class="badge bg-{{ 
                            $ticket->status === 'open' ? 'danger' : 
                            ($ticket->status === 'pending' ? 'warning' : 'success') 
                        }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Priority:</span>
                        <span class="badge bg-{{ 
                            $ticket->priority === 'high' ? 'danger' : 
                            ($ticket->priority === 'medium' ? 'warning' : 'info') 
                        }}">
                            {{ ucfirst($ticket->priority ?? 'normal') }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Created:</span>
                        <span>{{ $ticket->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Last Updated:</span>
                        <span>{{ $ticket->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="admin-card mt-4">
                <div class="admin-card-header">
                    <span>Customer Information</span>
                </div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <h6>{{ $ticket->user->name }}</h6>
                        <p class="mb-1">{{ $ticket->user->email }}</p>
                        @if(isset($ticket->user->phone))
                        <p class="mb-1">{{ $ticket->user->phone }}</p>
                        @endif
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $ticket->user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-person me-2"></i> View Customer Profile
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Ticket Actions -->
            <div class="admin-card mt-4">
                <div class="admin-card-header">
                    <span>Ticket Actions</span>
                </div>
                <div class="admin-card-body">
                    @if($ticket->status !== 'resolved')
                        <form method="POST" action="{{ route('admin.support.close', $ticket) }}" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label for="close_message" class="form-label">Closing Message (Optional)</label>
                                <textarea class="form-control" id="close_message" name="message" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-check-circle me-2"></i> Close Ticket
                            </button>
                        </form>
                    @else
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i> This ticket is resolved
                        </div>
                        <form method="POST" action="{{ route('admin.support.reply', $ticket) }}" class="mb-3">
                            @csrf
                            <input type="hidden" name="status" value="pending">
                            <button type="submit" class="btn btn-outline-warning w-100">
                                <i class="bi bi-arrow-counterclockwise me-2"></i> Reopen Ticket
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Ticket Conversation -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>{{ $ticket->subject }}</span>
                </div>
                <div class="admin-card-body">
                    <!-- Original Message -->
                    <div class="ticket-message customer-message">
                        <div class="d-flex justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                                </div>
                                <span class="fw-bold">{{ $ticket->user->name }}</span>
                            </div>
                            <small class="text-muted">{{ $ticket->created_at->format('M d, Y h:i A') }}</small>
                        </div>
                        <div class="message-content p-3 bg-light rounded">
                            {!! nl2br(e($ticket->message)) !!}
                        </div>
                    </div>
                    
                    <!-- Replies -->
                    @if(isset($ticket->replies) && $ticket->replies->count() > 0)
                        @foreach($ticket->replies as $reply)
                            <div class="ticket-message {{ $reply->is_admin ? 'admin-message' : 'customer-message' }} mt-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar {{ $reply->is_admin ? 'bg-success' : 'bg-primary' }} text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-bold">
                                            {{ $reply->user->name }}
                                            @if($reply->is_admin)
                                                <span class="badge bg-success ms-1">Staff</span>
                                            @endif
                                        </span>
                                    </div>
                                    <small class="text-muted">{{ $reply->created_at->format('M d, Y h:i A') }}</small>
                                </div>
                                <div class="message-content p-3 {{ $reply->is_admin ? 'bg-success bg-opacity-10' : 'bg-light' }} rounded">
                                    {!! nl2br(e($reply->message)) !!}
                                </div>
                            </div>
                        @endforeach
                    @endif
                    
                    <!-- Reply Form -->
                    @if($ticket->status !== 'resolved')
                        <div class="reply-form mt-4 pt-4 border-top">
                            <h5>Reply to Ticket</h5>
                            <form method="POST" action="{{ route('admin.support.reply', $ticket) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="message" class="form-label">Your Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required></textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="resolve" name="resolve" value="1">
                                    <label class="form-check-label" for="resolve">
                                        Resolve this ticket after sending reply
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-2"></i> Send Reply
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .ticket-message {
        margin-bottom: 1.5rem;
    }
    .admin-message .message-content {
        border-left: 4px solid var(--success);
    }
    .customer-message .message-content {
        border-left: 4px solid var(--primary);
    }
</style>
@endsection 