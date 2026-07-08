@extends('layouts.app')

@section('title', 'Inventory')
@section('page-title', 'Inventory')
@section('page-subtitle', 'Track fabric, accessories, stock movement, and low-stock items.')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-soft">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Add Inventory Item</div>
                    <form method="POST" action="{{ route('inventory.store') }}" class="d-grid gap-3">
                        @csrf
                        <div>
                            <label for="name" class="form-label">Item Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div>
                            <label for="sku" class="form-label">SKU</label>
                            <input type="text" id="sku" name="sku" class="form-control">
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select id="category" name="category" class="form-select" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}">{{ ucwords(str_replace('_', ' ', $category)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="unit" class="form-label">Unit</label>
                                <select id="unit" name="unit" class="form-select" required>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit }}">{{ ucfirst($unit) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="stock_quantity" class="form-label">Stock</label>
                                <input type="number" min="0" step="1" id="stock_quantity" name="stock_quantity" value="0" class="form-control" data-integer-input required>
                            </div>
                            <div class="col-md-4">
                                <label for="reorder_level" class="form-label">Reorder</label>
                                <input type="number" min="0" step="1" id="reorder_level" name="reorder_level" value="0" class="form-control" data-integer-input required>
                            </div>
                            <div class="col-md-4">
                                <label for="cost_price" class="form-label">Cost</label>
                                <input type="number" min="0" step="1" id="cost_price" name="cost_price" value="0" class="form-control" data-integer-input required>
                            </div>
                        </div>
                        <input type="hidden" name="is_active" value="0">
                        <label class="list-chip">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input mt-0" checked>
                            Active
                        </label>
                        <button class="btn btn-dark">Save Item</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="filters-shell mb-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('inventory.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label for="search" class="form-label">Search Inventory</label>
                            <input type="text" id="search" name="search" value="{{ $search }}" class="form-control" placeholder="Item, SKU, or category">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button class="btn btn-dark flex-fill">Search</button>
                            <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            @forelse ($items as $item)
                <div class="card card-soft mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                            <div>
                                <div class="section-title mb-1">{{ $item->name }}</div>
                                <div class="record-summary">{{ $item->sku ?: 'No SKU' }} · {{ ucwords(str_replace('_', ' ', $item->category)) }} · {{ ucfirst($item->unit) }}</div>
                                <div class="record-meta mt-2">
                                    <span class="list-chip">Stock {{ number_format($item->stock_quantity) }}</span>
                                    <span class="list-chip">Reorder {{ number_format($item->reorder_level) }}</span>
                                    <span class="list-chip">Cost Rs. {{ number_format((float) $item->cost_price, 0) }}</span>
                                    @if ($item->isLowStock())
                                        <span class="list-chip text-danger">Low Stock</span>
                                    @endif
                                </div>
                            </div>
                            <form method="POST" action="{{ route('inventory.movements.store', $item) }}" class="row g-2 align-items-end" style="max-width: 34rem;">
                                @csrf
                                <div class="col-6 col-md-3">
                                    <label class="form-label" for="type-{{ $item->id }}">Type</label>
                                    <select id="type-{{ $item->id }}" name="type" class="form-select" required>
                                        @foreach ($movementTypes as $type)
                                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label" for="quantity-{{ $item->id }}">Qty</label>
                                    <input type="number" min="1" step="1" id="quantity-{{ $item->id }}" name="quantity" class="form-control" data-integer-input required>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label" for="movement-date-{{ $item->id }}">Date</label>
                                    <input type="date" id="movement-date-{{ $item->id }}" name="movement_date" value="{{ now()->toDateString() }}" class="form-control" required>
                                </div>
                                <div class="col-6 col-md-3">
                                    <button class="btn btn-outline-dark w-100">Save</button>
                                </div>
                                <div class="col-12">
                                    <input type="text" name="note" class="form-control" placeholder="Movement note">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-mark">&#9638;</div>
                    <h3>No inventory items</h3>
                    <p>Add fabric, accessories, or shop supplies to start tracking stock.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
