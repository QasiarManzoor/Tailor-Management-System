@extends('layouts.app')

@section('title', 'Daily Cashbook')
@section('page-title', 'Daily Cashbook')
@section('page-subtitle', 'Track daily receipts, expenses, and net cash for shop closing.')

@section('content')
    <div class="filters-shell mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('cashbook.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label" for="date">Cashbook Date</label>
                    <input type="date" id="date" name="date" value="{{ $date->format('Y-m-d') }}" class="form-control">
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark">Open Day</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-soft stat-card stat-card--success">
                <div class="metric-label">Income</div>
                <div class="stat-value">Rs. {{ number_format($summary['income'], 0) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-soft stat-card stat-card--danger">
                <div class="metric-label">Expenses</div>
                <div class="stat-value">Rs. {{ number_format($summary['expenses'], 0) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-soft stat-card stat-card--info">
                <div class="metric-label">Net Cash</div>
                <div class="stat-value">Rs. {{ number_format($summary['net'], 0) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card card-soft">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Add Cashbook Entry</div>
                    <form method="POST" action="{{ route('cashbook.store') }}">
                        @csrf
                        <input type="hidden" name="entry_date" value="{{ $date->format('Y-m-d') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="type">Type</label>
                                <select id="type" name="type" class="form-select" required>
                                    <option value="expense">Expense</option>
                                    <option value="income">Income</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="category">Category</label>
                                <select id="category" name="category" class="form-select" required>
                                    @foreach ($expenseCategories as $category)
                                        <option value="{{ $category }}">{{ ucwords(str_replace('_', ' ', $category)) }}</option>
                                    @endforeach
                                    <option value="manual_income">Manual Income</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="amount">Amount</label>
                                <input type="number" min="1" step="1" inputmode="numeric" data-integer-input id="amount" name="amount" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="payment_method">Method</label>
                                <select id="payment_method" name="payment_method" class="form-select" required>
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method }}">{{ ucwords(str_replace('_', ' ', $method)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="note">Note</label>
                                <textarea id="note" name="note" rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                        <button class="btn btn-dark mt-3">Save Entry</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card card-soft mb-4">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Payments Received</div>
                    @forelse ($payments as $payment)
                        <div class="record-card">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <p class="record-card-title">{{ $payment->order?->order_no }} - {{ $payment->order?->customer?->name }}</p>
                                    <div class="record-summary">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }} - {{ $payment->note ?: 'No note' }}</div>
                                </div>
                                <div class="fw-semibold text-success">Rs. {{ number_format((float) $payment->amount, 0) }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No payments recorded for this day.</p>
                    @endforelse
                </div>
            </div>

            <div class="card card-soft">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Manual Entries</div>
                    @forelse ($entries as $entry)
                        <div class="record-card">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <p class="record-card-title">{{ ucwords(str_replace('_', ' ', $entry->category)) }}</p>
                                    <div class="record-summary">{{ ucfirst($entry->type) }} - {{ ucwords(str_replace('_', ' ', $entry->payment_method)) }} - {{ $entry->note ?: 'No note' }}</div>
                                </div>
                                <div class="fw-semibold {{ $entry->type === 'expense' ? 'text-danger' : 'text-success' }}">Rs. {{ number_format((float) $entry->amount, 0) }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No manual cashbook entries for this day.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
