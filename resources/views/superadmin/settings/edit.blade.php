@extends('layouts.app')

@section('title', 'System Settings')
@section('page-title', 'System Settings')
@section('page-subtitle', 'Manage branding and receipt details used across the application.')

@section('content')
    <section class="page-shell">
        <form method="POST" action="{{ route('superadmin.settings.update') }}" class="card-soft p-3 d-grid gap-3">
            @csrf
            @method('PUT')

            <div>
                <h2 class="section-title mb-1">Shop Branding</h2>
                <p class="section-copy">These values are used in the app header, login page, and printed documents.</p>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="shop_name" class="form-label">Shop Name</label>
                    <input type="text" class="form-control @error('shop_name') is-invalid @enderror" id="shop_name" name="shop_name" value="{{ old('shop_name', $settings->shop_name) }}" required>
                    @error('shop_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="shop_tagline" class="form-label">Shop Tagline</label>
                    <input type="text" class="form-control @error('shop_tagline') is-invalid @enderror" id="shop_tagline" name="shop_tagline" value="{{ old('shop_tagline', $settings->shop_tagline) }}">
                    @error('shop_tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="shop_phone_primary" class="form-label">Primary Phone</label>
                    <input type="text" class="form-control @error('shop_phone_primary') is-invalid @enderror" id="shop_phone_primary" name="shop_phone_primary" value="{{ old('shop_phone_primary', $settings->shop_phone_primary) }}">
                    @error('shop_phone_primary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="shop_phone_secondary" class="form-label">Secondary Phone</label>
                    <input type="text" class="form-control @error('shop_phone_secondary') is-invalid @enderror" id="shop_phone_secondary" name="shop_phone_secondary" value="{{ old('shop_phone_secondary', $settings->shop_phone_secondary) }}">
                    @error('shop_phone_secondary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="shop_address_line_1" class="form-label">Address Line 1</label>
                    <input type="text" class="form-control @error('shop_address_line_1') is-invalid @enderror" id="shop_address_line_1" name="shop_address_line_1" value="{{ old('shop_address_line_1', $settings->shop_address_line_1) }}">
                    @error('shop_address_line_1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="shop_address_line_2" class="form-label">Address Line 2</label>
                    <input type="text" class="form-control @error('shop_address_line_2') is-invalid @enderror" id="shop_address_line_2" name="shop_address_line_2" value="{{ old('shop_address_line_2', $settings->shop_address_line_2) }}">
                    @error('shop_address_line_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="logo_path" class="form-label">Logo Asset Path</label>
                    <input type="text" class="form-control @error('logo_path') is-invalid @enderror" id="logo_path" name="logo_path" value="{{ old('logo_path', $settings->logo_path) }}" placeholder="images/shaq-logo.png">
                    @error('logo_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div>
                <h2 class="section-title mb-1">Receipt Footer Details</h2>
                <p class="section-copy">Used as a graceful text fallback when a footer banner is not available.</p>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="receipt_footer_company_name" class="form-label">Company Name</label>
                    <input type="text" class="form-control @error('receipt_footer_company_name') is-invalid @enderror" id="receipt_footer_company_name" name="receipt_footer_company_name" value="{{ old('receipt_footer_company_name', $settings->receipt_footer_company_name) }}">
                    @error('receipt_footer_company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="receipt_footer_phone" class="form-label">Footer Phone</label>
                    <input type="text" class="form-control @error('receipt_footer_phone') is-invalid @enderror" id="receipt_footer_phone" name="receipt_footer_phone" value="{{ old('receipt_footer_phone', $settings->receipt_footer_phone) }}">
                    @error('receipt_footer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="receipt_footer_email" class="form-label">Footer Email</label>
                    <input type="email" class="form-control @error('receipt_footer_email') is-invalid @enderror" id="receipt_footer_email" name="receipt_footer_email" value="{{ old('receipt_footer_email', $settings->receipt_footer_email) }}">
                    @error('receipt_footer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </section>
@endsection
