@extends('layouts.app')

@section('title', 'Add Payment')
@section('page-title', 'Add Payment for '.$order->order_no)
@section('page-subtitle', 'Collect and record balance payments safely without letting the balance go negative.')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Order Summary</div>
                    <div class="mb-3"><span class="metric-label d-block mb-1">Customer</span>{{ $order->customer->name }}</div>
                    <div class="mb-3"><span class="metric-label d-block mb-1">Total Amount</span>Rs. {{ number_format((float) $order->total_amount, 0) }}</div>
                    <div class="mb-3"><span class="metric-label d-block mb-1">Advance Received</span>Rs. {{ number_format((float) $order->advance_amount, 0) }}</div>
                    <div><span class="metric-label d-block mb-1">Remaining Balance</span><span class="fw-semibold text-danger">Rs. {{ number_format((float) $order->balance_amount, 0) }}</span></div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card card-soft">
                <div class="card-body p-4 p-lg-5">
                    <form method="POST" action="{{ route('orders.payments.store', $order) }}">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label" for="amount">Amount</label>
                                <input type="number" step="1" min="1" max="{{ (int) $order->balance_amount }}" inputmode="numeric" data-integer-input id="amount" name="amount" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" required>
                                @include('partials.field-error', ['field' => 'amount'])
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="payment_method">Payment Method</label>
                                <select id="payment_method" name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                    <option value="">Select method</option>
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method }}" @selected(old('payment_method') === $method)>{{ ucwords(str_replace('_', ' ', $method)) }}</option>
                                    @endforeach
                                </select>
                                @include('partials.field-error', ['field' => 'payment_method'])
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="payment_date">Payment Date</label>
                                <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}" min="{{ now()->toDateString() }}" class="form-control @error('payment_date') is-invalid @enderror" required>
                                @include('partials.field-error', ['field' => 'payment_date'])
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="note">Note</label>
                                <textarea id="note" name="note" rows="4" class="form-control @error('note') is-invalid @enderror" placeholder="Example: customer paid remaining balance at trial visit">{{ old('note') }}</textarea>
                                @include('partials.field-error', ['field' => 'note'])
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-dark">Save Payment</button>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


