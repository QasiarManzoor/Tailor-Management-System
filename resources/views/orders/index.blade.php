@extends('layouts.app')

@section('title', 'Orders')
@section('page-title', 'Orders')
@section('page-subtitle', 'Track every booking from booked to delivered, with overdue jobs easy to spot.')
@section('page-actions')
    <a href="{{ route('orders.create') }}" class="btn btn-dark">Book New Order</a>
@endsection

@section('content')
    <div class="card card-soft mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" for="order_no">Order Number</label>
                    <input type="text" id="order_no" name="order_no" value="{{ $filters['order_no'] }}" class="form-control" placeholder="ORD-20260330-0001">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="customer_id">Customer</label>
                    <select id="customer_id" name="customer_id" class="form-select">
                        <option value="">All customers</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected((int) $filters['customer_id'] === $customer->id)>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="delivery_date">Delivery Date</label>
                    <input type="date" id="delivery_date" name="delivery_date" value="{{ $filters['delivery_date'] }}" class="form-control">
                </div>
                <div class="col-auto"><button class="btn btn-dark">Filter</button></div>
                <div class="col-auto"><a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Reset</a></div>
            </form>
        </div>
    </div>

    <div class="card card-soft">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Delivery</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Balance</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($orders as $order)
                    <tr class="{{ $order->isOverdue() ? 'overdue-row' : '' }}">
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="text-decoration-none fw-semibold">{{ $order->order_no }}</a>
                            <div class="small text-muted">{{ $order->order_type }} · Qty {{ $order->quantity }}</div>
                        </td>
                        <td>
                            {{ $order->customer->name }}
                            <div class="small text-muted">{{ $order->customer->phone }}</div>
                        </td>
                        <td>
                            @include('partials.status-badge', ['order' => $order])
                            @if ($order->priority === 'urgent')
                                <div class="small text-danger fw-semibold mt-1">Urgent</div>
                            @endif
                        </td>
                        <td>
                            {{ $order->delivery_date?->format('d M Y') }}
                            @if ($order->isOverdue())
                                <div class="small text-danger fw-semibold">Overdue</div>
                            @endif
                        </td>
                        <td class="text-end">Rs. {{ number_format((float) $order->total_amount, 2) }}</td>
                        <td class="text-end {{ (float) $order->balance_amount > 0 ? 'text-danger fw-semibold' : 'text-success fw-semibold' }}">Rs. {{ number_format((float) $order->balance_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">No orders matched your filters.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
@endsection
