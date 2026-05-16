@extends('layouts.app')

@section('title', 'Search')
@section('page-title', 'Search')
@section('page-subtitle', 'Find customers, orders, measurements, phone numbers, and slip numbers from one place.')

@section('content')
    <div class="filters-shell mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('global-search.index') }}" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label" for="q">Search Everything</label>
                    <input type="search" id="q" name="q" value="{{ $query }}" class="form-control" placeholder="Customer name, phone, customer number, order number, or measurement title" autofocus>
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark">Search</button>
                </div>
            </form>
        </div>
    </div>

    @if ($query === '')
        <div class="empty-state">
            <div class="empty-state-mark">&#8981;</div>
            <h3>Start with a name or number</h3>
            <p>Search by customer name, phone, customer number, order number, or measurement title.</p>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card card-soft h-100">
                    <div class="card-body p-4">
                        <div class="section-title mb-3">Customers</div>
                        @forelse ($customers as $customer)
                            <div class="record-card">
                                <p class="record-card-title"><a href="{{ route('customers.show', $customer) }}" class="text-decoration-none">{{ $customer->name }}</a></p>
                                <div class="record-summary"># {{ $customer->customer_no ?: '-' }} - {{ $customer->phone }}</div>
                                <div class="record-meta">
                                    <span class="list-chip">{{ $customer->measurements_count }} measurements</span>
                                    <span class="list-chip">{{ $customer->orders_count }} orders</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No customers matched.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-soft h-100">
                    <div class="card-body p-4">
                        <div class="section-title mb-3">Orders</div>
                        @forelse ($orders as $order)
                            <div class="record-card {{ $order->isOverdue() ? 'border border-danger-subtle' : '' }}">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <p class="record-card-title"><a href="{{ route('orders.show', $order) }}" class="text-decoration-none">{{ $order->order_no }}</a></p>
                                    @include('partials.status-badge', ['order' => $order])
                                </div>
                                <div class="record-summary">{{ $order->customer?->name }} - {{ $order->customer?->phone }}</div>
                                <div class="record-meta">
                                    <span class="list-chip">Delivery {{ $order->delivery_date?->format('d M Y') ?: '-' }}</span>
                                    <span class="list-chip">Balance Rs. {{ number_format((float) $order->balance_amount, 0) }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No orders matched.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-soft h-100">
                    <div class="card-body p-4">
                        <div class="section-title mb-3">Measurements</div>
                        @forelse ($measurements as $measurement)
                            <div class="record-card">
                                <p class="record-card-title"><a href="{{ route('measurements.show', $measurement) }}" class="text-decoration-none">{{ $measurement->title }}</a></p>
                                <div class="record-summary">{{ $measurement->customer?->name }} - {{ $measurement->customer?->phone }}</div>
                                <div class="record-actions">
                                    <a href="{{ route('measurements.copy', $measurement) }}" class="btn btn-sm btn-outline-dark">Copy</a>
                                    <a href="{{ route('measurements.edit', $measurement) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No measurements matched.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
