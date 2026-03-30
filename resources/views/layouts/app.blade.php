<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tailor Shop Management')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        (function () {
            var savedTheme = localStorage.getItem('tailor-theme');
            var theme = savedTheme === 'dark' ? 'dark' : 'light';

            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <style>
        :root {
            --bg-color: #f4efe8;
            --bg-gradient-start: #fbf8f3;
            --bg-gradient-accent: rgba(185, 137, 68, .12);
            --text-color: #1f2a37;
            --card-bg: #ffffff;
            --border-color: #e7dccd;
            --input-bg: #ffffff;
            --input-text: #1f2a37;
            --table-bg: rgba(255, 255, 255, .72);
            --table-text: #1f2a37;
            --muted-text: #6b7280;
            --navbar-bg: #182633;
            --sidebar-bg: linear-gradient(180deg, #243443 0%, #182633 100%);
            --btn-secondary-bg: #f4ede2;
            --btn-secondary-text: #425466;
            --btn-outline-border: #c8b59b;
            --btn-outline-hover-bg: #eadcc8;
            --btn-outline-hover-text: #1f2a37;
            --link-color: #8e6630;
            --link-hover-color: #6d4b1f;
            --shadow-color: rgba(24, 38, 51, .06);
            --surface-raised: #f9f4ec;
            --surface-subtle: #f8f4ee;
            --surface-soft: rgba(255, 255, 255, .72);
            --surface-border-strong: rgba(143, 102, 48, .14);
            --table-hover: rgba(185, 137, 68, .08);
            --alert-success-bg: #e8f6ef;
            --alert-success-text: #1f6b46;
            --alert-danger-bg: #fbe9ea;
            --alert-danger-text: #9b1c31;
            --input-placeholder: #8b95a1;
            --shop-accent: #b98944;
            --shop-accent-dark: #8e6630;
            --shop-nav: #182633;
            --btn-close-filter: none;
        }
        [data-theme="dark"] {
            --bg-color: #111821;
            --bg-gradient-start: #18232f;
            --bg-gradient-accent: rgba(185, 137, 68, .18);
            --text-color: #e7edf5;
            --card-bg: #18212c;
            --border-color: #314153;
            --input-bg: #101922;
            --input-text: #edf2f7;
            --table-bg: rgba(24, 33, 44, .82);
            --table-text: #e7edf5;
            --muted-text: #9eb0c4;
            --navbar-bg: #0f1720;
            --sidebar-bg: linear-gradient(180deg, #131d29 0%, #0f1720 100%);
            --btn-secondary-bg: #223141;
            --btn-secondary-text: #e7edf5;
            --btn-outline-border: #4a627b;
            --btn-outline-hover-bg: #314153;
            --btn-outline-hover-text: #ffffff;
            --link-color: #f2c98a;
            --link-hover-color: #ffdca6;
            --shadow-color: rgba(0, 0, 0, .34);
            --surface-raised: #1b2734;
            --surface-subtle: #223141;
            --surface-soft: rgba(26, 36, 49, .92);
            --surface-border-strong: rgba(242, 201, 138, .18);
            --table-hover: rgba(242, 201, 138, .09);
            --alert-success-bg: rgba(27, 107, 70, .18);
            --alert-success-text: #8fe1b2;
            --alert-danger-bg: rgba(155, 28, 49, .2);
            --alert-danger-text: #ffb4c0;
            --input-placeholder: #7f8ea0;
            --btn-close-filter: invert(1) grayscale(1) brightness(2);
        }
        html,
        body {
            background:
                radial-gradient(circle at top right, var(--bg-gradient-accent), transparent 28%),
                linear-gradient(180deg, var(--bg-gradient-start) 0%, var(--bg-color) 100%);
            color: var(--text-color);
            transition: background-color .2s ease, color .2s ease, border-color .2s ease, box-shadow .2s ease;
        }
        a {
            color: var(--link-color);
        }
        a:hover,
        a:focus {
            color: var(--link-hover-color);
        }
        .text-muted {
            color: var(--muted-text) !important;
        }
        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid rgba(255, 255, 255, .06);
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
            background: var(--card-bg);
            border: 1px solid var(--surface-border-strong);
            border-radius: 1.25rem;
            box-shadow: 0 20px 45px var(--shadow-color);
        }
        .page-header {
            border-bottom: 1px solid rgba(31, 42, 55, .08);
        }
        .metric-card {
            background: linear-gradient(135deg, var(--card-bg) 0%, var(--surface-raised) 100%);
        }
        .metric-label {
            color: var(--muted-text);
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .72rem;
            font-weight: 700;
        }
        .section-title {
            font-size: 1rem;
            font-weight: 700;
        }
        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--table-text);
            --bs-table-border-color: var(--border-color);
            --bs-table-hover-bg: var(--table-hover);
            --bs-table-hover-color: var(--table-text);
            margin-bottom: 0;
        }
        .table-responsive {
            background: var(--table-bg);
            border-radius: 1.25rem;
        }
        .table > :not(caption) > * > * {
            padding: 1rem;
            vertical-align: middle;
            background-color: transparent;
            color: var(--table-text);
            border-bottom-color: var(--border-color);
        }
        .table thead th {
            color: var(--muted-text);
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            white-space: nowrap;
        }
        .card-header,
        .card-footer {
            background: transparent !important;
            color: var(--text-color);
            border-color: var(--border-color) !important;
        }
        .form-label {
            font-weight: 600;
            color: var(--text-color);
        }
        .form-control,
        .form-select {
            border-radius: .9rem;
            border-color: var(--border-color);
            background-color: var(--input-bg);
            color: var(--input-text);
            min-height: 3rem;
        }
        .form-control::placeholder {
            color: var(--input-placeholder);
        }
        .form-control:focus,
        .form-select:focus {
            border-color: rgba(185, 137, 68, .75);
            box-shadow: 0 0 0 .25rem rgba(185, 137, 68, .18);
        }
        .form-control:disabled,
        .form-control[readonly],
        .form-select:disabled {
            background-color: var(--surface-subtle);
            color: var(--muted-text);
            opacity: 1;
        }
        textarea.form-control {
            min-height: auto;
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
        .btn-outline-secondary,
        .btn-outline-dark,
        .theme-toggle-btn {
            color: var(--btn-secondary-text);
            border-color: var(--btn-outline-border);
            background: transparent;
        }
        .btn-outline-secondary:hover,
        .btn-outline-secondary:focus,
        .btn-outline-dark:hover,
        .btn-outline-dark:focus,
        .theme-toggle-btn:hover,
        .theme-toggle-btn:focus {
            color: var(--btn-outline-hover-text);
            border-color: var(--btn-outline-hover-bg);
            background: var(--btn-outline-hover-bg);
        }
        .btn-close {
            filter: var(--btn-close-filter);
        }
        .balance-summary {
            background: var(--surface-raised);
            border: 1px solid var(--border-color);
        }
        .list-chip {
            background: var(--surface-subtle);
            border: 1px solid var(--border-color);
            border-radius: 999px;
            color: var(--muted-text);
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
            color: var(--muted-text);
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
            background: linear-gradient(180deg, var(--card-bg) 0%, var(--surface-raised) 100%);
            border: 1px solid var(--border-color);
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
            background: var(--surface-soft);
        }
        .slip-section-title {
            font-size: .9rem;
            font-weight: 700;
            color: var(--shop-accent-dark);
            margin-bottom: 1rem;
        }
        .slip-kpi {
            border-radius: 1rem;
            background: var(--card-bg);
            border: 1px solid var(--surface-border-strong);
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
        .alert-success {
            background: var(--alert-success-bg);
            color: var(--alert-success-text);
        }
        .alert-danger {
            background: var(--alert-danger-bg);
            color: var(--alert-danger-text);
        }
        .border,
        .border-top,
        .border-bottom,
        .border-start,
        .border-end {
            border-color: var(--border-color) !important;
        }
        .bg-white,
        .bg-light,
        .bg-light-subtle {
            background-color: var(--surface-raised) !important;
            color: var(--text-color) !important;
        }
        .pagination {
            --bs-pagination-bg: var(--card-bg);
            --bs-pagination-color: var(--text-color);
            --bs-pagination-border-color: var(--border-color);
            --bs-pagination-hover-bg: var(--surface-raised);
            --bs-pagination-hover-color: var(--text-color);
            --bs-pagination-hover-border-color: var(--border-color);
            --bs-pagination-focus-bg: var(--surface-raised);
            --bs-pagination-focus-color: var(--text-color);
            --bs-pagination-focus-box-shadow: 0 0 0 .2rem rgba(185, 137, 68, .18);
            --bs-pagination-active-bg: var(--shop-accent);
            --bs-pagination-active-border-color: var(--shop-accent);
            --bs-pagination-disabled-bg: var(--surface-subtle);
            --bs-pagination-disabled-color: var(--muted-text);
        }
        @media print {
            html,
            body {
                background: #ffffff !important;
                color: #111111 !important;
            }
            .sidebar,
            .page-header .theme-toggle-btn {
                display: none !important;
            }
            .card-soft,
            .surface-panel,
            .metric-card,
            .table-responsive,
            .slip-sheet,
            .slip-section,
            .slip-kpi,
            .bg-white,
            .bg-light,
            .bg-light-subtle {
                background: #ffffff !important;
                color: #111111 !important;
                box-shadow: none !important;
            }
            .table,
            .table > :not(caption) > * > * {
                color: #111111 !important;
                border-color: #d1d5db !important;
            }
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
                <div class="d-flex flex-wrap gap-2 justify-content-md-end align-items-center">
                    <button type="button" class="btn theme-toggle-btn" id="theme-toggle" aria-label="Toggle light and dark theme">
                        <span id="theme-toggle-label">Dark Mode</span>
                    </button>
                    @yield('page-actions')
                </div>
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
<script>
    (function () {
        var storageKey = 'tailor-theme';
        var root = document.documentElement;
        var toggleButton = document.getElementById('theme-toggle');
        var toggleLabel = document.getElementById('theme-toggle-label');

        function getTheme() {
            var savedTheme = localStorage.getItem(storageKey);
            return savedTheme === 'dark' ? 'dark' : 'light';
        }

        function applyTheme(theme) {
            root.setAttribute('data-theme', theme);

            if (!toggleButton || !toggleLabel) {
                return;
            }

            var isDark = theme === 'dark';
            toggleLabel.textContent = isDark ? 'Light Mode' : 'Dark Mode';
            toggleButton.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            toggleButton.setAttribute('title', isDark ? 'Switch to light mode' : 'Switch to dark mode');
        }

        applyTheme(getTheme());

        if (toggleButton) {
            toggleButton.addEventListener('click', function () {
                var nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';

                localStorage.setItem(storageKey, nextTheme);
                applyTheme(nextTheme);
            });
        }
    })();
</script>
@stack('scripts')
</body>
</html>

