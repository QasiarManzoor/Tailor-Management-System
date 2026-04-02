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
            --table-bg: rgba(255, 255, 255, .82);
            --table-text: #1f2a37;
            --muted-text: #6b7280;
            --sidebar-bg: linear-gradient(180deg, #243443 0%, #182633 100%);
            --btn-secondary-text: #425466;
            --btn-outline-border: #c8b59b;
            --btn-outline-hover-bg: #eadcc8;
            --btn-outline-hover-text: #1f2a37;
            --link-color: #8e6630;
            --link-hover-color: #6d4b1f;
            --shadow-color: rgba(24, 38, 51, .08);
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
            --btn-close-filter: none;
            --success-soft: #e8f6ef;
            --success-text: #1f6b46;
            --danger-soft: #fbe9ea;
            --danger-text: #9b1c31;
            --info-soft: #e8f0fb;
            --info-text: #295b9a;
            --warning-soft: #fbf1de;
            --warning-text: #8b5e1a;
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
            --table-bg: rgba(24, 33, 44, .9);
            --table-text: #e7edf5;
            --muted-text: #9eb0c4;
            --sidebar-bg: linear-gradient(180deg, #131d29 0%, #0f1720 100%);
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
            --success-soft: rgba(27, 107, 70, .18);
            --success-text: #8fe1b2;
            --danger-soft: rgba(155, 28, 49, .2);
            --danger-text: #ffb4c0;
            --info-soft: rgba(41, 91, 154, .18);
            --info-text: #95c3ff;
            --warning-soft: rgba(139, 94, 26, .24);
            --warning-text: #ffcf8b;
        }
        html,
        body {
            background:
                radial-gradient(circle at top right, var(--bg-gradient-accent), transparent 28%),
                linear-gradient(180deg, var(--bg-gradient-start) 0%, var(--bg-color) 100%);
            color: var(--text-color);
            transition: background-color .2s ease, color .2s ease, border-color .2s ease, box-shadow .2s ease;
        }
        body {
            min-height: 100vh;
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
        .container-fluid {
            --bs-gutter-x: .55rem;
        }
        .row.g-4 {
            --bs-gutter-x: .7rem;
            --bs-gutter-y: .7rem;
        }
        .row.g-3 {
            --bs-gutter-x: .55rem;
            --bs-gutter-y: .55rem;
        }
        .sidebar {
            min-height: 100vh;
            padding-top: .8rem !important;
            padding-bottom: .8rem !important;
            background: var(--sidebar-bg);
            border-right: 1px solid rgba(255, 255, 255, .06);
        }
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: .7rem;
            color: #fff;
            margin-bottom: .75rem;
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            padding: .35rem;
            border-radius: 1rem;
            background: #ffffff;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .12);
        }
        .brand-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
        .sidebar-meta {
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: .9rem;
            color: rgba(255, 255, 255, .72);
            font-size: .78rem;
            line-height: 1.45;
            padding: .55rem .65rem;
            margin-bottom: .8rem;
        }
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: .7rem;
            color: rgba(255, 255, 255, .78);
            border-radius: .85rem;
            padding: .55rem .65rem;
            font-weight: 600;
            transition: transform .18s ease, background-color .18s ease, color .18s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.12);
            transform: translateX(2px);
        }
        .nav-icon {
            width: 1.7rem;
            height: 1.7rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .65rem;
            background: rgba(255, 255, 255, .08);
            font-size: .92rem;
            flex-shrink: 0;
        }
        .nav-label {
            line-height: 1;
        }
        main.col-lg-10 {
            padding-top: .75rem !important;
            padding-bottom: .6rem !important;
        }
        .page-header {
            border-bottom: 1px solid rgba(31, 42, 55, .08);
            padding-bottom: .45rem !important;
            margin-bottom: .7rem !important;
            gap: .5rem !important;
        }
        .page-kicker {
            display: inline-block;
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--shop-accent-dark);
            margin-bottom: .2rem;
        }
        .page-title-text {
            margin: 0;
        }
        .card-soft,
        .surface-panel,
        .filters-shell,
        .record-card,
        .form-panel,
        .empty-state {
            background: var(--card-bg);
            border: 1px solid var(--surface-border-strong);
            border-radius: 1rem;
            box-shadow: 0 12px 28px var(--shadow-color);
        }
        .filters-shell {
            background: linear-gradient(180deg, var(--card-bg) 0%, var(--surface-raised) 100%);
        }
        .surface-caption,
        .stat-note {
            color: var(--muted-text);
            font-size: .87rem;
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
        .section-copy {
            color: var(--muted-text);
            font-size: .85rem;
            margin: .15rem 0 0;
        }
        .section-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .6rem;
            margin-bottom: .6rem;
        }
        .section-header--stack {
            flex-direction: column;
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
            border-radius: .95rem;
            overflow: hidden;
        }
        .table > :not(caption) > * > * {
            padding: .78rem;
            vertical-align: middle;
            background-color: transparent;
            color: var(--table-text);
            border-bottom-color: var(--border-color);
        }
        .table thead th {
            color: var(--muted-text);
            font-size: .76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            white-space: nowrap;
        }
        .table-heading-bilingual .bilingual-text {
            align-items: flex-start;
        }
        .form-label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: .35rem;
        }
        .form-control,
        .form-select {
            border-radius: .8rem;
            border-color: var(--border-color);
            background-color: var(--input-bg);
            color: var(--input-text);
            min-height: 2.75rem;
        }
        .form-control::placeholder {
            color: var(--input-placeholder);
        }
        .form-control:focus,
        .form-select:focus {
            border-color: rgba(185, 137, 68, .75);
            box-shadow: 0 0 0 .25rem rgba(185, 137, 68, .16);
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
            border-radius: .8rem;
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
        .theme-toggle-btn {
            width: 2.4rem;
            height: 2.4rem;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-brand-copy {
            flex: 1;
            min-width: 0;
        }
        .theme-toggle-icon {
            font-size: 1.05rem;
            line-height: 1;
        }
        .btn-close {
            filter: var(--btn-close-filter);
        }
        .text-danger {
            color: var(--danger-text) !important;
        }
        .text-success {
            color: var(--success-text) !important;
        }
        .text-primary-emphasis {
            color: var(--info-text) !important;
        }
        .text-warning-emphasis {
            color: var(--warning-text) !important;
        }
        .text-info-emphasis {
            color: var(--info-text) !important;
        }
        .text-secondary-emphasis,
        .text-dark-emphasis {
            color: var(--text-color) !important;
        }
        .bg-primary-subtle {
            background-color: var(--info-soft) !important;
        }
        .bg-warning-subtle {
            background-color: var(--warning-soft) !important;
        }
        .bg-info-subtle {
            background-color: var(--info-soft) !important;
        }
        .bg-secondary-subtle,
        .bg-dark-subtle {
            background-color: var(--surface-subtle) !important;
        }
        .bg-success-subtle {
            background-color: var(--success-soft) !important;
        }
        .bg-danger-subtle {
            background-color: var(--danger-soft) !important;
        }
        .border-danger-subtle {
            border-color: color-mix(in srgb, var(--danger-text) 34%, transparent) !important;
        }
        .list-chip {
            background: var(--surface-subtle);
            border: 1px solid var(--border-color);
            border-radius: 999px;
            color: var(--muted-text);
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .32rem .65rem;
            font-size: .8rem;
        }
        .pill-dot {
            width: .45rem;
            height: .45rem;
            border-radius: 999px;
            background: currentColor;
            opacity: .75;
            display: inline-block;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .38rem .72rem;
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            border: 1px solid transparent;
        }
        .status-pill--urgent {
            background: var(--danger-soft);
            color: var(--danger-text);
        }
        .metric-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(145deg, var(--card-bg) 0%, var(--surface-raised) 100%);
        }
        .metric-card::after {
            content: '';
            position: absolute;
            inset: auto -2rem -2rem auto;
            width: 5rem;
            height: 5rem;
            border-radius: 50%;
            background: rgba(185, 137, 68, .08);
        }
        .stat-card {
            padding: .9rem;
            min-height: 100%;
            color: var(--text-color);
        }
        .stat-card .metric-label,
        .stat-card .stat-note,
        .stat-card .stat-value,
        .record-card,
        .record-card-title,
        .record-card-title a,
        .record-summary,
        .empty-state,
        .empty-state h3,
        .balance-summary,
        .balance-summary .h3,
        #pending-balance-amount {
            color: var(--text-color);
        }
        .balance-summary .metric-label {
            color: var(--warning-text);
        }
        .balance-summary .list-chip {
            color: var(--text-color);
            background: rgba(255,255,255,.38);
        }
        [data-theme="dark"] .balance-summary .list-chip {
            background: rgba(255,255,255,.08);
        }
        .record-card-title a:hover,
        .record-card-title a:focus {
            color: var(--link-hover-color);
        }
        .stat-card--success {
            background: linear-gradient(180deg, var(--card-bg) 0%, color-mix(in srgb, var(--success-soft) 55%, var(--card-bg)) 100%);
        }
        .stat-card--warning {
            background: linear-gradient(180deg, var(--card-bg) 0%, color-mix(in srgb, var(--warning-soft) 58%, var(--card-bg)) 100%);
        }
        .stat-card--danger {
            background: linear-gradient(180deg, var(--card-bg) 0%, color-mix(in srgb, var(--danger-soft) 52%, var(--card-bg)) 100%);
        }
        .stat-card--info {
            background: linear-gradient(180deg, var(--card-bg) 0%, color-mix(in srgb, var(--info-soft) 52%, var(--card-bg)) 100%);
        }
        .stat-icon {
            width: 2.2rem;
            height: 2.2rem;
            border-radius: .8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-bottom: .55rem;
            background: rgba(255,255,255,.5);
            border: 1px solid rgba(143, 102, 48, .1);
        }
        .stat-value {
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1;
            margin-top: .25rem;
        }
        .record-card {
            padding: .8rem;
        }
        .record-card + .record-card {
            margin-top: .55rem;
        }
        .record-card-title {
            font-size: .98rem;
            font-weight: 700;
            margin: 0;
        }
        .record-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .35rem;
            margin-top: .4rem;
        }
        .record-summary {
            color: var(--muted-text);
            font-size: .83rem;
            margin-top: .35rem;
        }
        .record-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .35rem;
            margin-top: .6rem;
        }
        .mobile-record-grid {
            display: none;
        }
        .desktop-table {
            display: block;
        }
        .empty-state {
            padding: 1.1rem;
            text-align: center;
            background: linear-gradient(180deg, var(--card-bg) 0%, var(--surface-raised) 100%);
        }
        .empty-state-mark {
            width: 2.8rem;
            height: 2.8rem;
            margin: 0 auto .6rem;
            border-radius: .9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            background: var(--surface-subtle);
            color: var(--shop-accent-dark);
            border: 1px solid var(--border-color);
        }
        .empty-state h3 {
            font-size: 1rem;
            margin-bottom: .25rem;
        }
        .empty-state p {
            color: var(--muted-text);
            margin-bottom: .7rem;
        }
        .form-panel {
            padding: .95rem;
            background: linear-gradient(180deg, var(--card-bg) 0%, var(--surface-raised) 100%);
        }
        .form-panel-header {
            margin-bottom: .7rem;
        }
        .form-panel-title {
            margin: 0;
            font-size: .98rem;
            font-weight: 700;
        }
        .form-panel-copy {
            color: var(--muted-text);
            font-size: .83rem;
            margin: .15rem 0 0;
        }
        .section-grid-sep {
            margin-top: .7rem;
        }
        .balance-summary {
            background: linear-gradient(135deg, color-mix(in srgb, var(--warning-soft) 42%, var(--card-bg)) 0%, var(--surface-raised) 100%);
            border: 1px solid var(--border-color);
            border-radius: .95rem;
        }
        .overdue-row {
            background: rgba(220, 53, 69, .05);
        }
        .slip-sheet {
            background: linear-gradient(180deg, var(--card-bg) 0%, var(--surface-raised) 100%);
            border: 1px solid var(--border-color);
            border-radius: 1.25rem;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.65);
        }
        .slip-banner {
            background: linear-gradient(135deg, rgba(185, 137, 68, .14), rgba(143, 102, 48, .07));
            border-bottom: 1px solid rgba(143, 102, 48, .12);
        }
        .slip-section {
            border: 1px dashed rgba(143, 102, 48, .22);
            border-radius: .95rem;
            background: var(--surface-soft);
        }
        .slip-section-title {
            font-size: .9rem;
            font-weight: 700;
            color: var(--shop-accent-dark);
            margin-bottom: .8rem;
        }
        .slip-kpi {
            border-radius: .9rem;
            background: var(--card-bg);
            border: 1px solid var(--surface-border-strong);
            padding: .8rem;
        }
        .slip-kpi-value {
            font-size: 1rem;
            font-weight: 700;
        }
        .slip-notes {
            min-height: 6rem;
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
        .card-body.p-4,
        .card-header.p-4,
        .card-footer.p-4,
        .p-4.p-lg-5,
        .p-lg-5 {
            padding: .85rem !important;
        }
        .pt-3 {
            padding-top: .55rem !important;
        }
        .pb-2 {
            padding-bottom: .35rem !important;
        }
        .py-5,
        .py-4 {
            padding-top: .7rem !important;
            padding-bottom: .7rem !important;
        }
        .mb-5,
        .mb-4 {
            margin-bottom: .75rem !important;
        }
        .mb-3 {
            margin-bottom: .55rem !important;
        }
        .mt-4 {
            margin-top: .7rem !important;
        }
        .mt-3 {
            margin-top: .5rem !important;
        }
        .gap-4 {
            gap: .7rem !important;
        }
        .gap-3 {
            gap: .55rem !important;
        }
        .d-flex.gap-2,
        .d-inline-flex.gap-2 {
            gap: .4rem !important;
        }
        .rounded-4 {
            border-radius: .8rem !important;
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
            .sidebar {
                min-height: auto;
            }
            .mobile-record-grid {
                display: block;
            }
            .desktop-table {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="container-fluid">
    <div class="row g-0">
        <aside class="col-lg-2 px-3 sidebar">
            <div class="sidebar-brand">
                <span class="brand-badge"><img src="{{ asset($systemSettings->logo_path ?: 'images/shaq-logo.png') }}" alt="{{ $systemSettings->shop_name }} logo"></span>
                <div class="sidebar-brand-copy">
                    <div class="fw-semibold">{{ $systemSettings->shop_name }}</div>
                    <small class="text-white-50">{{ $systemSettings->shop_tagline }}</small>
                </div>
                <button type="button" class="btn theme-toggle-btn" id="theme-toggle" aria-label="Toggle light and dark theme">
                    <span class="theme-toggle-icon" id="theme-toggle-icon" aria-hidden="true">&#9789;</span>
                    <span class="visually-hidden" id="theme-toggle-label">Dark Mode</span>
                </button>
            </div>
            <div class="sidebar-meta">
                {{ $systemSettings->receipt_footer_company_name ?: 'ShaQ Technologies' }}
            </div>
            <nav class="nav flex-column gap-2">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span class="nav-icon">&#9783;</span><span class="nav-label">Dashboard</span></a>
                <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}"><span class="nav-icon">&#9786;</span><span class="nav-label">Customers</span></a>
                <a class="nav-link {{ request()->routeIs('measurements.*') ? 'active' : '' }}" href="{{ route('measurements.index') }}"><span class="nav-icon">&#9998;</span><span class="nav-label">Measurements</span></a>
                <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}"><span class="nav-icon">&#9636;</span><span class="nav-label">Orders</span></a>
                @if (auth()->user()?->isSuperAdmin())
                    <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}"><span class="nav-icon">&#9881;</span><span class="nav-label">Super Admin Dashboard</span></a>
                    <a class="nav-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}" href="{{ route('superadmin.users.index') }}"><span class="nav-icon">&#128101;</span><span class="nav-label">User Management</span></a>
                    <a class="nav-link {{ request()->routeIs('superadmin.settings.*') ? 'active' : '' }}" href="{{ route('superadmin.settings.edit') }}"><span class="nav-icon">&#9881;</span><span class="nav-label">System Settings</span></a>
                    <a class="nav-link {{ request()->routeIs('superadmin.activity-logs.*') ? 'active' : '' }}" href="{{ route('superadmin.activity-logs.index') }}"><span class="nav-icon">&#128221;</span><span class="nav-label">Activity Logs</span></a>
                @endif
            </nav>
            @auth
                <div class="sidebar-meta mt-3">
                    <div class="fw-semibold text-white">{{ auth()->user()->name }}</div>
                    <div class="small text-white-50">{{ auth()->user()->email }}</div>
                    <div class="small text-white-50 text-uppercase">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-light w-100 rounded-4">Sign Out</button>
                </form>
            @endauth
        </aside>
        <main class="col-lg-10 px-3 px-lg-4">
            @php
                $pageTitle = trim((string) $__env->yieldContent('page-title', 'Tailor Shop Management'));
                $pageSubtitle = trim((string) $__env->yieldContent('page-subtitle', 'Manage customers, measurements, orders, and payments in one place.'));
                $showPageCopy = $pageTitle !== '' || $pageSubtitle !== '';
            @endphp
            <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 pb-3 mb-4">
                @if ($showPageCopy)
                    <div>
                        <span class="page-kicker">Tailor Workspace</span>
                        @if ($pageTitle !== '')
                            <h1 class="h3 page-title-text">{{ $pageTitle }}</h1>
                        @endif
                        @if ($pageSubtitle !== '')
                            <p class="text-muted mb-0">{{ $pageSubtitle }}</p>
                        @endif
                    </div>
                @endif
                <div class="d-flex flex-wrap gap-2 justify-content-md-end align-items-center{{ $showPageCopy ? '' : ' ms-md-auto' }}">
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
        var toggleIcon = document.getElementById('theme-toggle-icon');

        function getTheme() {
            var savedTheme = localStorage.getItem(storageKey);
            return savedTheme === 'dark' ? 'dark' : 'light';
        }

        function applyTheme(theme) {
            root.setAttribute('data-theme', theme);

            if (!toggleButton || !toggleLabel || !toggleIcon) {
                return;
            }

            var isDark = theme === 'dark';
            toggleLabel.textContent = isDark ? 'Light Mode' : 'Dark Mode';
            toggleIcon.innerHTML = isDark ? '&#9728;' : '&#9789;';
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

    (function () {
        function sanitizeDecimal(value) {
            var sanitized = value.replace(/[^0-9.]/g, '');
            var parts = sanitized.split('.');

            if (parts.length > 2) {
                sanitized = parts.shift() + '.' + parts.join('');
            }

            return sanitized;
        }

        function sanitizeInteger(value) {
            return value.replace(/\D/g, '');
        }

        function sanitizePhone(value) {
            var sanitized = value.replace(/[^0-9+]/g, '');

            if (sanitized.startsWith('+')) {
                return '+' + sanitized.slice(1).replace(/\+/g, '');
            }

            return sanitized.replace(/\+/g, '');
        }

        document.querySelectorAll('[data-decimal-input]').forEach(function (input) {
            input.addEventListener('input', function () {
                input.value = sanitizeDecimal(input.value);
            });
        });

        document.querySelectorAll('[data-integer-input]').forEach(function (input) {
            input.addEventListener('input', function () {
                input.value = sanitizeInteger(input.value);
            });
        });

        document.querySelectorAll('[data-phone-input]').forEach(function (input) {
            input.addEventListener('input', function () {
                input.value = sanitizePhone(input.value);
            });
        });
    })();
</script>
@stack('scripts')
</body>
</html>









