<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Print' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ink: #1f2937;
            --muted: #6b7280;
            --line: #cbd5e1;
            --accent: #9a6b2f;
            --paper: #ffffff;
        }
        @page {
            size: A4;
            margin: 12mm;
        }
        body {
            background: #eef2f6;
            color: var(--ink);
            font-size: 14px;
        }
        .print-shell {
            max-width: 210mm;
            margin: 1rem auto;
            padding: 0;
        }
        .print-page {
            background: var(--paper);
            border: 1px solid #dbe4ee;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
            padding: 14mm;
        }
        .shop-header {
            border-bottom: 2px solid var(--accent);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        .shop-title {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .shop-subtitle {
            color: var(--muted);
        }
        .print-actions {
            display: flex;
            justify-content: flex-end;
            gap: .75rem;
            margin-bottom: 1rem;
        }
        .print-btn {
            border: 0;
            border-radius: .8rem;
            background: linear-gradient(135deg, #8e6630, #b98944);
            color: white;
            padding: .75rem 1.2rem;
            font-weight: 600;
        }
        .print-card {
            border: 1px solid var(--line);
            border-radius: .9rem;
            padding: 1rem;
            height: 100%;
            background: #fff;
        }
        .section-title {
            font-size: .95rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: .8rem;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .meta-label {
            color: var(--muted);
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .25rem;
        }
        .meta-value {
            font-size: 1rem;
            font-weight: 600;
        }
        .bilingual-label {
            display: inline-flex;
            align-items: baseline;
            flex-wrap: wrap;
            gap: .15rem;
        }
        .urdu-text,
        .ur-label {
            font-family: 'Noto Nastaliq Urdu', 'Noto Sans Arabic', sans-serif;
            direction: rtl;
            text-align: right;
            unicode-bidi: embed;
            line-height: 1.85;
            display: inline-block;
            font-weight: 600;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .8rem 1rem;
        }
        .measurement-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .85rem;
        }
        .measurement-box {
            border: 1px dashed var(--line);
            border-radius: .8rem;
            padding: .75rem;
            min-height: 78px;
        }
        .notes-box {
            border: 1px solid var(--line);
            border-radius: .9rem;
            min-height: 120px;
            padding: 1rem;
            white-space: pre-line;
        }
        .summary-table td,
        .summary-table th,
        .table td,
        .table th {
            border-color: var(--line);
            padding: .65rem .75rem;
        }
        .compact-receipt {
            font-size: 12px;
        }
        .compact-receipt .print-page {
            padding: 10mm;
        }
        .compact-receipt .shop-header {
            margin-bottom: .75rem;
            padding-bottom: .75rem;
        }
        .compact-receipt .print-card {
            padding: .75rem;
            border-radius: .75rem;
            break-inside: avoid;
            page-break-inside: avoid;
        }
        .compact-receipt .section-title {
            font-size: .8rem;
            margin-bottom: .55rem;
        }
        .compact-receipt .meta-label {
            font-size: .7rem;
            margin-bottom: .15rem;
        }
        .compact-receipt .meta-value {
            font-size: .9rem;
        }
        .compact-receipt .summary-table td,
        .compact-receipt .summary-table th,
        .compact-receipt .table td,
        .compact-receipt .table th {
            padding: .4rem .45rem;
            font-size: .78rem;
        }
        .compact-receipt .detail-grid {
            gap: .55rem .8rem;
        }
        .compact-receipt .measurement-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .45rem;
        }
        .compact-receipt .measurement-box {
            min-height: 0;
            padding: .45rem .5rem;
            border-style: solid;
        }
        .compact-receipt .notes-box {
            min-height: 64px;
            padding: .7rem;
            font-size: .82rem;
        }
        .compact-receipt .signature-line {
            margin-top: 1.75rem;
            width: 180px;
        }
        .print-muted {
            color: var(--muted);
        }
        .signature-line {
            border-top: 1px solid var(--line);
            margin-top: 3rem;
            padding-top: .35rem;
            width: 220px;
        }
        @media (max-width: 900px) {
            .measurement-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .detail-grid {
                grid-template-columns: 1fr;
            }
            .compact-receipt .measurement-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media print {
            body {
                background: white;
            }
            .print-shell {
                margin: 0;
                max-width: none;
            }
            .print-page {
                box-shadow: none;
                border: 0;
                padding: 0;
            }
            .print-actions,
            .no-print {
                display: none !important;
            }
            a[href]:after {
                content: none !important;
            }
        }
    </style>
    @stack('print-styles')
</head>
<body class="@yield('body-class')">
<div class="print-shell">
    <div class="print-actions no-print">
        <button type="button" class="print-btn" onclick="window.print()">Print</button>
        <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">Back</button>
    </div>
    <div class="print-page">
        <div class="shop-header d-flex justify-content-between align-items-start gap-3">
            <div>
                <div class="shop-title">Rashid Tailor Shop</div>
                <div class="shop-subtitle">Digital Tailor Management System</div>
            </div>
            <div class="text-end">
                <div class="meta-label">Document</div>
                <div class="meta-value">{{ $documentTitle ?? 'Print View' }}</div>
            </div>
        </div>
        @yield('print-content')
    </div>
</div>
</body>
</html>
