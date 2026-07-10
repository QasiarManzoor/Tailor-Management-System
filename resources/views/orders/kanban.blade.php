@extends('layouts.app')

@section('title', 'Order Kanban')
@section('page-title', 'Order Kanban')
@section('page-subtitle', 'Move active orders through the production workflow.')

@section('page-actions')
    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Order Register</a>
    <a href="{{ route('orders.create') }}" class="btn btn-dark">Book Order</a>
@endsection

@push('styles')
<style>
    .kanban-board {
        display: grid;
        grid-template-columns: repeat(5, minmax(16rem, 1fr));
        gap: .75rem;
        overflow-x: auto;
        padding-bottom: .5rem;
    }
    .kanban-column {
        min-width: 16rem;
        background: var(--surface-soft);
        border: 1px solid var(--surface-border-strong);
        border-radius: .8rem;
        padding: .75rem;
    }
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: .75rem;
        padding: .75rem;
        box-shadow: 0 8px 18px var(--shadow-color);
    }
    .kanban-card + .kanban-card {
        margin-top: .6rem;
    }
</style>
@endpush

@section('content')
    <div class="kanban-board">
        @foreach ($statuses as $status)
            @continue(in_array($status, ['delivered', 'cancelled'], true))
            <section class="kanban-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="section-title mb-0">{{ ucfirst($status) }}</div>
                    <span class="list-chip">{{ ($ordersByStatus->get($status) ?? collect())->count() }}</span>
                </div>

                @forelse ($ordersByStatus->get($status) ?? collect() as $order)
                    <article class="kanban-card {{ $order->isOverdue() ? 'border border-danger-subtle' : '' }}">
                        <div class="d-flex justify-content-between gap-2 align-items-start">
                            <div>
                                <a href="{{ route('orders.show', $order) }}" class="fw-semibold text-decoration-none">{{ $order->order_no }}</a>
                                <div class="record-summary">{{ $order->customer?->name }} · {{ $order->customer?->phone }}</div>
                            </div>
                            @if ($order->priority === 'urgent')
                                <span class="badge bg-danger-subtle text-danger rounded-pill">Urgent</span>
                            @endif
                        </div>
                        <div class="record-meta">
                            <span class="list-chip">Delivery {{ $order->delivery_date?->format('d M') }}</span>
                            @if ($order->worker)
                                <span class="list-chip">{{ $order->worker->name }}</span>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('orders.status.update', $order) }}" class="mt-3 d-flex gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm" required>
                                @foreach ($statuses as $nextStatus)
                                    <option value="{{ $nextStatus }}" @selected($order->status === $nextStatus)>{{ ucfirst($nextStatus) }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-outline-dark">Move</button>
                        </form>
                    </article>
                @empty
                    <p class="text-muted small mb-0">No orders in this stage.</p>
                @endforelse
            </section>
        @endforeach
    </div>
@endsection
