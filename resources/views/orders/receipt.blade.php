@extends('layouts.print')

@php
    $coreMeasurementFields = [
        'kameez_length',
        'chest',
        'waist',
        'hip',
        'shoulder',
        'sleeve',
        'collar',
        'shalwar_length',
        'thigh',
        'bottom_width',
    ];

    $receiptNotes = \Illuminate\Support\Str::limit(
        $order->special_instructions ?: ($order->measurement?->special_notes ?: 'No notes recorded.'),
        140
    );
@endphp

@section('body-class', 'compact-receipt')

@push('print-styles')
    <style>
        @page {
            size: A5 portrait;
            margin: 5mm;
        }

        body.compact-receipt {
            background: #fff;
            font-size: 10.5px;
            line-height: 1.25;
        }

        .compact-receipt .print-shell {
            max-width: 138mm;
            margin: .35rem auto;
        }

        .compact-receipt .print-page {
            padding: 6mm;
        }

        .compact-receipt .shop-header {
            margin-bottom: .45rem;
            padding-bottom: .4rem;
        }

        .compact-receipt .shop-title {
            font-size: 1rem;
        }

        .compact-receipt .shop-subtitle,
        .compact-receipt .print-muted {
            font-size: .68rem;
        }

        .compact-receipt .print-actions {
            margin-bottom: .45rem;
        }

        .compact-receipt .print-btn,
        .compact-receipt .btn {
            padding: .35rem .65rem;
            font-size: .75rem;
        }

        .compact-receipt .receipt-grid {
            display: grid;
            grid-template-columns: 1.2fr .8fr;
            gap: .4rem;
            margin-bottom: .4rem;
        }

        .compact-receipt .receipt-card {
            border: 1px solid var(--line);
            border-radius: .5rem;
            padding: .45rem .55rem;
            background: #fff;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .compact-receipt .receipt-card + .receipt-card,
        .compact-receipt .receipt-card + .receipt-grid,
        .compact-receipt .receipt-grid + .receipt-card {
            margin-top: .4rem;
        }

        .compact-receipt .section-title {
            font-size: .68rem;
            margin-bottom: .35rem;
            letter-spacing: .03em;
        }

        .compact-receipt .meta-label {
            font-size: .6rem;
            margin-bottom: .1rem;
            letter-spacing: .02em;
        }

        .compact-receipt .meta-value {
            font-size: .76rem;
            line-height: 1.2;
        }

        .compact-receipt .detail-grid {
            gap: .3rem .55rem;
        }

        .compact-receipt .summary-table td,
        .compact-receipt .summary-table th,
        .compact-receipt .table td,
        .compact-receipt .table th {
            padding: .22rem .28rem;
            font-size: .66rem;
            line-height: 1.15;
        }

        .compact-receipt .measurement-table td,
        .compact-receipt .measurement-table th {
            padding: .2rem .28rem;
            font-size: .63rem;
            line-height: 1.1;
            vertical-align: middle;
        }

        .compact-receipt .notes-box {
            min-height: 0;
            max-height: 16mm;
            overflow: hidden;
            padding: .35rem .45rem;
            border-radius: .45rem;
            font-size: .66rem;
            line-height: 1.2;
        }

        .compact-receipt .receipt-inline {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .3rem .5rem;
        }

        .compact-receipt .bilingual-label {
            gap: .08rem;
        }

        .compact-receipt .urdu-text,
        .compact-receipt .ur-label {
            line-height: 1.5;
            font-size: .66rem;
        }

        .compact-receipt .signature-line {
            margin-top: 1rem;
            width: 95px;
            font-size: .65rem;
        }

        .compact-receipt .receipt-table-wrap,
        .compact-receipt table,
        .compact-receipt tr,
        .compact-receipt td,
        .compact-receipt th {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        @media print {
            body.compact-receipt {
                margin: 0;
            }

            .compact-receipt .print-shell {
                max-width: none;
                margin: 0;
            }

            .compact-receipt .print-page {
                padding: 0;
            }
        }
    </style>
@endpush

@section('print-content')
    <div class="receipt-grid">
        <div class="receipt-card">
            <div class="section-title">Compact Order Receipt</div>
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
                    <div class="meta-label">@include('partials.bilingual-text', ['key' => 'tailor.common.return_date'])</div>
                    <div class="meta-value">{{ $order->delivery_date?->format('d M Y') }}</div>
                </div>
                <div>
                    <div class="meta-label">Order Type</div>
                    <div class="meta-value">{{ $order->order_type }}</div>
                </div>
            </div>
        </div>

        <div class="receipt-card">
            <div class="section-title">Payment Summary</div>
            <table class="table summary-table mb-0">
                <tr>
                    <th>@include('partials.bilingual-text', ['key' => 'tailor.common.total'])</th>
                    <td class="text-end">Rs. {{ number_format((float) $order->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>@include('partials.bilingual-text', ['key' => 'tailor.common.advance'])</th>
                    <td class="text-end">Rs. {{ number_format((float) $order->advance_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>@include('partials.bilingual-text', ['key' => 'tailor.common.balance'])</th>
                    <td class="text-end fw-bold">Rs. {{ number_format((float) $order->balance_amount, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="receipt-grid">
        <div class="receipt-card">
            <div class="section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.customer_information'])</div>
            <div class="receipt-inline">
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
                    <div class="meta-label">Qty</div>
                    <div class="meta-value">{{ $order->quantity }}</div>
                </div>
            </div>
        </div>

        <div class="receipt-card">
            <div class="section-title">Notes</div>
            <div class="notes-box">{{ $receiptNotes }}</div>
        </div>
    </div>

    @if ($order->measurement)
        <div class="receipt-card">
            <div class="section-title">Core Measurements</div>
            <div class="receipt-table-wrap">
                <table class="table table-bordered measurement-table mb-0">
                    <tbody>
                    @foreach (array_chunk($coreMeasurementFields, 2) as $fieldPair)
                        <tr>
                            @foreach ($fieldPair as $field)
                                <th>@include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.'.$field])</th>
                                <td>{{ $order->measurement->{$field} ?: '-' }}</td>
                            @endforeach
                            @if (count($fieldPair) === 1)
                                <th></th>
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
