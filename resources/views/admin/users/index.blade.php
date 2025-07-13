@extends('layouts.admin')

@section('title', 'User Management')
@section('description', 'Manage all users in the system')

@section('content')
    <div class="admin-header">
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-2"></i> Add New User
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <span>Users List</span>
            <span class="badge bg-primary">{{ $users->count() }} Users</span>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table admin-datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-{{ $role->name === 'Admin' ? 'danger' : 'info' }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="document.getElementById('delete-user-{{ $user->id }}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-user-{{ $user->id }}" 
                                          action="{{ route('admin.users.delete', $user) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection 