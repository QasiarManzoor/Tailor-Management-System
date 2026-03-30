@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Shop Dashboard')
@section('page-subtitle', 'See bookings, deliveries, pending balances, and urgent work at a glance.')
@section('page-actions')
    <a href="{{ route('customers.create') }}" class="btn btn-outline-secondary">New Customer</a>
    <a href="{{ route('orders.create') }}" class="btn btn-dark">Book Order</a>
@endsection

@section('content')
    <div class="row g-3 mb-4">
        @foreach ([
            ['label' => 'Total Customers', 'value' => $stats['totalCustomers'], 'note' => 'Registered customer profiles'],
            ['label' => 'Total Orders', 'value' => $stats['totalOrders'], 'note' => 'All booking slips'],
            ['label' => 'Pending Orders', 'value' => $stats['pendingOrders'], 'note' => 'Not delivered or cancelled'],
            ['label' => 'Ready Orders', 'value' => $stats['readyOrders'], 'note' => 'Ready for handover'],
            ['label' => 'Deliveries Today', 'value' => $stats['deliveriesToday'], 'note' => 'Due for today'],
            ['label' => 'Overdue Orders', 'value' => $stats['overdueOrders'], 'note' => 'Delivery date already passed'],
            ['label' => 'Pending Payments', 'value' => $stats['pendingPayments'], 'note' => 'Orders with balance left'],
        ] as $card)
            <div class="col-md-6 col-xl">
                <div class="card card-soft metric-card h-100">
                    <div class="card-body">
                        <div class="metric-label">{{ $card['label'] }}</div>
                        <div class="display-6 fw-semibold mt-2">{{ $card['value'] }}</div>
                        <div class="stat-note mt-2">{{ $card['note'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card card-soft h-100">
                <div class="card-header bg-white border-0 p-4 pb-2">
                    <div class="section-title">Latest Orders</div>
                    <p class="text-muted small mb-0">Newest slips booked from the counter.</p>
                </div>
                <div class="card-body p-4 pt-3">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Delivery</th>
                                <th class="text-end">Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($latestOrders as $order)
                                <tr class="{{ $order->isOverdue() ? 'overdue-row' : '' }}">
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="text-decoration-none fw-semibold">{{ $order->order_no }}</a>
                                        <div class="small text-muted">{{ $order->order_type }}</div>
                                    </td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>@include('partials.status-badge', ['order' => $order])</td>
                                    <td>
                                        {{ $order->delivery_date?->format('d M Y') }}
                                        @if ($order->isOverdue())
                                            <div class="small text-danger fw-semibold">Overdue</div>
                                        @endif
                                    </td>
                                    <td class="text-end">Rs. {{ number_format((float) $order->balance_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No orders booked yet.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-soft h-100 mb-4 mb-lg-0">
                <div class="card-header bg-white border-0 p-4 pb-2">
                    <div class="section-title">Pending Balance Widget</div>
                    <p class="text-muted small mb-0">Orders that still need payment collection.</p>
                </div>
                <div class="card-body p-4 pt-3">
                    <div class="d-flex justify-content-between align-items-center rounded-4 p-3 mb-3" style="background:#f9f4ec; border:1px solid #eadfce;">
                        <div>
                            <div class="metric-label">Outstanding Amount</div>
                            <div class="h3 mb-0">Rs. {{ number_format((float) $stats['pendingBalanceAmount'], 2) }}</div>
                        </div>
                        <div class="text-end text-muted small">{{ $stats['pendingPayments'] }} orders pending</div>
                    </div>
                    @forelse ($pendingBalanceOrders as $order)
                        <div class="d-flex justify-content-between align-items-start gap-3 border rounded-4 p-3 mb-3">
                            <div>
                                <div class="fw-semibold"><a href="{{ route('orders.show', $order) }}" class="text-decoration-none">{{ $order->order_no }}</a></div>
                                <div>{{ $order->customer->name }}</div>
                                <div class="text-muted small">Delivery {{ $order->delivery_date?->format('d M Y') }}</div>
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold text-danger">Rs. {{ number_format((float) $order->balance_amount, 2) }}</div>
                                @include('partials.status-badge', ['order' => $order])
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">No pending balances right now.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card card-soft h-100">
                <div class="card-header bg-white border-0 p-4 pb-2">
                    <div class="section-title">Today's Deliveries</div>
                    <p class="text-muted small mb-0">Orders expected to be handed over today.</p>
                </div>
                <div class="card-body p-4 pt-3">
                    @forelse ($todayDeliveries as $order)
                        <div class="border rounded-4 p-3 mb-3 bg-light-subtle">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold">{{ $order->order_no }}</div>
                                    <div>{{ $order->customer->name }}</div>
                                    <div class="text-muted small">{{ $order->order_type }}</div>
                                </div>
                                <span class="list-chip text-capitalize">{{ $order->priority }}</span>
                            </div>
                            <div class="mt-3">@include('partials.status-badge', ['order' => $order])</div>
                        </div>
                    @empty
                        <div class="text-muted">No deliveries due today.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
