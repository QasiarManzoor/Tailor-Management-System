@extends('layouts.print')

@section('print-content')
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="print-card h-100">
                <div class="section-title">Invoice / Delivery Slip</div>
                <div class="detail-grid">
                    <div>
                        <div class="meta-label">Order No</div>
                        <div class="meta-value">{{ $order->order_no }}</div>
                    </div>
                    <div>
                        <div class="meta-label">@include('partials.bilingual-text', ['key' => 'tailor.common.date'])</div>
                        <div class="meta-value">{{ $order->booking_date?->format('d M Y') }}</div>
                    </div>
                    <div>
                        <div class="meta-label">@include('partials.bilingual-text', ['key' => 'tailor.common.name'])</div>
                        <div class="meta-value">{{ $order->customer->name }}</div>
                    </div>
                    <div>
                        <div class="meta-label">@include('partials.bilingual-text', ['key' => 'tailor.common.phone_number'])</div>
                        <div class="meta-value">{{ $order->customer->phone }}</div>
                    </div>
                    <div>
                        <div class="meta-label">Measurement Slip</div>
                        <div class="meta-value">{{ $order->measurement?->title ?: 'Not linked' }}</div>
                    </div>
                    <div>
                        <div class="meta-label">@include('partials.bilingual-text', ['key' => 'tailor.common.return_date'])</div>
                        <div class="meta-value">{{ $order->delivery_date?->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="print-card h-100">
                <div class="section-title">Amount Summary</div>
                <table class="table summary-table mb-0">
                    <tr><th>@include('partials.bilingual-text', ['key' => 'tailor.common.total'])</th><td class="text-end">Rs. {{ number_format((float) $order->total_amount, 2) }}</td></tr>
                    <tr><th>@include('partials.bilingual-text', ['key' => 'tailor.common.advance'])</th><td class="text-end">Rs. {{ number_format((float) $order->advance_amount, 2) }}</td></tr>
                    <tr><th>@include('partials.bilingual-text', ['key' => 'tailor.common.balance'])</th><td class="text-end fw-bold">Rs. {{ number_format((float) $order->balance_amount, 2) }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="print-card mb-4">
        <div class="section-title">Order Items</div>
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Description</th>
                <th class="text-center">Qty</th>
                <th>Fabric</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $order->order_type }}</td>
                <td class="text-center">{{ $order->quantity }}</td>
                <td>{{ $order->fabric_details ?: '-' }}</td>
                <td class="text-capitalize">{{ $order->status }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="print-card mb-4">
        <div class="section-title">Notes</div>
        <div class="notes-box">{{ $order->special_instructions ?: 'No special instructions recorded.' }}</div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="signature-line">Customer Signature</div>
        </div>
        <div class="col-md-6 d-flex justify-content-md-end">
            <div class="signature-line">Shop Signature</div>
        </div>
    </div>
@endsection
