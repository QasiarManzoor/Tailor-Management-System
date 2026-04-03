@extends('layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page-title', 'Super Admin Dashboard')
@section('page-subtitle', 'Monitor users, shops, orders, activity, and system-wide performance from one place.')

@section('content')
    <section class="page-shell d-grid gap-3">
        <div class="row g-3">
            <div class="col-md-6 col-xl-3">
                <article class="metric-card metric-card--info h-100">
                    <div class="metric-card__label">Total Shops</div>
                    <div class="metric-card__value">{{ number_format($stats['totalShops']) }}</div>
                    <div class="metric-card__meta">Users: {{ number_format($stats['totalUsers']) }}</div>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="metric-card metric-card--success h-100">
                    <div class="metric-card__label">Total Customers</div>
                    <div class="metric-card__value">{{ number_format($stats['totalCustomers']) }}</div>
                    <div class="metric-card__meta">Orders: {{ number_format($stats['totalOrders']) }}</div>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="metric-card metric-card--warning h-100">
                    <div class="metric-card__label">Pending Orders</div>
                    <div class="metric-card__value">{{ number_format($stats['pendingOrders']) }}</div>
                    <div class="metric-card__meta">Delivered: {{ number_format($stats['deliveredOrders']) }}</div>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="metric-card metric-card--danger h-100">
                    <div class="metric-card__label">Pending Balances</div>
                    <div class="metric-card__value">Rs. {{ number_format($stats['pendingBalances'], 2) }}</div>
                    <div class="metric-card__meta">Received: Rs. {{ number_format($stats['paymentsReceived'], 2) }}</div>
                </article>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-xl-4">
                <section class="card-soft h-100 p-3">
                    <div class="section-header">
                        <div>
                            <h2 class="section-title mb-0">Latest Users</h2>
                            <p class="section-copy">Newest accounts, their shop, and access level.</p>
                        </div>
                        <a href="{{ route('superadmin.users.index') }}" class="btn btn-sm btn-outline-secondary">Manage</a>
                    </div>
                    <div class="d-grid gap-2">
                        @forelse ($latestUsers as $user)
                            <article class="record-card">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="record-title">{{ $user->name }}</div>
                                        <div class="record-meta">{{ $user->email }}</div>
                                        <div class="record-meta">{{ $user->shop?->name ?? 'Unassigned shop' }}</div>
                                    </div>
                                    <span class="badge {{ $user->is_active ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }} rounded-pill text-uppercase">{{ $user->role }}</span>
                                </div>
                            </article>
                        @empty
                            <div class="empty-state">
                                <div class="empty-state__title">No users yet</div>
                                <div class="empty-state__copy">Create accounts for owners or additional super admins.</div>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="col-xl-4">
                <section class="card-soft h-100 p-3">
                    <div class="section-header">
                        <div>
                            <h2 class="section-title mb-0">Latest Orders</h2>
                            <p class="section-copy">Recent order activity across every shop.</p>
                        </div>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary">Open Orders</a>
                    </div>
                    <div class="d-grid gap-2">
                        @forelse ($latestOrders as $order)
                            <article class="record-card">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="record-title">{{ $order->order_no }}</div>
                                        <div class="record-meta">{{ $order->customer?->name }} · {{ ucfirst($order->status) }}</div>
                                        <div class="record-meta">{{ $order->shop?->name ?? 'Unassigned shop' }}</div>
                                    </div>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                </div>
                            </article>
                        @empty
                            <div class="empty-state">
                                <div class="empty-state__title">No orders yet</div>
                                <div class="empty-state__copy">Recent bookings will appear here automatically.</div>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="col-xl-4">
                <section class="card-soft h-100 p-3">
                    <div class="section-header">
                        <div>
                            <h2 class="section-title mb-0">Activity Feed</h2>
                            <p class="section-copy">Important actions happening in the system.</p>
                        </div>
                        <a href="{{ route('superadmin.activity-logs.index') }}" class="btn btn-sm btn-outline-secondary">Open Logs</a>
                    </div>
                    <div class="d-grid gap-2">
                        @forelse ($latestActivityLogs as $log)
                            <article class="record-card">
                                <div class="record-title">{{ $log->action }}</div>
                                <div class="record-meta">{{ $log->user?->name ?? 'System' }} · {{ $log->created_at?->diffForHumans() }}</div>
                                @if ($log->description)
                                    <div class="small mt-1">{{ $log->description }}</div>
                                @endif
                            </article>
                        @empty
                            <div class="empty-state">
                                <div class="empty-state__title">No activity yet</div>
                                <div class="empty-state__copy">Key actions will start appearing here as the system is used.</div>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
