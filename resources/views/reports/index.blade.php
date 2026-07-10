@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports')
@section('page-subtitle', 'Review sales, payments, expenses, balances, and order progress for any period.')

@section('content')
    <div class="filters-shell mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="period" class="form-label">Period</label>
                    <select id="period" name="period" class="form-select">
                        @foreach (['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'custom' => 'Custom Range'] as $value => $label)
                            <option value="{{ $value }}" @selected($period === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="form-control">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-dark flex-fill">Apply</button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach ([
            ['label' => 'Gross Income', 'value' => $summary['grossIncome'], 'note' => 'Payments plus manual income', 'tone' => 'success'],
            ['label' => 'Expenses', 'value' => $summary['expenses'], 'note' => 'Manual cashbook expenses', 'tone' => 'danger'],
            ['label' => 'Net Income', 'value' => $summary['netIncome'], 'note' => 'Gross income minus expenses', 'tone' => 'info'],
            ['label' => 'Booked Value', 'value' => $summary['ordersValue'], 'note' => $summary['ordersCount'].' orders booked', 'tone' => 'warning'],
        ] as $card)
            <div class="col-md-6 col-xl-3">
                <div class="card card-soft stat-card stat-card--{{ $card['tone'] }} h-100">
                    <div class="metric-label">{{ $card['label'] }}</div>
                    <div class="stat-value">Rs. {{ number_format((float) $card['value'], 0) }}</div>
                    <div class="stat-note mt-2">{{ $card['note'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Collections</div>
                    <div class="d-grid gap-2">
                        <div class="d-flex justify-content-between"><span>Order Payments</span><strong>Rs. {{ number_format($summary['paymentIncome'], 0) }}</strong></div>
                        <div class="d-flex justify-content-between"><span>Manual Income</span><strong>Rs. {{ number_format($summary['manualIncome'], 0) }}</strong></div>
                        <div class="d-flex justify-content-between"><span>Order Advances</span><strong>Rs. {{ number_format($summary['ordersAdvance'], 0) }}</strong></div>
                        <div class="d-flex justify-content-between"><span>Booked Balance</span><strong>Rs. {{ number_format($summary['ordersBalance'], 0) }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Current Risk</div>
                    <div class="d-grid gap-2">
                        <div class="d-flex justify-content-between"><span>Pending Balance</span><strong class="text-danger">Rs. {{ number_format($summary['currentPendingBalance'], 0) }}</strong></div>
                        <div class="d-flex justify-content-between"><span>Overdue Orders</span><strong class="text-danger">{{ number_format($summary['overdueOrders']) }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Payment Methods</div>
                    @foreach ($paymentMethods as $method => $amount)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ ucwords(str_replace('_', ' ', $method)) }}</span>
                            <strong>Rs. {{ number_format($amount, 0) }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Order Status Breakdown</div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>Status</th><th class="text-end">Orders</th></tr></thead>
                            <tbody>
                            @foreach ($statusBreakdown as $status => $count)
                                <tr>
                                    <td>{{ ucfirst($status) }}</td>
                                    <td class="text-end fw-semibold">{{ number_format($count) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Expense Categories</div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>Category</th><th class="text-end">Amount</th></tr></thead>
                            <tbody>
                            @foreach ($expenseCategories as $category => $amount)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $category)) }}</td>
                                    <td class="text-end fw-semibold">Rs. {{ number_format($amount, 0) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Top Customers</div>
                    @forelse ($topCustomers as $row)
                        <div class="record-card">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <p class="record-card-title">{{ $row['customer']?->name ?: 'Unknown Customer' }}</p>
                                    <div class="record-summary">{{ $row['orders_count'] }} orders · Balance Rs. {{ number_format($row['balance_amount'], 0) }}</div>
                                </div>
                                <div class="fw-semibold">Rs. {{ number_format($row['total_amount'], 0) }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No customer orders in this period.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Recent Payments</div>
                    @forelse ($recentPayments as $payment)
                        <div class="record-card">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <p class="record-card-title">{{ $payment->order?->order_no }} - {{ $payment->order?->customer?->name }}</p>
                                    <div class="record-summary">{{ $payment->payment_date?->format('d M Y') }} · {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</div>
                                </div>
                                <div class="fw-semibold text-success">Rs. {{ number_format((float) $payment->amount, 0) }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No payments found in this period.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
