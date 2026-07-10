<div class="card card-soft mb-4">
    <div class="card-body p-4">
        <div class="section-header">
            <div>
                <div class="section-title">Work Alerts</div>
                <p class="section-copy">Overdue, urgent, and payment follow-ups that need attention first.</p>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="form-panel h-100">
                    <div class="section-title mb-2">Overdue</div>
                    @forelse ($overdueOrdersList as $order)
                        <div class="record-card border border-danger-subtle">
                            <p class="record-card-title"><a href="{{ route('orders.show', $order) }}" class="text-decoration-none">{{ $order->order_no }}</a></p>
                            <div class="record-summary">{{ $order->customer?->name }} - Due {{ $order->delivery_date?->format('d M Y') }}</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No overdue orders.</p>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-panel h-100">
                    <div class="section-title mb-2">Urgent</div>
                    @forelse ($urgentOrders as $order)
                        <div class="record-card">
                            <p class="record-card-title"><a href="{{ route('orders.show', $order) }}" class="text-decoration-none">{{ $order->order_no }}</a></p>
                            <div class="record-summary">{{ $order->customer?->name }} - {{ ucfirst($order->status) }}</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No urgent active orders.</p>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-panel h-100">
                    <div class="section-title mb-2">Payment Follow-Up</div>
                    @forelse ($pendingBalanceOrders->take(3) as $order)
                        <div class="record-card">
                            <p class="record-card-title"><a href="{{ route('orders.show', $order) }}" class="text-decoration-none">{{ $order->order_no }}</a></p>
                            <div class="record-summary">{{ $order->customer?->name }} - Rs. {{ number_format((float) $order->balance_amount, 0) }}</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No payment follow-ups.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
