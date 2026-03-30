@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page-subtitle', 'Search by name or phone and open each customer slip in one click.')
@section('page-actions')
    <a href="{{ route('customers.create') }}" class="btn btn-dark">Add Customer</a>
@endsection

@section('content')
    <div class="card card-soft mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label" for="search">Customer Search</label>
                    <input type="text" id="search" name="search" value="{{ $search }}" class="form-control" placeholder="Search by customer name, phone, or alternate phone">
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark">Search</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-soft">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                <tr>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th class="text-center">Measurements</th>
                    <th class="text-center">Orders</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td>
                            <a href="{{ route('customers.show', $customer) }}" class="text-decoration-none fw-semibold">{{ $customer->name }}</a>
                            <div class="small text-muted text-capitalize">{{ $customer->gender ?: 'Gender not set' }}</div>
                        </td>
                        <td>
                            <div>{{ $customer->phone }}</div>
                            @if ($customer->alternate_phone)
                                <div class="small text-muted">Alt: {{ $customer->alternate_phone }}</div>
                            @endif
                        </td>
                        <td class="text-muted">{{ $customer->address ?: 'Address not added' }}</td>
                        <td class="text-center">{{ $customer->measurements_count }}</td>
                        <td class="text-center">{{ $customer->orders_count }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-dark">View</a>
                                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">No customers matched your search.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
@endsection
