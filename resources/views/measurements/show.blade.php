@extends('layouts.app')

@section('title', $measurement->title)
@section('page-title', $measurement->title)
@section('page-subtitle', 'Bilingual measurement slip view for quick reading at the shop counter.')
@section('page-actions')

    <a href="{{ route('measurements.edit', $measurement) }}" class="btn btn-outline-secondary">Edit Measurement</a>
    <a href="{{ route('orders.create', ['customer_id' => $measurement->customer_id]) }}" class="btn btn-dark">Book Order</a>
@endsection

@section('content')
    <div class="slip-sheet overflow-hidden">
        <div class="slip-banner p-4 p-lg-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="h4 mb-2">{{ $measurement->title }}</div>
                    <div class="text-muted">{{ $measurement->customer->name }} · {{ $measurement->customer->phone }}</div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="metric-label">@include('partials.bilingual-text', ['key' => 'tailor.common.date'])</div>
                    <div class="slip-kpi-value">{{ $measurement->updated_at?->format('d M Y') }}</div>
                </div>
            </div>
        </div>
        <div class="p-4 p-lg-5">
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="slip-section p-4 h-100">
                        <div class="slip-section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.customer_information'])</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="metric-label mb-2">@include('partials.bilingual-text', ['key' => 'tailor.common.name'])</div>
                                <div class="slip-kpi-value">{{ $measurement->customer->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="metric-label mb-2">@include('partials.bilingual-text', ['key' => 'tailor.common.phone_number'])</div>
                                <div class="slip-kpi-value">{{ $measurement->customer->phone }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="slip-section p-4 h-100">
                        <div class="slip-section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.measurement_summary'])</div>
                        <div class="slip-kpi">
                            <div class="metric-label mb-2">@include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.title'])</div>
                            <div class="slip-kpi-value">{{ $measurement->title }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="slip-section p-4 h-100">
                        <div class="slip-section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.upper_body'])</div>
                        <div class="row g-3">
                            @foreach (['kameez_length', 'chest', 'waist', 'hip', 'shoulder', 'sleeve', 'collar', 'arm_hole'] as $field)
                                <div class="col-sm-6">
                                    <div class="metric-label mb-1">@include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.'.$field])</div>
                                    <div class="slip-kpi-value">{{ $measurement->{$field} ?: '-' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="slip-section p-4 h-100">
                        <div class="slip-section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.lower_body'])</div>
                        <div class="row g-3">
                            @foreach (['shalwar_length', 'thigh', 'knee', 'bottom_width', 'cuff'] as $field)
                                <div class="col-sm-6">
                                    <div class="metric-label mb-1">@include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.'.$field])</div>
                                    <div class="slip-kpi-value">{{ $measurement->{$field} ?: '-' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="slip-section p-4">
                        <div class="slip-section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.style_details'])</div>
                        <div class="row g-3">
                            @foreach (['front_style', 'collar_style', 'pocket_style', 'trouser_style'] as $field)
                                <div class="col-md-6 col-xl-3">
                                    <div class="metric-label mb-1">@include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.'.$field])</div>
                                    <div class="slip-kpi-value">{{ $measurement->{$field} ?: '-' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="slip-section p-4">
                        <div class="slip-section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.special_notes'])</div>
                        <div class="slip-notes p-3 rounded-4 border bg-white">{{ $measurement->special_notes ?: 'No notes saved on this slip.' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


