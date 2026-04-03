@extends('layouts.app')

@section('title', 'Shop Management')
@section('page-title', 'Shop Management')
@section('page-subtitle', 'Archive shops safely or delete only shops that have no assigned users. Deleting a shop removes its business data.')

@section('content')
    <section class="page-shell d-grid gap-3">
        <form method="GET" action="{{ route('superadmin.shops.index') }}" class="card-soft p-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label for="search" class="form-label">Search Shops</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ $search }}" placeholder="Shop name, code, or phone">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Search</button>
                    <a href="{{ route('superadmin.shops.index') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
                </div>
            </div>
        </form>

        <section class="card-soft p-3">
            <div class="table-responsive desktop-table">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Shop</th>
                            <th>Status</th>
                            <th>Users</th>
                            <th>Customers</th>
                            <th>Orders</th>
                            <th>Payments</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shops as $shop)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $shop->name }}</div>
                                    <div class="text-muted small">{{ $shop->code }} @if($shop->phone_primary) · {{ $shop->phone_primary }} @endif</div>
                                </td>
                                <td>
                                    <span class="badge {{ $shop->is_active ? 'bg-success-subtle text-success-emphasis' : 'bg-warning-subtle text-warning-emphasis' }} rounded-pill">
                                        {{ $shop->is_active ? 'Active' : 'Archived' }}
                                    </span>
                                </td>
                                <td>{{ number_format($shop->users_count) }}</td>
                                <td>{{ number_format($shop->customers_count) }}</td>
                                <td>{{ number_format($shop->orders_count) }}</td>
                                <td>{{ number_format($shop->payments_count) }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <form method="POST" action="{{ route('superadmin.shops.manage', $shop) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">Manage Shop Data</button>
                                        </form>
                                        <form method="POST" action="{{ route('superadmin.shops.toggle-status', $shop) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $shop->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                {{ $shop->is_active ? 'Archive' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('superadmin.shops.destroy', $shop) }}" onsubmit="return confirm('Delete this shop and all of its related business data? Assigned users must be removed or reassigned first. This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state__title">No shops found</div>
                                        <div class="empty-state__copy">Archived and active shops will appear here.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mobile-record-grid gap-2">
                @foreach ($shops as $shop)
                    <article class="record-card">
                        <div class="record-title">{{ $shop->name }}</div>
                        <div class="record-meta">{{ $shop->code }}</div>
                        <div class="record-meta">Users: {{ $shop->users_count }} · Customers: {{ $shop->customers_count }}</div>
                        <div class="record-meta">Orders: {{ $shop->orders_count }} · Payments: {{ $shop->payments_count }}</div>
                        <div class="d-flex gap-2 flex-wrap mt-2">
                            <span class="badge {{ $shop->is_active ? 'bg-success-subtle text-success-emphasis' : 'bg-warning-subtle text-warning-emphasis' }} rounded-pill">{{ $shop->is_active ? 'Active' : 'Archived' }}</span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap mt-2">
                            <form method="POST" action="{{ route('superadmin.shops.manage', $shop) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">Manage Shop Data</button>
                            </form>
                            <form method="POST" action="{{ route('superadmin.shops.toggle-status', $shop) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $shop->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">{{ $shop->is_active ? 'Archive' : 'Activate' }}</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-3">{{ $shops->links() }}</div>
        </section>
    </section>
@endsection


