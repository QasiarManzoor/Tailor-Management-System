@extends('layouts.app')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')
@section('page-subtitle', 'Review important system actions performed by users and administrators.')

@section('content')
    <section class="page-shell d-grid gap-3">
        <form method="GET" action="{{ route('superadmin.activity-logs.index') }}" class="card-soft p-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="action" class="form-label">Action</label>
                    <input type="text" class="form-control" id="action" name="action" value="{{ $filters['action'] }}" placeholder="order.created, user.login, settings.updated">
                </div>
                <div class="col-md-5">
                    <label for="user_id" class="form-label">User</label>
                    <select class="form-select" id="user_id" name="user_id">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected((int) $filters['user_id'] === $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                </div>
            </div>
        </form>

        <section class="card-soft p-3">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>User</th>
                            <th>Description</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td><span class="fw-semibold">{{ $log->action }}</span></td>
                                <td>{{ $log->user?->name ?? 'System' }}</td>
                                <td>
                                    <div>{{ $log->description ?: 'No extra description.' }}</div>
                                    @if ($log->context)
                                        <div class="small text-muted mt-1">{{ json_encode($log->context) }}</div>
                                    @endif
                                </td>
                                <td>{{ $log->created_at?->format('d M Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state__title">No activity logs found</div>
                                        <div class="empty-state__copy">Important system actions will appear here automatically.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $logs->links() }}</div>
        </section>
    </section>
@endsection
