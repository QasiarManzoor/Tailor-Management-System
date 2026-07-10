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
            ['label' => 'Total Customers', 'value' => $stats['totalCustomers'], 'note' => 'Registered customer profiles', 'icon' => '&#9786;', 'tone' => 'info'],
            ['label' => 'Total Orders', 'value' => $stats['totalOrders'], 'note' => 'All booking slips', 'icon' => '&#9636;', 'tone' => 'info'],
            ['label' => 'Pending Orders', 'value' => $stats['pendingOrders'], 'note' => 'Still in progress at the shop', 'icon' => '&#9716;', 'tone' => 'warning'],
            ['label' => 'Ready Orders', 'value' => $stats['readyOrders'], 'note' => 'Ready for handover', 'icon' => '&#10003;', 'tone' => 'success'],
            ['label' => 'Deliveries Today', 'value' => $stats['deliveriesToday'], 'note' => 'Expected for today', 'icon' => '&#9719;', 'tone' => 'info'],
            ['label' => 'Overdue Orders', 'value' => $stats['overdueOrders'], 'note' => 'Require urgent attention', 'icon' => '&#9888;', 'tone' => 'danger'],
            ['label' => 'Pending Payments', 'value' => $stats['pendingPayments'], 'note' => 'Balances still to collect', 'icon' => '$', 'tone' => 'warning'],
        ] as $card)
            <div class="col-md-6 col-xl">
                <div class="card card-soft metric-card stat-card stat-card--{{ $card['tone'] }} h-100">
                    <div class="stat-icon">{!! $card['icon'] !!}</div>
                    <div class="metric-label">{{ $card['label'] }}</div>
                    <div class="stat-value">{{ $card['value'] }}</div>
                    <div class="stat-note mt-2">{{ $card['note'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    @include('dashboard._work_alerts')

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-header">
                        <div>
                            <div class="section-title">Latest Orders</div>
                            <p class="section-copy">Newest slips booked from the counter.</p>
                        </div>
                    </div>

                    @if ($latestOrders->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-mark">&#9636;</div>
                            <h3>No orders yet</h3>
                            <p>Start with your first booking and it will appear here immediately.</p>
                            <a href="{{ route('orders.create') }}" class="btn btn-dark btn-sm">Book First Order</a>
                        </div>
                    @else
                        <div class="desktop-table">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Delivery</th>
                                        <th class="text-end">Balance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($latestOrders as $order)
                                        <tr class="{{ $order->isOverdue() ? 'overdue-row' : '' }}">
                                            <td>
                                                <a href="{{ route('orders.show', $order) }}" class="text-decoration-none fw-semibold">{{ $order->order_no }}</a>
                                                <div class="small text-muted">{{ $order->customer->customer_no ?: '-' }} � Qty {{ $order->quantity }}</div>
                                            </td>
                                            <td>
                                                {{ $order->customer->name }}
                                                <div class="small text-muted">{{ $order->customer->phone }}</div>
                                            </td>
                                            <td>@include('partials.status-badge', ['order' => $order])</td>
                                            <td>
                                                {{ $order->delivery_date?->format('d M Y') }}
                                                @if ($order->isOverdue())
                                                    <div class="small text-danger fw-semibold">Overdue</div>
                                                @endif
                                            </td>
                                            <td class="text-end {{ (float) $order->balance_amount > 0 ? 'text-danger fw-semibold' : 'text-success fw-semibold' }}">Rs. {{ number_format((float) $order->balance_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mobile-record-grid">
                            @foreach ($latestOrders as $order)
                                <div class="record-card {{ $order->isOverdue() ? 'border border-danger-subtle' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <div>
                                            <p class="record-card-title"><a href="{{ route('orders.show', $order) }}" class="text-decoration-none">{{ $order->order_no }}</a></p>
                                            <div class="record-summary">{{ $order->customer->name }} � {{ $order->customer->phone }}</div>
                                        </div>
                                        @include('partials.status-badge', ['order' => $order])
                                    </div>
                                    <div class="record-meta">
                                        <span class="list-chip">Delivery {{ $order->delivery_date?->format('d M Y') }}</span>
                                        <span class="list-chip">Balance Rs. {{ number_format((float) $order->balance_amount, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-header">
                        <div>
                            <div class="section-title">Pending Balance</div>
                            <p class="section-copy">Orders that still need payment collection.</p>
                        </div>
                    </div>

                    <div class="balance-summary p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="metric-label">Outstanding Amount</div>
                                <div class="h3 mb-0" id="pending-balance-amount" data-hidden-value="Rs. {{ number_format((float) $stats['pendingBalanceAmount'], 2) }}" data-visible="false">Tap to reveal</div>
                            </div>
                            <div class="text-end">
                                <span class="list-chip d-inline-flex mb-2">{{ $stats['pendingPayments'] }} unpaid</span>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="pending-balance-toggle">Show Amount</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @forelse ($pendingBalanceOrders as $order)
                        <div class="record-card">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <p class="record-card-title"><a href="{{ route('orders.show', $order) }}" class="text-decoration-none">{{ $order->order_no }}</a></p>
                                    <div class="record-summary">{{ $order->customer->name }} � Delivery {{ $order->delivery_date?->format('d M Y') }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold text-danger">Rs. {{ number_format((float) $order->balance_amount, 2) }}</div>
                                    @include('partials.status-badge', ['order' => $order])
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-mark">&#10003;</div>
                            <h3>No pending balances</h3>
                            <p>All current orders are clear on payments right now.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-header">
                        <div>
                            <div class="section-title">Today's Deliveries</div>
                            <p class="section-copy">Orders expected to be handed over today.</p>
                        </div>
                    </div>

                    @forelse ($todayDeliveries as $order)
                        <div class="record-card">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <p class="record-card-title">{{ $order->order_no }}</p>
                                    <div class="record-summary">{{ $order->customer->name }} � {{ $order->customer->phone }}</div>
                                </div>
                                <span class="list-chip"><span class="pill-dot"></span>{{ ucfirst($order->priority) }}</span>
                            </div>
                            <div class="record-meta">
                                @include('partials.status-badge', ['order' => $order])
                                <span class="list-chip">{{ $order->order_type }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-mark">&#9719;</div>
                            <h3>No deliveries due today</h3>
                            <p>Nothing is scheduled for handover today.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        var amount = document.getElementById('pending-balance-amount');
        var toggle = document.getElementById('pending-balance-toggle');

        if (!amount || !toggle) {
            return;
        }

        function applyVisibility(isVisible) {
            amount.textContent = isVisible ? amount.dataset.hiddenValue : 'Tap to reveal';
            amount.dataset.visible = isVisible ? 'true' : 'false';
            toggle.textContent = isVisible ? 'Hide Amount' : 'Show Amount';
            toggle.setAttribute('aria-pressed', isVisible ? 'true' : 'false');
        }

        toggle.addEventListener('click', function () {
            applyVisibility(amount.dataset.visible !== 'true');
        });

        amount.addEventListener('click', function () {
            applyVisibility(amount.dataset.visible !== 'true');
        });

        applyVisibility(false);
    })();
</script>
@endpush
