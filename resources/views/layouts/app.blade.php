<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tailor Shop Management')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --shop-bg: #f4efe8;
            --shop-panel: #ffffff;
            --shop-ink: #1f2a37;
            --shop-muted: #6b7280;
            --shop-line: #e7dccd;
            --shop-accent: #b98944;
            --shop-accent-dark: #8e6630;
            --shop-nav: #182633;
        }
        body {
            background:
                radial-gradient(circle at top right, rgba(185, 137, 68, .12), transparent 28%),
                linear-gradient(180deg, #fbf8f3 0%, var(--shop-bg) 100%);
            color: var(--shop-ink);
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #243443 0%, var(--shop-nav) 100%);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, .78);
            border-radius: 1rem;
            padding: .85rem 1rem;
            font-weight: 500;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,.12);
        }
        .brand-badge {
            background: linear-gradient(135deg, #f2d7a6, var(--shop-accent));
            color: #1b1b1b;
        }
        .card-soft,
        .surface-panel {
            background: var(--shop-panel);
            border: 1px solid rgba(143, 102, 48, .08);
            border-radius: 1.25rem;
            box-shadow: 0 20px 45px rgba(24, 38, 51, .06);
        }
        .page-header {
            border-bottom: 1px solid rgba(31, 42, 55, .08);
        }
        .metric-card {
            background: linear-gradient(135deg, #fff 0%, #f7efe2 100%);
        }
        .metric-label {
            color: var(--shop-muted);
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .72rem;
            font-weight: 700;
        }
        .section-title {
            font-size: 1rem;
            font-weight: 700;
        }
        .table > :not(caption) > * > * {
            padding: 1rem;
            vertical-align: middle;
        }
        .table thead th {
            color: var(--shop-muted);
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            white-space: nowrap;
        }
        .form-label {
            font-weight: 600;
            color: var(--shop-ink);
        }
        .form-control,
        .form-select {
            border-radius: .9rem;
            border-color: #d5dbe3;
            min-height: 3rem;
        }
        textarea.form-control {
            min-height: auto;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: rgba(185, 137, 68, .75);
            box-shadow: 0 0 0 .25rem rgba(185, 137, 68, .18);
        }
        .btn {
            border-radius: .9rem;
            font-weight: 600;
        }
        .btn-dark {
            background: linear-gradient(135deg, var(--shop-accent-dark), var(--shop-accent));
            border: 0;
        }
        .btn-dark:hover {
            background: linear-gradient(135deg, #7d5725, #a97836);
        }
        .list-chip {
            background: #f8f4ee;
            border: 1px solid var(--shop-line);
            border-radius: 999px;
            color: var(--shop-muted);
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .4rem .75rem;
            font-size: .85rem;
        }
        .overdue-row {
            background: rgba(220, 53, 69, .05);
        }
        .stat-note {
            color: var(--shop-muted);
            font-size: .9rem;
        }
        .bilingual-label,
        .bilingual-text {
            display: inline-flex;
            align-items: baseline;
            gap: .12rem;
            flex-wrap: wrap;
        }
        .urdu-text,
        .ur-label {
            font-family: 'Noto Nastaliq Urdu', 'Noto Sans Arabic', 'Segoe UI', Tahoma, sans-serif;
            font-weight: 600;
            direction: rtl;
            text-align: right;
            unicode-bidi: embed;
            display: inline-block;
            line-height: 1.8;
        }
        .slip-sheet {
            background: linear-gradient(180deg, #fffefc 0%, #fdf8f1 100%);
            border: 1px solid var(--shop-line);
            border-radius: 1.4rem;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.65);
        }
        .slip-banner {
            background: linear-gradient(135deg, rgba(185, 137, 68, .14), rgba(143, 102, 48, .07));
            border-bottom: 1px solid rgba(143, 102, 48, .12);
        }
        .slip-section {
            border: 1px dashed rgba(143, 102, 48, .22);
            border-radius: 1rem;
            background: rgba(255,255,255,.72);
        }
        .slip-section-title {
            font-size: .9rem;
            font-weight: 700;
            color: var(--shop-accent-dark);
            margin-bottom: 1rem;
        }
        .slip-kpi {
            border-radius: 1rem;
            background: #fff;
            border: 1px solid rgba(143, 102, 48, .14);
            padding: 1rem;
        }
        .slip-kpi-value {
            font-size: 1rem;
            font-weight: 700;
        }
        .slip-notes {
            min-height: 8rem;
        }
        .table-heading-bilingual .bilingual-text {
            align-items: flex-start;
        }
        @media (max-width: 991.98px) {
            .sidebar { min-height: auto; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="container-fluid">
    <div class="row g-0">
        <aside class="col-lg-2 px-3 py-4 sidebar">
            <div class="d-flex align-items-center gap-3 text-white mb-4">
                <span class="badge rounded-pill brand-badge px-3 py-2 fs-6">R</span>
                <div>
                    <div class="fw-semibold">Rashid Tailor Shop</div>
                    <small class="text-white-50">Digital order slip</small>
                </div>
            </div>
            <nav class="nav flex-column gap-2">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">Customers</a>
                <a class="nav-link {{ request()->routeIs('measurements.*') ? 'active' : '' }}" href="{{ route('measurements.index') }}">Measurements</a>
                <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">Orders</a>
            </nav>
        </aside>
        <main class="col-lg-10 px-3 px-lg-4 py-4 py-lg-5">
            <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 pb-3 mb-4">
                <div>
                    <h1 class="h3 mb-1">@yield('page-title', 'Tailor Shop Management')</h1>
                    <p class="text-muted mb-0">@yield('page-subtitle', 'Manage customers, measurements, orders, and payments in one place.')</p>
                </div>
                <div class="d-flex flex-wrap gap-2 justify-content-md-end">@yield('page-actions')</div>
            </div>

            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-4 alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4">
                    <div class="fw-semibold mb-2">Please review the highlighted fields.</div>
                    <div class="small">The form could not be saved until the required details are corrected.</div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
