@extends('layouts.sneat')

@section('title', 'Product Master - Imported Data')

@push('styles')
<style>
    /* ─── Google Fonts ─── */
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap');

    :root {
        --pm-primary:       #4361ee;
        --pm-primary-dark:  #3251d4;
        --pm-accent:        #f72585;
        --pm-success:       #06d6a0;
        --pm-warning:       #ffd166;
        --pm-danger:        #ef476f;
        --pm-info:          #118ab2;
        --pm-surface:       #ffffff;
        --pm-bg:            #f4f5fb;
        --pm-border:        #e2e5f0;
        --pm-text:          #1e2740;
        --pm-muted:         #6b7593;
        --pm-thead-bg:      #1e2740;
        --pm-thead-text:    #e8ecff;
        --pm-row-even:      #f8f9ff;
        --pm-row-hover:     #eef1ff;
        --pm-radius:        12px;
        --pm-radius-sm:     8px;
        --pm-shadow:        0 4px 24px rgba(67,97,238,.08);
        --pm-shadow-lg:     0 8px 40px rgba(67,97,238,.14);
        --font-body:        'DM Sans', sans-serif;
        --font-mono:        'DM Mono', monospace;
    }

    body { font-family: var(--font-body); background: var(--pm-bg); color: var(--pm-text); }

    /* ── Page wrapper ── */
    .pm-page { padding: 1.5rem 0; }

    /* ── Page header ── */
    .pm-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .pm-header-left h1 {
        font-size: 1.35rem;
        font-weight: 600;
        color: var(--pm-text);
        margin: 0;
        letter-spacing: -.3px;
    }
    .pm-header-left p {
        font-size: .82rem;
        color: var(--pm-muted);
        margin: .2rem 0 0;
    }
    .pm-badge-compat {
        font-size: .7rem;
        font-weight: 600;
        background: #e8ecff;
        color: var(--pm-primary);
        border-radius: 20px;
        padding: .3rem .75rem;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    /* ── Cards ── */
    .pm-card {
        background: var(--pm-surface);
        border-radius: var(--pm-radius);
        box-shadow: var(--pm-shadow);
        border: 1px solid var(--pm-border);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .pm-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--pm-border);
        background: #fafbff;
        display: flex;
        align-items: center;
        gap: .6rem;
    }
    .pm-card-header h5 {
        margin: 0;
        font-size: .92rem;
        font-weight: 600;
        color: var(--pm-text);
    }
    .pm-card-header .icon-wrap {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: var(--pm-primary);
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: .95rem;
        flex-shrink: 0;
    }
    .pm-card-header .icon-wrap.green  { background: var(--pm-success); }
    .pm-card-header .icon-wrap.orange { background: #fd9e02; }
    .pm-card-body { padding: 1.25rem; }

    /* ── Import form ── */
    .pm-file-zone {
        border: 2px dashed var(--pm-border);
        border-radius: var(--pm-radius-sm);
        padding: 1.5rem;
        text-align: center;
        transition: border-color .2s, background .2s;
        cursor: pointer;
        background: #fafbff;
    }
    .pm-file-zone:hover { border-color: var(--pm-primary); background: #f0f3ff; }
    .pm-file-zone input[type="file"] {
        position: absolute; opacity: 0; width: 0; height: 0;
    }
    .pm-file-zone .file-label {
        display: flex; flex-direction: column; align-items: center; gap: .5rem;
    }
    .pm-file-zone .file-label i { font-size: 2rem; color: var(--pm-primary); }
    .pm-file-zone .file-label span { font-size: .88rem; color: var(--pm-muted); }
    .pm-file-zone .file-name {
        margin-top: .5rem;
        font-size: .82rem;
        font-weight: 500;
        color: var(--pm-primary);
    }
    .form-check-input:checked { background-color: var(--pm-primary); border-color: var(--pm-primary); }

    /* ── Action buttons ── */
    .pm-btn {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .5rem 1.1rem;
        border-radius: var(--pm-radius-sm);
        font-size: .85rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all .18s;
        text-decoration: none;
    }
    .pm-btn-primary   { background: var(--pm-primary); color: #fff; }
    .pm-btn-primary:hover { background: var(--pm-primary-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(67,97,238,.3); }
    .pm-btn-outline   { background: #fff; color: var(--pm-primary); border: 1.5px solid var(--pm-primary); }
    .pm-btn-outline:hover { background: var(--pm-primary); color: #fff; }
    .pm-btn-success   { background: var(--pm-success); color: #fff; }
    .pm-btn-success:hover { background: #05b88a; color: #fff; }
    .pm-btn-sm { padding: .3rem .65rem; font-size: .78rem; }

    /* ── Stats row ── */
    .pm-stats {
        display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1rem;
    }
    .pm-stat-chip {
        display: inline-flex; align-items: center; gap: .4rem;
        background: #fff;
        border: 1px solid var(--pm-border);
        border-radius: 20px;
        padding: .3rem .85rem;
        font-size: .8rem;
        color: var(--pm-text);
        font-weight: 500;
    }
    .pm-stat-chip .dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--pm-primary);
    }
    .pm-stat-chip .dot.green  { background: var(--pm-success); }
    .pm-stat-chip .dot.orange { background: #fd9e02; }

    /* ── Table card ── */
    .pm-table-card {
        background: var(--pm-surface);
        border-radius: var(--pm-radius);
        box-shadow: var(--pm-shadow);
        border: 1px solid var(--pm-border);
        overflow: hidden;
    }
    .pm-table-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: .9rem 1.25rem;
        background: #fafbff;
        border-bottom: 1px solid var(--pm-border);
        gap: .75rem;
        flex-wrap: wrap;
    }
    .pm-table-title {
        display: flex; align-items: center; gap: .5rem;
        font-size: .92rem; font-weight: 600; color: var(--pm-text);
    }
    .pm-table-title i { color: var(--pm-primary); }
    .pm-table-actions { display: flex; align-items: center; gap: .5rem; }

    /* ── Table ── */
    .pm-table-wrap { overflow-x: auto; }
    .pm-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .82rem;
    }
    .pm-table thead tr:first-child th {
        background: var(--pm-thead-bg);
        color: var(--pm-thead-text);
        font-weight: 600;
        padding: .65rem .9rem;
        white-space: nowrap;
        border-bottom: none;
        letter-spacing: .2px;
        border-right: 1px solid rgba(255,255,255,.07);
    }
    .pm-table thead tr:last-child th {
        background: #263050;
        color: #c5ccee;
        font-weight: 500;
        padding: .45rem .9rem;
        white-space: nowrap;
        font-size: .78rem;
        letter-spacing: .2px;
        border-right: 1px solid rgba(255,255,255,.07);
    }
    .pm-table thead th a {
        color: inherit !important;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
    }
    .pm-table thead th a:hover { color: var(--pm-warning) !important; }
    .pm-table tbody tr { border-bottom: 1px solid var(--pm-border); transition: background .12s; }
    .pm-table tbody tr:nth-child(even) { background: var(--pm-row-even); }
    .pm-table tbody tr:hover { background: var(--pm-row-hover); }
    .pm-table tbody td {
        padding: .55rem .9rem;
        vertical-align: middle;
        color: var(--pm-text);
    }
    .pm-table tbody td:last-child { white-space: nowrap; }

    /* ── Code chip ── */
    .code-chip {
        font-family: var(--font-mono);
        font-size: .78rem;
        background: #e8ecff;
        color: var(--pm-primary);
        border-radius: 5px;
        padding: .15rem .45rem;
        font-weight: 500;
    }

    /* ── Category pill ── */
    .cat-pill {
        font-size: .73rem;
        font-weight: 600;
        padding: .18rem .6rem;
        border-radius: 20px;
        background: #e9f7f0;
        color: #1a7a57;
        white-space: nowrap;
    }

    /* ── Price ── */
    .price-val {
        font-family: var(--font-mono);
        font-size: .82rem;
        font-weight: 500;
        color: var(--pm-text);
    }
    .price-val.mrp { color: #c0392b; }
    .price-val.pp  { color: #1a7a57; }

    /* ── Tooltip ── */
    .pm-tooltip-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        cursor: pointer;
        max-width: 140px;
    }
    .pm-tooltip-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        max-width: 120px;
        border-bottom: 1.5px dashed #aab4e6;
        color: var(--pm-text);
    }
    .pm-tooltip-bubble {
        visibility: hidden;
        opacity: 0;
        position: absolute;
        bottom: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%);
        background: #1e2740;
        color: #e8ecff;
        padding: .6rem .9rem;
        border-radius: 8px;
        font-size: .78rem;
        line-height: 1.55;
        white-space: normal;
        width: 260px;
        word-break: break-word;
        box-shadow: 0 6px 20px rgba(0,0,0,.25);
        z-index: 9999;
        transition: opacity .2s, visibility .2s;
    }
    .pm-tooltip-bubble::after {
        content: '';
        position: absolute;
        top: 100%; left: 50%; transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: #1e2740;
    }
    .pm-tooltip-wrap:hover .pm-tooltip-bubble { visibility: visible; opacity: 1; }

    /* ── Action buttons in rows ── */
    .pm-row-actions { display: flex; gap: .3rem; align-items: center; }
    .pm-icon-btn {
        width: 28px; height: 28px;
        border-radius: 6px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .85rem;
        border: none;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
    }
    .pm-icon-btn.view    { background: #e0f3fb; color: var(--pm-info); }
    .pm-icon-btn.view:hover  { background: var(--pm-info); color: #fff; }
    .pm-icon-btn.edit    { background: #fff3cd; color: #856404; }
    .pm-icon-btn.edit:hover  { background: #ffc107; color: #fff; }
    .pm-icon-btn.del     { background: #fde8ec; color: var(--pm-danger); }
    .pm-icon-btn.del:hover   { background: var(--pm-danger); color: #fff; }

    /* ── Pagination ── */
    .pm-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .75rem 1.25rem;
        background: #fafbff;
        border-top: 1px solid var(--pm-border);
        font-size: .82rem;
        color: var(--pm-muted);
        flex-wrap: wrap;
        gap: .5rem;
    }
    .pm-footer .pagination { margin: 0; }
    .pm-footer .page-link {
        border-radius: 6px !important;
        font-size: .78rem;
        padding: .3rem .6rem;
        color: var(--pm-primary);
        border-color: var(--pm-border);
        margin: 0 1px;
    }
    .pm-footer .page-item.active .page-link {
        background: var(--pm-primary);
        border-color: var(--pm-primary);
        color: #fff;
    }

    /* ── Sheet tabs ── */
    .pm-sheet-tabs {
        display: flex;
        align-items: center;
        gap: .4rem;
        margin-top: .75rem;
    }
    .pm-sheet-tab {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .78rem; font-weight: 500;
        padding: .35rem .8rem;
        border-radius: 6px 6px 0 0;
        border: 1px solid var(--pm-border);
        border-bottom: none;
        cursor: pointer;
        transition: all .15s;
        background: #fff;
        color: var(--pm-muted);
    }
    .pm-sheet-tab.active {
        background: var(--pm-primary);
        color: #fff;
        border-color: var(--pm-primary);
    }

    /* ── Empty state ── */
    .pm-empty {
        text-align: center;
        padding: 4rem 2rem;
    }
    .pm-empty img { width: 90px; opacity: .45; margin-bottom: 1.25rem; }
    .pm-empty h5 { font-size: 1rem; font-weight: 600; color: var(--pm-text); margin-bottom: .35rem; }
    .pm-empty p  { font-size: .85rem; color: var(--pm-muted); }

    /* ── Alerts ── */
    .pm-alert {
        border: none;
        border-radius: var(--pm-radius-sm);
        font-size: .875rem;
        padding: .75rem 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: .6rem;
    }
    .pm-alert.success { background: #e9f7f0; color: #1a7a57; }
    .pm-alert.danger  { background: #fde8ec; color: #9b1c2e; }
    .pm-alert i { font-size: 1.1rem; margin-top: .05rem; }

    /* ── Sort icon ── */
    .sort-asc::after  { content: ' ↑'; font-size: .7em; opacity: .7; }
    .sort-desc::after { content: ' ↓'; font-size: .7em; opacity: .7; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .pm-stats  { display: none; }
        .pm-tooltip-bubble { width: 200px; }
    }

    
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    /* Clean up DataTable appearance to match your UI */
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter { margin-bottom: 1rem; font-size: 0.85rem; }
    .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { margin-top: 1rem; font-size: 0.82rem; }
    table.dataTable thead th { border-bottom: none !important; }
</style>
<style>
    /* 1. Table Container & Base Styles */
.table-responsive {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    background: #fff;
    border: 1px solid #eef2f7;
}

.table {
    margin-bottom: 0;
    font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
    color: #4a5568;
}

/* 2. Professional Deep Navy Header */
.table thead th {
    background-color: #1a237e !important; /* Deep Navy Blue */
    color: #ffffff !important;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding: 16px 20px;
    border: none;
    vertical-align: middle;
}

/* 3. Row & Cell Alignment */
.table tbody td {
    padding: 16px 20px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.875rem;
}

.table tbody tr:hover {
    background-color: #f8faff;
    transition: background 0.2s ease;
}

/* 4. Column Specific Polishing */
.table td:first-child { font-weight: 600; color: #1a237e; } /* Product Code */
.table td:nth-child(2) { font-weight: 500; color: #2d3748; } /* Product Name */

/* 5. Modern Pill Badges for Category */
.table td .badge {
    padding: 6px 12px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.7rem;
    text-transform: uppercase;
    background-color: #ebf4ff;
    color: #3182ce;
}

/* 6. Alignment Fixes */
.text-right, .text-end { text-align: right !important; }
.text-center { text-align: center !important; }

/* 7. Price Styling */
.table td:last-child {
    font-weight: 700;
    color: #2d3748;
}
    </style>
@endpush

@section('content')
<div class="pm-page">

    {{-- ── Page Header ── --}}
    <div class="pm-header">
        <div class="pm-header-left">
            <h1><i class="bx bx-package" style="color:var(--pm-primary);margin-right:.35rem;"></i>Product Master</h1>
            <p>Last 2-year imported data &mdash; Compatibility Mode</p>
        </div>
        <span class="pm-badge-compat">⚡ Compatibility Mode</span>
    </div>

    {{-- ── Alerts ── --}}
    @if(session('success'))
    <div class="pm-alert success">
        <i class="bx bx-check-circle"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif
    @if(session('error'))
    <div class="pm-alert danger">
        <i class="bx bx-error-circle"></i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    {{-- ── Import Card ── --}}
    <div class="pm-card">
        <div class="pm-card-header">
            <div class="icon-wrap"><i class="bx bx-import"></i></div>
            <h5>Import New Data</h5>
        </div>
        <div class="pm-card-body">
            <form action="{{ route('admin.medicines.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">
                            Excel / CSV File <span class="text-danger">*</span>
                        </label>
                        <div class="pm-file-zone" onclick="document.getElementById('import_file').click()">
                            <input type="file" id="import_file" name="import_file"
                                   accept=".xlsx,.xls,.csv" required
                                   onchange="showFileName(this)"
                                   class="@error('import_file') is-invalid @enderror">
                            <div class="file-label">
                                <i class="bx bx-cloud-upload"></i>
                                <span>Click to browse or drag & drop</span>
                                <span style="font-size:.75rem;">.xlsx · .xls · .csv</span>
                            </div>
                            <div class="file-name" id="file-name-display"></div>
                        </div>
                        @error('import_file')
                            <div class="text-danger mt-1" style="font-size:.8rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <div class="form-check mb-2" style="margin-top:1rem;">
                            <input class="form-check-input" type="checkbox" id="skip_duplicates" name="skip_duplicates" checked>
                            <label class="form-check-label" for="skip_duplicates" style="font-size:.85rem;">
                                Skip duplicate products
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="pm-btn pm-btn-primary">
                                <i class="bx bx-upload"></i> Import
                            </button>
                            <a href="{{ route('admin.medicines.import.template') }}" class="pm-btn pm-btn-outline">
                                <i class="bx bx-download"></i> Template
                            </a>
                            <a href="{{ route('admin.medicines.create') }}" class="pm-btn pm-btn-success">
                                <i class="bx bx-plus"></i> Add New
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Stats Chips ── --}}
    <div class="pm-stats">
        <div class="pm-stat-chip"><span class="dot"></span> {{ $medicines->total() }} Total Records</div>
        <div class="pm-stat-chip"><span class="dot green"></span> Page {{ $medicines->currentPage() }} of {{ $medicines->lastPage() }}</div>
        <div class="pm-stat-chip"><span class="dot orange"></span> Showing {{ $medicines->perPage() }} per page</div>
    </div>

    {{-- ── Data Table ── --}}
    <div class="pm-table-card">
        <div class="pm-table-header">
            <div class="pm-table-title">
                <i class="bx bx-spreadsheet" style="font-size:1.1rem;"></i>
                Product Inventory Master
            </div>
            <div class="pm-table-actions">
    <span style="font-size:.78rem;color:var(--pm-muted); margin-right: 10px;">
        {{ $medicines->total() }} records found
    </span>
</div>
        </div>

        @if($medicines->count() > 0)
        <div class="pm-table-wrap">
            <table class="pm-table" id="productMasterTable">
    <thead>
        <tr>
            <th rowspan="2" class="align-middle" style="min-width:100px;">Product Code</th>
            <th rowspan="2" class="align-middle" style="min-width:180px;">Product Name</th>
            <th rowspan="2" class="align-middle" style="min-width:100px;">Category</th>
            <th rowspan="2" class="align-middle" style="min-width:150px;">Supplier Name</th>
            <th rowspan="2" class="align-middle" style="min-width:110px;">Supplier Code</th>
            <th colspan="2" class="text-center" style="border-bottom: 1px solid rgba(255,255,255,0.1) !important;">Units</th>
            <th rowspan="2" class="align-middle text-end" style="min-width:90px;">MRP</th>
            <th rowspan="2" class="align-middle text-end" style="min-width:100px;">Purchase Price</th>
            <th rowspan="2" class="align-middle" style="min-width:130px;">Alt Supplier Codes</th>
            <th rowspan="2" class="align-middle" style="min-width:140px;">Generic Name</th>
            <th rowspan="2" class="align-middle text-center" style="min-width:100px;">Actions</th>
        </tr>
        <tr>
            <th class="text-center" style="background: #263050; font-size: .75rem;">Pur</th>
            <th class="text-center" style="background: #263050; font-size: .75rem;">Sale</th>
        </tr>
    </thead>
    <tbody>
        @foreach($medicines as $medicine)
        <tr>
            <td><span class="code-chip">{{ $medicine->product_code ?? 'N/A' }}</span></td>
            <td style="font-weight:600; color: #2c3e50;">{{ $medicine->name }}</td>
            <td><span class="cat-pill">{{ $medicine->category ?? 'General' }}</span></td>
            <td>{{ $medicine->supplier_name ?? $medicine->manufacturer ?? '—' }}</td>
            <td><span class="code-chip" style="background:#f0faf5;color:#1a7a57;">{{ $medicine->supplier_code ?? '—' }}</span></td>
            <td class="text-center" style="color:var(--pm-muted);">{{ $medicine->purchase_unit ?? '1' }}</td>
            <td class="text-center" style="color:var(--pm-muted);">{{ $medicine->sale_unit ?? '1' }}</td>
            <td class="text-end"><span class="price-val mrp">₹{{ number_format($medicine->price, 2) }}</span></td>
            <td class="text-end"><span class="price-val pp">₹{{ number_format($medicine->purchase_price ?? 0, 2) }}</span></td>
            <td>
                @if($medicine->alt_supplier_codes)
                    <div class="pm-tooltip-wrap">
                        <span class="pm-tooltip-text">{{ Str::limit($medicine->alt_supplier_codes, 15) }}</span>
                        <div class="pm-tooltip-bubble">
                            <strong>Alt Supplier Codes</strong><br>{{ $medicine->alt_supplier_codes }}
                        </div>
                    </div>
                @else <span style="color:var(--pm-muted);">—</span> @endif
            </td>
            <td>
                @if($medicine->generic_name)
                    <div class="pm-tooltip-wrap">
                        <span class="pm-tooltip-text">{{ Str::limit($medicine->generic_name, 18) }}</span>
                        <div class="pm-tooltip-bubble">
                            <strong>Generic Name</strong><br>{{ $medicine->generic_name }}
                        </div>
                    </div>
                @else <span style="color:var(--pm-muted);">—</span> @endif
            </td>
            <td>
                <div class="pm-row-actions justify-content-center">
                    <a href="{{ route('admin.medicines.show', $medicine->id) }}" class="pm-icon-btn view" title="View"><i class="bx bx-show"></i></a>
                    <a href="{{ route('admin.medicines.edit', $medicine->id) }}" class="pm-icon-btn edit" title="Edit"><i class="bx bx-edit-alt"></i></a>
                    <form action="{{ route('admin.medicines.destroy', $medicine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="pm-icon-btn del"><i class="bx bx-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
        </div>

        {{-- Pagination Footer --}}
        <div class="pm-footer d-flex justify-content-between align-items-center px-4 py-3 border-top">
            <div class="text-muted">
                Showing <strong>{{ $medicines->firstItem() }}</strong> to <strong>{{ $medicines->lastItem() }}</strong> of <strong>{{ $medicines->total() }}</strong> entries
            </div>
            <div class="pagination-container">
                {{ $medicines->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>

        @else
        <div class="pm-empty">
            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076478.png" alt="No data">
            <h5>No Data Available</h5>
            <p>Import an Excel or CSV file above to populate this table.</p>
        </div>
        @endif
    </div>

    {{-- ── Sheet Tabs ── --}}
    <div class="pm-sheet-tabs">
        <div class="pm-sheet-tab active"><i class="bx bx-spreadsheet"></i> Sheet1</div>
        <div class="pm-sheet-tab"><i class="bx bx-spreadsheet"></i> Sheet2</div>
        <div style="flex:1; border-bottom:1px solid var(--pm-border);"></div>
        <span style="font-size:.75rem;color:var(--pm-muted);padding-bottom:.2rem;">Ready</span>
    </div>

</div>{{-- ── Modal ── --}}
<div class="modal fade" id="textModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border:none;border-radius:var(--pm-radius);overflow:hidden;">
            <div class="modal-header" style="background:var(--pm-primary);color:#fff;">
                <h5 class="modal-title" id="modalTitle" style="font-size:.95rem;font-weight:600;">Full Text</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div style="background:var(--pm-bg);border-radius:8px;padding:1rem;">
                    <p id="modalText" style="word-wrap:break-word;white-space:pre-wrap;font-size:.9rem;margin:0;"></p>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid var(--pm-border);">
                <button type="button" class="pm-btn pm-btn-outline" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ── File name display ──
    function showFileName(input) {
        const el = document.getElementById('file-name-display');
        el.textContent = input.files[0] ? '📎 ' + input.files[0].name : '';
    }

    // ── Search & Filter ──
    let searchTimeout;

    function performSearch() {
        const urlParams = new URLSearchParams(window.location.search);
        const params = new URLSearchParams();

        if (urlParams.has('sort')) params.set('sort', urlParams.get('sort'));
        if (urlParams.has('direction')) params.set('direction', urlParams.get('direction'));

        const base = '{{ route("admin.medicines.import.form") }}';
        window.location.href = params.toString() ? base + '?' + params : base;
    }
</script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // 1. Get current length from URL or default to 20
    const urlParams = new URLSearchParams(window.location.search);
    const currentLength = urlParams.get('length') || 20;

    // Destroy any existing DataTable instance
    if ($.fn.DataTable.isDataTable('#productMasterTable')) {
        $('#productMasterTable').DataTable().destroy();
    }

    // Initialize DataTable
    const table = $('#productMasterTable').DataTable({
        "paging": true,
        "ordering": true,
        "info": true,
        "searching": true,
        "lengthMenu": [10, 20, 50, 100],
        "pageLength": parseInt(currentLength), // Set initial length from URL
        "responsive": true,
        "dom": '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
        "columnDefs": [
            { "orderable": false, "targets": 11 } 
        ],
        "language": {
            "search": "",
            "searchPlaceholder": "Search records...",
            "lengthMenu": "Show _MENU_ entries",
        }
    });

    // 2. IMPORTANT: Listen for length change and reload page
    $('#productMasterTable').on('length.dt', function(e, settings, len) {
        const url = new URL(window.location.href);
        url.searchParams.set('length', len);
        url.searchParams.set('page', 1); // Reset to page 1 when length changes
        window.location.href = url.toString();
    });

    // Hide Laravel's default footer/stats as DataTable handles it
    $('.pm-stats').hide();
    $('.pm-footer').hide();
});
</script>
@endpush