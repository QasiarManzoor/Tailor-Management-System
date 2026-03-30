@extends('layouts.app')

@section('title', $order->order_no)
@section('page-title', 'Order '.$order->order_no)
@section('page-subtitle', 'View the full slip, payment record, and delivery status in one place.')
@section('page-actions')
    <a href="{{ route('orders.receipt', $order) }}" class="btn btn-outline-dark" target="_blank">Print Receipt</a>
    <a href="{{ route('orders.invoice', $order) }}" class="btn btn-outline-dark" target="_blank">Print Invoice</a>
    <a href="{{ route('orders.payments.create', $order) }}" class="btn btn-outline-secondary">Add Payment</a>
    <a href="{{ route('orders.edit', $order) }}" class="btn btn-dark">Edit Order</a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card card-soft mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @include('partials.status-badge', ['order' => $order])
                        <span class="list-chip text-capitalize">{{ $order->priority }}</span>
                        @if ($order->isOverdue())
                            <span class="list-chip text-danger">Overdue</span>
                        @endif
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6"><span class="metric-label d-block mb-1">Customer</span><a href="{{ route('customers.show', $order->customer) }}" class="text-decoration-none fw-semibold">{{ $order->customer->name }}</a></div>
                        <div class="col-md-6"><span class="metric-label d-block mb-1">Saved Measurement</span>{{ $order->measurement?->title ?: 'Not linked' }}</div>
                        <div class="col-md-6"><span class="metric-label d-block mb-1">Order Type</span>{{ $order->order_type }}</div>
                        <div class="col-md-6"><span class="metric-label d-block mb-1">Quantity</span>{{ $order->quantity }}</div>
                        <div class="col-md-4"><span class="metric-label d-block mb-1">Booking Date</span>{{ $order->booking_date?->format('d M Y') }}</div>
                        <div class="col-md-4"><span class="metric-label d-block mb-1">Trial Date</span>{{ $order->trial_date?->format('d M Y') ?: 'Not set' }}</div>
                        <div class="col-md-4"><span class="metric-label d-block mb-1">Delivery Date</span>{{ $order->delivery_date?->format('d M Y') }}</div>
                        <div class="col-12"><span class="metric-label d-block mb-1">Fabric Details</span>{{ $order->fabric_details ?: 'No fabric details added.' }}</div>
                        <div class="col-12"><span class="metric-label d-block mb-1">Special Instructions</span>{{ $order->special_instructions ?: 'No special instructions added.' }}</div>
                    </div>
                </div>
            </div>
            <div class="card card-soft">
                <div class="card-header bg-white border-0 p-4 pb-2 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="section-title">Payment History</div>
                        <p class="text-muted small mb-0">Receipts collected after the initial booking advance.</p>
                    </div>
                    <a href="{{ route('orders.payments.create', $order) }}" class="btn btn-sm btn-dark">Record Payment</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Note</th>
                            <th class="text-end">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($order->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date?->format('d M Y') }}</td>
                                <td class="text-capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                                <td class="text-muted">{{ $payment->note ?: '-' }}</td>
                                <td class="text-end">Rs. {{ number_format((float) $payment->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No additional payments recorded yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card card-soft mb-4">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Amount Summary</div>
                    <div class="d-flex justify-content-between py-2 border-bottom"><span>Total Amount</span><strong>Rs. {{ number_format((float) $order->total_amount, 2) }}</strong></div>
                    <div class="d-flex justify-content-between py-2 border-bottom"><span>Advance Received</span><strong>Rs. {{ number_format((float) $order->advance_amount, 2) }}</strong></div>
                    <div class="d-flex justify-content-between py-2"><span>Remaining Balance</span><strong class="{{ (float) $order->balance_amount > 0 ? 'text-danger' : 'text-success' }}">Rs. {{ number_format((float) $order->balance_amount, 2) }}</strong></div>
                </div>
            </div>
            <div class="card card-soft mb-4">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Print Options</div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('orders.receipt', $order) }}" target="_blank" class="btn btn-outline-dark">Open Printable Receipt</a>
                        <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="btn btn-outline-dark">Open Invoice / Delivery Slip</a>
                    </div>
                </div>
            </div>
            <div class="card card-soft">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Quick Actions</div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-outline-secondary">Update Order Slip</a>
                        <a href="{{ route('orders.payments.create', $order) }}" class="btn btn-outline-dark">Record Payment</a>
                        <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Delete this order and its payments?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger w-100">Delete Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
