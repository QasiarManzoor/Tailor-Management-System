@extends('layouts.app')

@section('title', 'Data Transfer')
@section('page-title', 'Data Transfer')
@section('page-subtitle', 'Import or export customers, orders, measurements, payments, inventory, workers, and related shop data.')

@section('content')
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Export Data</div>
                    <p class="section-copy mb-3">Download a JSON file containing all business records for the current shop, including customer/order relationships and order attachment files.</p>

                    @if ($shop)
                        <div class="list-chip mb-3">Shop: {{ $shop->name }}</div>
                    @endif

                    <a href="{{ route('data-transfer.export') }}" class="btn btn-dark">Download Export</a>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Import Data</div>
                    <p class="section-copy mb-3">Upload a JSON export from this system. Imported records are remapped so customers, orders, payments, measurements, workers, and inventory stay connected.</p>

                    <form method="POST" action="{{ route('data-transfer.import') }}" enctype="multipart/form-data" onsubmit="return confirm('Import this data file now?');">
                        @csrf
                        <div class="mb-3">
                            <label for="import_file" class="form-label">Export JSON File</label>
                            <input type="file" id="import_file" name="import_file" class="form-control @error('import_file') is-invalid @enderror" accept=".json,application/json" required>
                            @error('import_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" id="replace_existing" name="replace_existing" value="1" class="form-check-input">
                            <label for="replace_existing" class="form-check-label">Replace current shop data before import</label>
                            <div class="form-text text-muted">Leave unchecked to add imported records alongside existing data.</div>
                        </div>

                        <button class="btn btn-outline-dark">Import Data</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-soft">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Included Data</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($tables as $table)
                            <span class="list-chip">{{ str_replace('_', ' ', $table) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
