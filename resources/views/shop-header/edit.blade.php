@extends('layouts.app')

@section('title', 'Shop Profile')
@section('page-title', 'Shop Profile')
@section('page-subtitle', 'Update the shop title, contact details, addresses, and receipt branding.')

@section('content')
    <section class="page-shell">
        <form method="POST" action="{{ route('shop-header.update') }}" class="card-soft p-3 d-grid gap-3">
            @csrf
            @method('PUT')

            <div>
                <h2 class="section-title mb-1">Shop Identity</h2>
                <p class="section-copy">These values are used for this shop in the sidebar, printed slips, receipts, and invoices.</p>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Shop Title</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $shop->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="tagline" class="form-label">Shop Tagline</label>
                    <input type="text" class="form-control @error('tagline') is-invalid @enderror" id="tagline" name="tagline" value="{{ old('tagline', $shop->tagline) }}">
                    @error('tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="phone_primary" class="form-label">Primary Phone</label>
                    <input type="text" class="form-control @error('phone_primary') is-invalid @enderror" id="phone_primary" name="phone_primary" value="{{ old('phone_primary', $shop->phone_primary) }}" data-phone-input>
                    @error('phone_primary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="phone_secondary" class="form-label">Secondary Phone</label>
                    <input type="text" class="form-control @error('phone_secondary') is-invalid @enderror" id="phone_secondary" name="phone_secondary" value="{{ old('phone_secondary', $shop->phone_secondary) }}" data-phone-input>
                    @error('phone_secondary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="address_line_1" class="form-label">Address Line 1</label>
                    <input type="text" class="form-control @error('address_line_1') is-invalid @enderror" id="address_line_1" name="address_line_1" value="{{ old('address_line_1', $shop->address_line_1) }}">
                    @error('address_line_1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="address_line_2" class="form-label">Address Line 2</label>
                    <input type="text" class="form-control @error('address_line_2') is-invalid @enderror" id="address_line_2" name="address_line_2" value="{{ old('address_line_2', $shop->address_line_2) }}">
                    @error('address_line_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="logo_path" class="form-label">Logo Asset Path</label>
                    <input type="text" class="form-control @error('logo_path') is-invalid @enderror" id="logo_path" name="logo_path" value="{{ old('logo_path', $shop->logo_path) }}" placeholder="images/shaq-logo-web-safe.png">
                    @error('logo_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div>
                <h2 class="section-title mb-1">Receipt Footer</h2>
                <p class="section-copy">These details appear as text if the footer banner image is unavailable.</p>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="receipt_footer_company_name" class="form-label">Footer Company Name</label>
                    <input type="text" class="form-control @error('receipt_footer_company_name') is-invalid @enderror" id="receipt_footer_company_name" name="receipt_footer_company_name" value="{{ old('receipt_footer_company_name', $shop->receipt_footer_company_name) }}">
                    @error('receipt_footer_company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="receipt_footer_phone" class="form-label">Footer Phone</label>
                    <input type="text" class="form-control @error('receipt_footer_phone') is-invalid @enderror" id="receipt_footer_phone" name="receipt_footer_phone" value="{{ old('receipt_footer_phone', $shop->receipt_footer_phone) }}" data-phone-input>
                    @error('receipt_footer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="receipt_footer_email" class="form-label">Footer Email</label>
                    <input type="email" class="form-control @error('receipt_footer_email') is-invalid @enderror" id="receipt_footer_email" name="receipt_footer_email" value="{{ old('receipt_footer_email', $shop->receipt_footer_email) }}">
                    @error('receipt_footer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Save Shop Profile</button>
            </div>
        </form>
    </section>
@endsection

