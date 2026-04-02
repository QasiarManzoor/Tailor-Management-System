@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page-subtitle', 'Search by name, customer number, or phone and open each customer slip in one click.')
@section('page-actions')
    <a href="{{ route('customers.create') }}" class="btn btn-dark">Add Customer</a>
@endsection

@section('content')
    <div class="filters-shell mb-4">
        <div class="card-body p-4">
            <div class="section-header section-header--stack">
                <div>
                    <div class="section-title">Customer Register</div>
                    <p class="section-copy">Find repeat clients quickly by customer number, name, or phone.</p>
                </div>
            </div>
            <form method="GET" class="row g-3 align-items-end" id="customer-search-form">
                <div class="col-md-6">
                    <label class="form-label" for="search">Customer Search</label>
                    <input type="text" id="search" name="search" value="{{ $search }}" class="form-control" placeholder="Search by customer number, name, phone, or alternate phone" autocomplete="off">
                </div>
                <div class="col-auto">
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary" id="customer-search-reset">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div id="customer-results">
        @if ($customers->isEmpty())
            <div class="empty-state">
                <div class="empty-state-mark">&#9786;</div>
                <h3>No customers matched</h3>
                <p>Try another name or number, or add a fresh customer profile.</p>
                <a href="{{ route('customers.create') }}" class="btn btn-dark btn-sm">Add Customer</a>
            </div>
        @else
            <div class="card card-soft desktop-table">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Number</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th class="text-center">Measurements</th>
                            <th class="text-center">Orders</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>
                                    <a href="{{ route('customers.show', $customer) }}" class="text-decoration-none fw-semibold">{{ $customer->name }}</a>
                                    <div class="small text-muted text-capitalize">{{ $customer->gender ?: 'Gender not set' }}</div>
                                </td>
                                <td><span class="fw-semibold">{{ $customer->customer_no ?: '-' }}</span></td>
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
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mobile-record-grid">
                @foreach ($customers as $customer)
                    <div class="record-card">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <p class="record-card-title"><a href="{{ route('customers.show', $customer) }}" class="text-decoration-none">{{ $customer->name }}</a></p>
                                <div class="record-summary">Customer # {{ $customer->customer_no ?: '-' }}</div>
                            </div>
                            <span class="list-chip">{{ $customer->measurements_count }} slips</span>
                        </div>
                        <div class="record-meta">
                            <span class="list-chip">{{ $customer->phone }}</span>
                            @if ($customer->alternate_phone)
                                <span class="list-chip">Alt {{ $customer->alternate_phone }}</span>
                            @endif
                            <span class="list-chip">{{ $customer->orders_count }} orders</span>
                        </div>
                        <div class="record-summary">{{ $customer->address ?: 'Address not added' }}</div>
                        <div class="record-actions">
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-dark">View</a>
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4" id="customer-pagination">{{ $customers->links() }}</div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        var form = document.getElementById('customer-search-form');
        var searchInput = document.getElementById('search');
        var results = document.getElementById('customer-results');
        var resetLink = document.getElementById('customer-search-reset');
        var submitTimer;
        var activeController = null;
        var activeQuery = searchInput ? searchInput.value.trim() : '';

        if (!form || !searchInput || !results) {
            return;
        }

        function buildUrl(query, pageUrl) {
            var url = pageUrl ? new URL(pageUrl, window.location.origin) : new URL(form.action || window.location.href);
            if (!pageUrl) {
                url.search = '';
            }
            if (query) {
                url.searchParams.set('search', query);
            } else {
                url.searchParams.delete('search');
            }
            if (!pageUrl) {
                url.searchParams.delete('page');
            }
            return url;
        }

        function wirePaginationLinks() {
            results.querySelectorAll('#customer-pagination a').forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    fetchResults(searchInput.value.trim(), link.href, true);
                });
            });
        }

        function fetchResults(query, pageUrl, shouldFocusInput) {
            var url = buildUrl(query, pageUrl);
            if (activeController) {
                activeController.abort();
            }
            activeController = new AbortController();
            activeQuery = query;

            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: activeController.signal
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Search request failed.');
                    }
                    return response.text();
                })
                .then(function (html) {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(html, 'text/html');
                    var nextResults = doc.getElementById('customer-results');
                    if (!nextResults) {
                        throw new Error('Search results could not be loaded.');
                    }
                    results.innerHTML = nextResults.innerHTML;
                    window.history.replaceState({}, '', url.toString());
                    wirePaginationLinks();
                    if (shouldFocusInput) {
                        searchInput.focus();
                        var length = searchInput.value.length;
                        searchInput.setSelectionRange(length, length);
                    }
                })
                .catch(function (error) {
                    if (error.name === 'AbortError') {
                        return;
                    }
                    window.location.assign(url.toString());
                });
        }

        searchInput.addEventListener('input', function () {
            var nextValue = searchInput.value.trim();
            window.clearTimeout(submitTimer);
            if (nextValue === activeQuery) {
                return;
            }
            submitTimer = window.setTimeout(function () {
                fetchResults(nextValue, null, true);
            }, 300);
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            window.clearTimeout(submitTimer);
            fetchResults(searchInput.value.trim(), null, true);
        });

        if (resetLink) {
            resetLink.addEventListener('click', function (event) {
                event.preventDefault();
                searchInput.value = '';
                window.clearTimeout(submitTimer);
                fetchResults('', null, true);
            });
        }

        wirePaginationLinks();
    })();
</script>
@endpush
