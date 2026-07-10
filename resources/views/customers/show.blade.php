@extends('layouts.app')

@section('title', $customer->name)
@section('page-title', $customer->name)
@section('page-subtitle', 'Customer profile, saved measurements, and order history.')
@section('page-actions')
    <a href="{{ route('customers.ledger', $customer) }}" class="btn btn-outline-secondary">Ledger</a>
    <a href="{{ route('measurements.create', ['customer_id' => $customer->id]) }}" class="btn btn-outline-secondary">Add Measurement</a>
    <a href="{{ route('orders.create', ['customer_id' => $customer->id]) }}" class="btn btn-dark">Book Order</a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="list-chip">Customer # {{ $customer->customer_no ?: '-' }}</span>
                        <span class="list-chip">{{ $customer->phone }}</span>
                        @if ($customer->alternate_phone)
                            <span class="list-chip">Alt {{ $customer->alternate_phone }}</span>
                        @endif
                    </div>
                    <div class="mb-3"><span class="metric-label d-block mb-1">Address</span>{{ $customer->address ?: 'Address not added yet' }}</div>
                    <div class="mb-3"><span class="metric-label d-block mb-1">Gender</span>{{ $customer->gender ? ucfirst($customer->gender) : 'Not set' }}</div>
                    <div><span class="metric-label d-block mb-1">Notes</span>{{ $customer->notes ?: 'No notes saved yet' }}</div>
                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer and related records?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card card-soft mb-4">
                <div class="card-header bg-white border-0 p-4 pb-2 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="section-title">Saved Measurements</div>
                        <p class="text-muted small mb-0">Use these saved slips while booking a new order.</p>
                    </div>
                    <a href="{{ route('measurements.create', ['customer_id' => $customer->id]) }}" class="btn btn-sm btn-dark">Add Measurement</a>
                </div>
                <div class="card-body p-4 pt-3">
                    @forelse ($customer->measurements as $measurement)
                        <div class="border rounded-4 p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <a href="{{ route('measurements.show', $measurement) }}" class="text-decoration-none fw-semibold">{{ $measurement->title }}</a>
                                    <div class="text-muted small">Chest {{ $measurement->chest ?: '-' }}, Waist {{ $measurement->waist ?: '-' }}, Shalwar {{ $measurement->shalwar_length ?: '-' }}</div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('measurements.show', $measurement) }}" class="btn btn-sm btn-outline-dark">View</a>
                                    <a href="{{ route('measurements.edit', $measurement) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form method="POST" action="{{ route('measurements.destroy', $measurement) }}" onsubmit="return confirm('Delete this measurement?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                            @if ($measurement->special_notes)
                                <div class="small text-muted mt-2">{{ $measurement->special_notes }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-muted">No saved measurements yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="card card-soft">
                <div class="card-header bg-white border-0 p-4 pb-2 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="section-title">Order History</div>
                        <p class="text-muted small mb-0">Every order slip linked to this customer.</p>
                    </div>
                    <a href="{{ route('orders.create', ['customer_id' => $customer->id]) }}" class="btn btn-sm btn-dark">Book Order</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Delivery</th>
                            <th class="text-end">Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($customer->orders as $order)
                            <tr class="{{ $order->isOverdue() ? 'overdue-row' : '' }}">
                                <td><a href="{{ route('orders.show', $order) }}" class="text-decoration-none fw-semibold">{{ $order->order_no }}</a></td>
                                <td>{{ $order->order_type }}</td>
                                <td>@include('partials.status-badge', ['order' => $order])</td>
                                <td>{{ $order->delivery_date?->format('d M Y') }}</td>
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
@endsection
