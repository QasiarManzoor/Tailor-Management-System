@extends('layouts.app')

@section('title', 'Measurements')
@section('page-title', 'Measurements')
@section('page-subtitle', 'Browse saved slips with English and Urdu cues for quick shop use.')
@section('page-actions')
    <a href="{{ route('measurements.create') }}" class="btn btn-dark">New Measurement</a>
@endsection

@section('content')
    <div class="filters-shell mb-4">
        <div class="card-body p-4">
            <div class="section-header section-header--stack">
                <div>
                    <div class="section-title">Measurement Library</div>
                    <p class="section-copy">Search slips by title, customer, or phone without leaving the page.</p>
                </div>
            </div>
            <form method="GET" class="row g-3 align-items-end" id="measurement-search-form">
                <div class="col-md-6">
                    @include('partials.bilingual-text', ['key' => 'tailor.measurements.index.search', 'for' => 'search', 'tag' => 'label', 'class' => 'form-label'])
                    <input type="text" id="search" name="search" value="{{ $search }}" class="form-control" placeholder="Measurement title, customer name, or phone" autocomplete="off">
                </div>
                <div class="col-auto"><a href="{{ route('measurements.index') }}" class="btn btn-outline-secondary" id="measurement-search-reset">Reset</a></div>
            </form>
        </div>
    </div>

    <div id="measurement-results">
        @if ($measurements->isEmpty())
            <div class="empty-state">
                <div class="empty-state-mark">&#9998;</div>
                <h3>No measurements matched</h3>
                <p>Try another title or customer name, or create a fresh measurement slip.</p>
                <a href="{{ route('measurements.create') }}" class="btn btn-dark btn-sm">New Measurement</a>
            </div>
        @else
            <div class="card card-soft desktop-table">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th class="table-heading-bilingual">@include('partials.bilingual-text', ['key' => 'tailor.measurements.index.measurement'])</th>
                            <th class="table-heading-bilingual">@include('partials.bilingual-text', ['key' => 'tailor.common.customer'])</th>
                            <th class="table-heading-bilingual">@include('partials.bilingual-text', ['key' => 'tailor.measurements.index.key_sizes'])</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($measurements as $measurement)
                            <tr>
                                <td>
                                    <a href="{{ route('measurements.show', $measurement) }}" class="text-decoration-none fw-semibold">{{ $measurement->title }}</a>
                                    <div class="small text-muted">@include('partials.bilingual-text', ['key' => 'tailor.measurements.index.updated']) {{ $measurement->updated_at?->format('d M Y') }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('customers.show', $measurement->customer) }}" class="text-decoration-none">{{ $measurement->customer->name }}</a>
                                    <div class="small text-muted">{{ $measurement->customer->phone }}</div>
                                </td>
                                <td class="text-muted small">
                                    @include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.chest']) {{ $measurement->chest ?: '-' }},
                                    @include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.waist']) {{ $measurement->waist ?: '-' }},
                                    @include('partials.bilingual-text', ['key' => 'tailor.measurements.fields.shalwar_length']) {{ $measurement->shalwar_length ?: '-' }}
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('measurements.show', $measurement) }}" class="btn btn-sm btn-outline-dark">View</a>
                                        <a href="{{ route('measurements.copy', $measurement) }}" class="btn btn-sm btn-outline-dark">Copy</a>
                                        <a href="{{ route('measurements.edit', $measurement) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form method="POST" action="{{ route('measurements.destroy', $measurement) }}" onsubmit="return confirm('Delete this measurement?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mobile-record-grid">
                @foreach ($measurements as $measurement)
                    <div class="record-card">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <p class="record-card-title"><a href="{{ route('measurements.show', $measurement) }}" class="text-decoration-none">{{ $measurement->title }}</a></p>
                                <div class="record-summary">{{ $measurement->customer->name }} · {{ $measurement->customer->phone }}</div>
                            </div>
                            <span class="list-chip">{{ $measurement->updated_at?->format('d M Y') }}</span>
                        </div>
                        <div class="record-meta">
                            <span class="list-chip">Chest {{ $measurement->chest ?: '-' }}</span>
                            <span class="list-chip">Waist {{ $measurement->waist ?: '-' }}</span>
                            <span class="list-chip">Shalwar {{ $measurement->shalwar_length ?: '-' }}</span>
                        </div>
                        <div class="record-actions">
                            <a href="{{ route('measurements.show', $measurement) }}" class="btn btn-sm btn-outline-dark">View</a>
                            <a href="{{ route('measurements.edit', $measurement) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4" id="measurement-pagination">{{ $measurements->links() }}</div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        var form = document.getElementById('measurement-search-form');
        var searchInput = document.getElementById('search');
        var results = document.getElementById('measurement-results');
        var resetLink = document.getElementById('measurement-search-reset');
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
            results.querySelectorAll('#measurement-pagination a').forEach(function (link) {
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
                    var nextResults = doc.getElementById('measurement-results');
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
