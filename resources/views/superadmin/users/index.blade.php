@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')
@section('page-subtitle', 'Create, update, activate, and review application users.')

@section('page-actions')
    <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">Add User</a>
@endsection

@section('content')
    <section class="page-shell d-grid gap-3">
        <form method="GET" action="{{ route('superadmin.users.index') }}" class="card-soft p-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label for="search" class="form-label">Search Users</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ $search }}" placeholder="Name, email, or role">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Search</button>
                    <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
                </div>
            </div>
        </form>

        <section class="card-soft p-3">
            <div class="table-responsive desktop-table">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <div class="text-muted small">Added {{ $user->created_at?->diffForHumans() }}</div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge bg-info-subtle text-info-emphasis rounded-pill text-uppercase">{{ $user->role }}</span></td>
                                <td>
                                    <span class="badge {{ $user->is_active ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }} rounded-pill">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form method="POST" action="{{ route('superadmin.users.toggle-status', $user) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state__title">No users found</div>
                                        <div class="empty-state__copy">Try a different search or create a new account.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mobile-record-grid d-grid gap-2">
                @foreach ($users as $user)
                    <article class="record-card">
                        <div class="record-title">{{ $user->name }}</div>
                        <div class="record-meta">{{ $user->email }}</div>
                        <div class="d-flex gap-2 flex-wrap mt-2">
                            <span class="badge bg-info-subtle text-info-emphasis rounded-pill text-uppercase">{{ $user->role }}</span>
                            <span class="badge {{ $user->is_active ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }} rounded-pill">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap mt-2">
                            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-3">{{ $users->links() }}</div>
        </section>
    </section>
@endsection
