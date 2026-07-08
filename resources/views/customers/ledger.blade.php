@extends('layouts.app')

@section('title', $customer->name.' Ledger')
@section('page-title', 'Customer Ledger')
@section('page-subtitle', $customer->name.' payment statement and order balance history.')

@section('page-actions')
    <button type="button" onclick="window.print()" class="btn btn-outline-secondary">Print</button>
    <a href="{{ route('customers.show', $customer) }}" class="btn btn-dark">Back To Customer</a>
@endsection

@section('content')
    <div class="row g-3 mb-4">
        @foreach ([
            ['label' => 'Orders', 'value' => number_format($summary['ordersCount']), 'note' => 'Total bookings', 'tone' => 'info', 'money' => false],
            ['label' => 'Total Amount', 'value' => $summary['totalAmount'], 'note' => 'All order value', 'tone' => 'warning', 'money' => true],
            ['label' => 'Paid Amount', 'value' => $summary['paidAmount'], 'note' => 'Advance plus payments', 'tone' => 'success', 'money' => true],
            ['label' => 'Balance', 'value' => $summary['balanceAmount'], 'note' => 'Still receivable', 'tone' => 'danger', 'money' => true],
        ] as $card)
            <div class="col-md-6 col-xl-3">
                <div class="card card-soft stat-card stat-card--{{ $card['tone'] }} h-100">
                    <div class="metric-label">{{ $card['label'] }}</div>
                    <div class="stat-value">{{ $card['money'] ? 'Rs. '.number_format((float) $card['value'], 0) : $card['value'] }}</div>
                    <div class="stat-note mt-2">{{ $card['note'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Order Statement</div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>Order</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($orders as $order)
                                <tr class="{{ $order->isOverdue() ? 'overdue-row' : '' }}">
                                    <td><a href="{{ route('orders.show', $order) }}" class="text-decoration-none fw-semibold">{{ $order->order_no }}</a></td>
                                    <td>{{ $order->booking_date?->format('d M Y') }}</td>
                                    <td>@include('partials.status-badge', ['order' => $order])</td>
                                    <td class="text-end">Rs. {{ number_format((float) $order->total_amount, 0) }}</td>
                                    <td class="text-end">Rs. {{ number_format((float) $order->advance_amount, 0) }}</td>
                                    <td class="text-end fw-semibold {{ (float) $order->balance_amount > 0 ? 'text-danger' : 'text-success' }}">Rs. {{ number_format((float) $order->balance_amount, 0) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No orders found for this customer.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Payment History</div>
                    @forelse ($payments as $row)
                        <div class="record-card">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <p class="record-card-title">{{ $row['order']->order_no }}</p>
                                    <div class="record-summary">{{ $row['payment']->payment_date?->format('d M Y') }} · {{ ucwords(str_replace('_', ' ', $row['payment']->payment_method)) }}</div>
                                    @if ($row['payment']->note)
                                        <div class="record-summary">{{ $row['payment']->note }}</div>
                                    @endif
                                </div>
                                <div class="fw-semibold text-success">Rs. {{ number_format((float) $row['payment']->amount, 0) }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No payment entries found yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
