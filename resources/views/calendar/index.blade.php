@extends('layouts.app')

@section('title', 'Delivery Calendar')
@section('page-title', 'Delivery Calendar')
@section('page-subtitle', 'Plan trial dates, delivery dates, urgent orders, and overdue handovers.')

@push('styles')
<style>
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: .55rem;
    }
    .calendar-day {
        min-height: 11rem;
        padding: .65rem;
        border: 1px solid var(--surface-border-strong);
        border-radius: .8rem;
        background: var(--card-bg);
    }
    .calendar-day--muted {
        opacity: .58;
    }
    .calendar-day--today {
        outline: 2px solid var(--shop-accent);
        outline-offset: 2px;
    }
    .calendar-date {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: .45rem;
        font-weight: 700;
    }
    .calendar-item {
        display: block;
        padding: .4rem .5rem;
        border-radius: .65rem;
        background: var(--surface-subtle);
        color: var(--text-color);
        text-decoration: none;
        font-size: .78rem;
        line-height: 1.25;
    }
    .calendar-item + .calendar-item {
        margin-top: .35rem;
    }
    .calendar-item--delivery {
        border-left: 3px solid var(--success-text);
    }
    .calendar-item--trial {
        border-left: 3px solid var(--info-text);
    }
    .calendar-item--urgent,
    .calendar-item--overdue {
        border-left-color: var(--danger-text);
        background: var(--danger-soft);
    }
    @media (max-width: 991.98px) {
        .calendar-grid {
            grid-template-columns: 1fr;
        }
        .calendar-day {
            min-height: auto;
        }
    }
</style>
@endpush

@section('page-actions')
    <a href="{{ route('calendar.index', ['month' => $previousMonth->format('Y-m')]) }}" class="btn btn-outline-secondary">Previous</a>
    <a href="{{ route('calendar.index') }}" class="btn btn-outline-secondary">Today</a>
    <a href="{{ route('calendar.index', ['month' => $nextMonth->format('Y-m')]) }}" class="btn btn-dark">Next</a>
@endsection

@section('content')
    <div class="filters-shell mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('calendar.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="month" class="form-label">Month</label>
                    <input type="month" id="month" name="month" value="{{ $month->format('Y-m') }}" class="form-control">
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark">Open Month</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-soft">
        <div class="card-body p-4">
            <div class="section-header">
                <div>
                    <div class="section-title">{{ $month->format('F Y') }}</div>
                    <p class="section-copy">Green markers are deliveries, blue markers are trials, and red markers need urgent attention.</p>
                </div>
            </div>

            <div class="calendar-grid mb-2 d-none d-lg-grid">
                @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dayName)
                    <div class="metric-label">{{ $dayName }}</div>
                @endforeach
            </div>

            <div class="calendar-grid">
                @foreach ($weeks->flatten() as $day)
                    @php
                        $key = $day->format('Y-m-d');
                        $deliveryOrders = $ordersByDeliveryDate->get($key, collect());
                        $trialOrders = $ordersByTrialDate->get($key, collect());
                    @endphp
                    <div class="calendar-day {{ $day->month !== $month->month ? 'calendar-day--muted' : '' }} {{ $day->isToday() ? 'calendar-day--today' : '' }}">
                        <div class="calendar-date">
                            <span>{{ $day->format('d') }}</span>
                            <span class="d-lg-none small text-muted">{{ $day->format('D') }}</span>
                        </div>

                        @foreach ($trialOrders as $order)
                            <a href="{{ route('orders.show', $order) }}" class="calendar-item calendar-item--trial {{ $order->priority === 'urgent' ? 'calendar-item--urgent' : '' }}">
                                Trial: {{ $order->order_no }}
                                <div class="small text-muted">{{ $order->customer?->name }}</div>
                            </a>
                        @endforeach

                        @foreach ($deliveryOrders as $order)
                            <a href="{{ route('orders.show', $order) }}" class="calendar-item calendar-item--delivery {{ $order->isOverdue() ? 'calendar-item--overdue' : ($order->priority === 'urgent' ? 'calendar-item--urgent' : '') }}">
                                Delivery: {{ $order->order_no }}
                                <div class="small text-muted">{{ $order->customer?->name }} · {{ ucfirst($order->status) }}</div>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
