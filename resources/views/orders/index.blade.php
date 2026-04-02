@extends('layouts.app')

@section('title', 'Orders')
@section('page-title', 'Orders')
@section('page-subtitle', 'Track every booking from booked to delivered, with overdue jobs easy to spot.')
@section('page-actions')
    <a href="{{ route('orders.create') }}" class="btn btn-dark">Book New Order</a>
@endsection

@section('content')
    <div class="filters-shell mb-4">
        <div class="card-body p-4">
            <div class="section-header section-header--stack">
                <div>
                    <div class="section-title">Order Register</div>
                    <p class="section-copy">Search by order number, customer, customer number, or phone and refine by status or date.</p>
                </div>
            </div>
            <form method="GET" class="row g-3 align-items-end" id="order-filter-form">
                <div class="col-md-3">
                    <label class="form-label" for="order_no">Search Orders</label>
                    <input type="text" id="order_no" name="order_no" value="{{ $filters['order_no'] }}" class="form-control" placeholder="Order #, customer name, customer no, or phone" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="customer_id">Customer</label>
                    <select id="customer_id" name="customer_id" class="form-select">
                        <option value="">All customers</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected((int) $filters['customer_id'] === $customer->id)>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="delivery_date">Delivery Date</label>
                    <input type="date" id="delivery_date" name="delivery_date" value="{{ $filters['delivery_date'] }}" class="form-control">
                </div>
                <div class="col-auto"><a href="{{ route('orders.index') }}" class="btn btn-outline-secondary" id="order-filter-reset">Reset</a></div>
            </form>
        </div>
    </div>

    <div id="order-results">
        @if ($orders->isEmpty())
            <div class="empty-state">
                <div class="empty-state-mark">&#9636;</div>
                <h3>No orders matched</h3>
                <p>Try another customer or date filter, or create a new booking slip.</p>
                <a href="{{ route('orders.create') }}" class="btn btn-dark btn-sm">Book New Order</a>
            </div>
        @else
            <div class="card card-soft desktop-table">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Delivery</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $order)
                            <tr class="{{ $order->isOverdue() ? 'overdue-row' : '' }}">
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="text-decoration-none fw-semibold">{{ $order->order_no }}</a>
                                    <div class="small text-muted">{{ $order->customer->customer_no ?: '-' }} · Qty {{ $order->quantity }}</div>
                                </td>
                                <td>
                                    {{ $order->customer->name }}
                                    <div class="small text-muted">{{ $order->customer->phone }}</div>
                                </td>
                                <td>
                                    @include('partials.status-badge', ['order' => $order])
                                    @if ($order->priority === 'urgent')
                                        <div class="small text-danger fw-semibold mt-1">Urgent</div>
                                    @endif
                                </td>
                                <td>
                                    {{ $order->delivery_date?->format('d M Y') }}
                                    @if ($order->isOverdue())
                                        <div class="small text-danger fw-semibold">Overdue</div>
                                    @endif
                                </td>
                                <td class="text-end">Rs. {{ number_format((float) $order->total_amount, 2) }}</td>
                                <td class="text-end {{ (float) $order->balance_amount > 0 ? 'text-danger fw-semibold' : 'text-success fw-semibold' }}">Rs. {{ number_format((float) $order->balance_amount, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mobile-record-grid">
                @foreach ($orders as $order)
                    <div class="record-card {{ $order->isOverdue() ? 'border border-danger-subtle' : '' }}">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <p class="record-card-title"><a href="{{ route('orders.show', $order) }}" class="text-decoration-none">{{ $order->order_no }}</a></p>
                                <div class="record-summary">{{ $order->customer->name }} · {{ $order->customer->phone }}</div>
                            </div>
                            @include('partials.status-badge', ['order' => $order])
                        </div>
                        <div class="record-meta">
                            <span class="list-chip">Delivery {{ $order->delivery_date?->format('d M Y') }}</span>
                            <span class="list-chip">Total Rs. {{ number_format((float) $order->total_amount, 2) }}</span>
                            <span class="list-chip">Balance Rs. {{ number_format((float) $order->balance_amount, 2) }}</span>
                        </div>
                        <div class="record-summary">{{ $order->order_type }}</div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4" id="order-pagination">{{ $orders->links() }}</div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        var form = document.getElementById('order-filter-form');
        var orderInput = document.getElementById('order_no');
        var customerSelect = document.getElementById('customer_id');
        var statusSelect = document.getElementById('status');
        var deliveryDateInput = document.getElementById('delivery_date');
        var results = document.getElementById('order-results');
        var resetLink = document.getElementById('order-filter-reset');
        var submitTimer;
        var activeController = null;

        if (!form || !orderInput || !customerSelect || !statusSelect || !deliveryDateInput || !results) {
            return;
        }

        function currentFilters() {
            return {
                order_no: orderInput.value.trim(),
                customer_id: customerSelect.value,
                status: statusSelect.value,
                delivery_date: deliveryDateInput.value
            };
        }

        var activeState = JSON.stringify(currentFilters());

        function buildUrl(filters, pageUrl) {
            var url = pageUrl ? new URL(pageUrl, window.location.origin) : new URL(form.action || window.location.href);
            if (!pageUrl) {
                url.search = '';
            }
            ['order_no', 'customer_id', 'status', 'delivery_date'].forEach(function (key) {
                if (filters[key]) {
                    url.searchParams.set(key, filters[key]);
                } else {
                    url.searchParams.delete(key);
                }
            });
            if (!pageUrl) {
                url.searchParams.delete('page');
            }
            return url;
        }

        function wirePaginationLinks() {
            results.querySelectorAll('#order-pagination a').forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    fetchResults(currentFilters(), link.href, true);
                });
            });
        }

        function fetchResults(filters, pageUrl, shouldFocusOrder) {
            var url = buildUrl(filters, pageUrl);
            if (activeController) {
                activeController.abort();
            }
            activeController = new AbortController();
            activeState = JSON.stringify(filters);

            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: activeController.signal
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Filter request failed.');
                    }
                    return response.text();
                })
                .then(function (html) {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(html, 'text/html');
                    var nextResults = doc.getElementById('order-results');
                    if (!nextResults) {
                        throw new Error('Order results could not be loaded.');
                    }
                    results.innerHTML = nextResults.innerHTML;
                    window.history.replaceState({}, '', url.toString());
                    wirePaginationLinks();
                    if (shouldFocusOrder) {
                        orderInput.focus();
                        var length = orderInput.value.length;
                        orderInput.setSelectionRange(length, length);
                    }
                })
                .catch(function (error) {
                    if (error.name === 'AbortError') {
                        return;
                    }
                    window.location.assign(url.toString());
                });
        }

        function triggerFilter(shouldFocusOrder) {
            var nextFilters = currentFilters();
            var nextState = JSON.stringify(nextFilters);
            window.clearTimeout(submitTimer);
            if (nextState === activeState) {
                return;
            }
            if (shouldFocusOrder) {
                submitTimer = window.setTimeout(function () {
                    fetchResults(nextFilters, null, true);
                }, 300);
                return;
            }
            fetchResults(nextFilters, null, false);
        }

        orderInput.addEventListener('input', function () {
            triggerFilter(true);
        });

        [customerSelect, statusSelect, deliveryDateInput].forEach(function (field) {
            field.addEventListener('change', function () {
                triggerFilter(false);
            });
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            window.clearTimeout(submitTimer);
            fetchResults(currentFilters(), null, true);
        });

        if (resetLink) {
            resetLink.addEventListener('click', function (event) {
                event.preventDefault();
                orderInput.value = '';
                customerSelect.value = '';
                statusSelect.value = '';
                deliveryDateInput.value = '';
                window.clearTimeout(submitTimer);
                fetchResults(currentFilters(), null, true);
            });
        }

        wirePaginationLinks();
    })();
</script>
@endpush
