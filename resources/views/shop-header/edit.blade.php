@extends('layouts.app')

@section('title', 'Slip Header')
@section('page-title', 'Slip Header')
@section('page-subtitle', 'Update the shop information shown at the top of printed slips and receipts.')

@section('content')
    <section class="page-shell">
        <form method="POST" action="{{ route('shop-header.update') }}" class="card-soft p-3 d-grid gap-3">
            @csrf
            @method('PUT')

            <div>
                <h2 class="section-title mb-1">Print Header Details</h2>
                <p class="section-copy">These values are used for this shop in the sidebar branding and the printed receipt header.</p>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Shop Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $shop->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="tagline" class="form-label">Shop Tagline</label>
                    <input type="text" class="form-control @error('tagline') is-invalid @enderror" id="tagline" name="tagline" value="{{ old('tagline', $shop->tagline) }}" required>
                    @error('tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="phone_primary" class="form-label">Primary Phone</label>
                    <input type="text" class="form-control @error('phone_primary') is-invalid @enderror" id="phone_primary" name="phone_primary" value="{{ old('phone_primary', $shop->phone_primary) }}" required>
                    @error('phone_primary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="phone_secondary" class="form-label">Secondary Phone</label>
                    <input type="text" class="form-control @error('phone_secondary') is-invalid @enderror" id="phone_secondary" name="phone_secondary" value="{{ old('phone_secondary', $shop->phone_secondary) }}" required>
                    @error('phone_secondary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="address_line_1" class="form-label">Address Line 1</label>
                    <input type="text" class="form-control @error('address_line_1') is-invalid @enderror" id="address_line_1" name="address_line_1" value="{{ old('address_line_1', $shop->address_line_1) }}" required>
                    @error('address_line_1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="address_line_2" class="form-label">Address Line 2</label>
                    <input type="text" class="form-control @error('address_line_2') is-invalid @enderror" id="address_line_2" name="address_line_2" value="{{ old('address_line_2', $shop->address_line_2) }}" required>
                    @error('address_line_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="logo_path" class="form-label">Logo Asset Path</label>
                    <input type="text" class="form-control @error('logo_path') is-invalid @enderror" id="logo_path" name="logo_path" value="{{ old('logo_path', $shop->logo_path) }}" placeholder="images/shaq-logo-web-safe.png">
                    @error('logo_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Save Slip Header</button>
            </div>
        </form>
    </section>
@endsection

