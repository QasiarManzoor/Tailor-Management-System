@extends('layouts.app')

@section('title', 'Measurements')
@section('page-title', 'Measurements')
@section('page-subtitle', 'Browse saved slips with English and Urdu cues for quick shop use.')
@section('page-actions')
    <a href="{{ route('measurements.create') }}" class="btn btn-dark">New Measurement</a>
@endsection

@section('content')
    <div class="card card-soft mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    @include('partials.bilingual-text', ['key' => 'tailor.measurements.index.search', 'for' => 'search', 'tag' => 'label', 'class' => 'form-label'])
                    <input type="text" id="search" name="search" value="{{ $search }}" class="form-control" placeholder="Measurement title, customer name, or phone">
                </div>
                <div class="col-auto"><button class="btn btn-dark">Search</button></div>
                <div class="col-auto"><a href="{{ route('measurements.index') }}" class="btn btn-outline-secondary">Reset</a></div>
            </form>
        </div>
    </div>

    <div class="card card-soft">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                <tr>
                    <th class="table-heading-bilingual">@include('partials.bilingual-text', ['key' => 'tailor.measurements.index.measurement'])</th>
                    <th class="table-heading-bilingual">@include('partials.bilingual-text', ['key' => 'tailor.common.customer'])</th>
                    <th class="table-heading-bilingual">@include('partials.bilingual-text', ['key' => 'tailor.measurements.index.key_sizes'])</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($measurements as $measurement)
                    <tr>
                        <td>
                            <a href="{{ route('measurements.show', $measurement) }}" class="text-decoration-none fw-semibold">{{ $measurement->title }}</a>
                            <div class="small text-muted">@include('partials.bilingual-text', ['key' => 'tailor.measurements.index.updated']) {{ $measurement->updated_at?->format('d M Y') }}</div>
                        </td>
                        <td>
                            <a href="{{ route('customers.show', $measurement->customer) }}" class="text-decoration-none">{{ $measurement->customer->name }}</a>
                            <div class="small text-muted">{{ $measurement->customer->phone }}</div>
                        </td>
                        <td class="text-muted small">
                            @include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.chest']) {{ $measurement->chest ?: '-' }},
                            @include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.waist']) {{ $measurement->waist ?: '-' }},
                            @include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.shalwar_length']) {{ $measurement->shalwar_length ?: '-' }}
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('measurements.show', $measurement) }}" class="btn btn-sm btn-outline-dark">View</a>
                                <a href="{{ route('measurements.edit', $measurement) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form method="POST" action="{{ route('measurements.destroy', $measurement) }}" onsubmit="return confirm('Delete this measurement?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-5">No measurements matched your search.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $measurements->links() }}</div>
@endsection
