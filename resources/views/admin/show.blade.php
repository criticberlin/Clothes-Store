@extends('layouts.admin')

@section('title', 'Support Ticket')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <span>Ticket #{{ $ticket->id }}</span>
        <a href="{{ route('admin.support.index') }}" class="btn btn-sm btn-secondary">Back to Tickets</a>
                </div>

    <div class="admin-card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
            <h4>Ticket Details</h4>
                        <p><strong>User:</strong> {{ $ticket->user->name }}</p>
                        <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
                        <p><strong>Status:</strong>
                            <span class="badge bg-{{ $ticket->status === 'sent' ? 'danger' : ($ticket->status === 'in_progress' ? 'warning' : 'success') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </p>
                        <p><strong>Created:</strong> {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="mb-4">
                        <h4>User's Message</h4>
                        <div class="p-3 bg-light rounded">
                            {{ $ticket->message }}
                        </div>
                    </div>

                    @if($ticket->admin_reply)
                        <div class="mb-4">
                            <h4>Previous Response</h4>
                            <div class="p-3 bg-light rounded">
                                {{ $ticket->admin_reply }}
                            </div>
                            <small class="text-muted">Replied by: {{ $ticket->admin->name ?? 'Support Team' }}</small>
                        </div>
                    @endif

                    @if($ticket->status !== 'done')
                        <div class="mb-4">
                <h4>Reply to Ticket</h4>
                            <form method="POST" action="{{ route('admin.support.reply', $ticket) }}">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control @error('admin_reply') is-invalid @enderror"
                                        name="admin_reply" rows="4" required>{{ old('admin_reply', $ticket->admin_reply) }}</textarea>
                                    @error('admin_reply')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary">Send Reply</button>
                                    <form method="POST" action="{{ route('admin.support.close', $ticket) }}" class="d-inline">
                                        @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to close this ticket?')">
                                Close Ticket
                                        </button>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-info">
                This ticket has been closed.
            </div>
        @endif
    </div>
</div>
@endsection
