@extends('layouts.print')

@section('print-content')
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="print-card h-100">
                <div class="section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.customer_information'])</div>
                <div class="detail-grid">
                    <div>
                        <div class="meta-label">@include('partials.bilingual-text', ['key' => 'tailor.common.name'])</div>
                        <div class="meta-value">{{ $measurement->customer->name }}</div>
                    </div>
                    <div>
                        <div class="meta-label">@include('partials.bilingual-text', ['key' => 'tailor.common.phone_number'])</div>
                        <div class="meta-value">{{ $measurement->customer->phone }}</div>
                    </div>
                    <div>
                        <div class="meta-label">@include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.title'])</div>
                        <div class="meta-value">{{ $measurement->title }}</div>
                    </div>
                    <div>
                        <div class="meta-label">@include('partials.bilingual-text', ['key' => 'tailor.common.date'])</div>
                        <div class="meta-value">{{ $measurement->updated_at?->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="print-card h-100">
                <div class="section-title">Slip Notes</div>
                <div class="print-muted">Printable bilingual measurement slip prepared for A4 paper and browser printing.</div>
            </div>
        </div>
    </div>

    <div class="print-card mb-4">
        <div class="section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.upper_body'])</div>
        <div class="measurement-grid">
            @foreach (['kameez_length', 'chest', 'waist', 'hip', 'shoulder', 'sleeve', 'collar', 'arm_hole'] as $field)
                <div class="measurement-box">
                    <div class="meta-label">@include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.'.$field])</div>
                    <div class="meta-value">{{ $measurement->{$field} ?: '-' }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="print-card mb-4">
        <div class="section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.lower_body'])</div>
        <div class="measurement-grid">
            @foreach (['shalwar_length', 'thigh', 'knee', 'bottom_width', 'cuff'] as $field)
                <div class="measurement-box">
                    <div class="meta-label">@include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.'.$field])</div>
                    <div class="meta-value">{{ $measurement->{$field} ?: '-' }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="print-card mb-4">
        <div class="section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.sections.style_details'])</div>
        <div class="measurement-grid">
            @foreach (['front_style', 'collar_style', 'pocket_style', 'trouser_style'] as $field)
                <div class="measurement-box">
                    <div class="meta-label">@include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.'.$field])</div>
                    <div class="meta-value">{{ $measurement->{$field} ?: '-' }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="print-card">
        <div class="section-title">@include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.special_notes'])</div>
        <div class="notes-box">{{ $measurement->special_notes ?: 'No notes recorded on this slip.' }}</div>
    </div>
@endsection
