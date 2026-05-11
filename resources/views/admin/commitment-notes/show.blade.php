@extends('layouts.sneat')

@section('title', 'Commitment Note #' . $commitmentNote->id)

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    --primary:       #5f6bfa;
    --primary-soft:  #eef0ff;
    --primary-glow:  rgba(95,107,250,0.18);
    --success:       #22c55e;
    --success-soft:  #f0fdf4;
    --warning:       #f59e0b;
    --warning-soft:  #fffbeb;
    --danger:        #ef4444;
    --danger-soft:   #fef2f2;
    --info:          #38bdf8;
    --info-soft:     #f0f9ff;
    --surface:       #ffffff;
    --surface-2:     #f8fafc;
    --border:        #e2e8f0;
    --text-primary:  #0f172a;
    --text-secondary:#64748b;
    --text-muted:    #94a3b8;
    --shadow-sm:     0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md:     0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.05);
    --radius:        12px;
    --radius-sm:     8px;
    --font:          'DM Sans', sans-serif;
    --font-mono:     'DM Mono', monospace;
}

.vn-page * { font-family: var(--font); box-sizing: border-box; }

.vn-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 12.5px; color: var(--text-muted); margin-bottom: 6px;
}
.vn-breadcrumb a { color: var(--primary); text-decoration: none; }
.vn-breadcrumb a:hover { text-decoration: underline; }
.vn-breadcrumb i { font-size: 14px; }

.vn-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 0 20px;
    border-bottom: 2px solid var(--border);
    margin-bottom: 28px;
}
.vn-header-left { display: flex; align-items: center; gap: 14px; }
.vn-header-icon {
    width: 48px; height: 48px;
    background: var(--info-soft);
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
}
.vn-header-icon i { font-size: 24px; color: var(--info); }
.vn-header-title { font-size: 20px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.3px; }
.vn-header-sub   { font-size: 13px; color: var(--text-muted); margin-top: 2px; font-family: var(--font-mono); }
.vn-badge-id {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--primary-soft); color: var(--primary);
    font-size: 12px; font-weight: 600; font-family: var(--font-mono);
    padding: 4px 12px; border-radius: 20px;
    border: 1px solid rgba(95,107,250,.2);
}

.vn-section {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    margin-bottom: 22px;
    overflow: hidden;
    animation: fadeSlideUp 0.35s ease both;
}
.vn-section:nth-child(2) { animation-delay: .05s; }
.vn-section:nth-child(3) { animation-delay: .10s; }

@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.vn-section-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    background: var(--surface-2);
}
.vn-section-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 600; color: var(--text-primary);
}
.vn-section-title i { font-size: 18px; color: var(--primary); }
.vn-section-body { padding: 20px; }

.vn-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.vn-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

.vn-field { display: flex; flex-direction: column; gap: 6px; }
.vn-label {
    font-size: 11.5px; font-weight: 600; color: var(--text-secondary);
    text-transform: uppercase; letter-spacing: .6px;
}
.vn-value {
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 10px 13px;
    font-size: 14px; font-weight: 500;
    color: var(--text-primary);
    min-height: 42px;
    display: flex; align-items: center;
}
.vn-value.mono { font-family: var(--font-mono); }
.vn-value.muted { color: var(--text-muted); font-weight: 400; font-style: italic; }
.vn-value.multiline { align-items: flex-start; min-height: 72px; white-space: pre-wrap; }

.vn-pill {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 6px 14px; border-radius: 8px;
    font-size: 13.5px; font-weight: 600;
    border: 1.5px solid transparent;
}
.vn-pill-home    { background: var(--info-soft); color: #0e7490; border-color: #bae6fd; }
.vn-pill-medical { background: var(--primary-soft); color: var(--primary); border-color: rgba(95,107,250,.25); }

/* Single-product filter notice */
.vn-filter-notice {
    display: flex; align-items: center; gap: 10px;
    background: var(--info-soft); border: 1px solid #bae6fd;
    border-radius: var(--radius-sm); padding: 10px 16px;
    font-size: 13px; color: #0e7490; font-weight: 500;
    margin: 12px 20px 0;
}
.vn-filter-notice i { font-size: 18px; flex-shrink: 0; }
.vn-filter-notice a {
    margin-left: auto; color: var(--primary);
    font-weight: 600; text-decoration: none; white-space: nowrap;
}
.vn-filter-notice a:hover { text-decoration: underline; }

.vn-products-table {
    width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13px;
}
.vn-products-table thead th {
    padding: 10px 12px;
    background: var(--surface-2);
    color: var(--text-secondary);
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .6px;
    border-bottom: 2px solid var(--border);
    white-space: nowrap;
}
.vn-product-row { border-bottom: 1px solid var(--border); transition: background .15s; }
.vn-product-row:last-child { border-bottom: none; }
.vn-product-row:hover { background: #fafbff; }
.vn-product-row td { padding: 12px; vertical-align: middle; }

.vn-row-num {
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px;
    background: var(--primary-soft); color: var(--primary);
    border-radius: 50%; font-size: 12px; font-weight: 700;
    font-family: var(--font-mono);
}

.vn-cell-val { font-size: 13.5px; font-weight: 500; color: var(--text-primary); }
.vn-cell-val.mono { font-family: var(--font-mono); }
.vn-cell-empty { color: var(--text-muted); font-style: italic; font-size: 12px; }

.vn-money { font-family: var(--font-mono); font-weight: 700; color: #15803d; font-size: 14px; }
.vn-qty-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 32px; padding: 2px 8px;
    background: var(--warning-soft); color: #92400e;
    border: 1px solid #fcd34d; border-radius: 12px;
    font-size: 12px; font-weight: 700; font-family: var(--font-mono);
}

.vn-stage-checklist { display: flex; flex-direction: column; gap: 5px; min-width: 138px; }
.vn-stage-item {
    display: flex; align-items: center; gap: 8px;
    padding: 5px 10px; border-radius: 7px;
    border: 1.5px solid var(--border);
    background: var(--surface-2);
    font-size: 12px; font-weight: 500; color: var(--text-muted);
}
.vn-stage-item.active-received  { background: var(--info-soft);     border-color: #38bdf8;  color: #0e7490;  }
.vn-stage-item.active-contacted { background: var(--primary-soft);  border-color: var(--primary); color: var(--primary); }
.vn-stage-item.active-delivered { background: var(--success-soft);  border-color: #22c55e;  color: #15803d;  }
.vn-stage-item.active-returned  { background: var(--danger-soft);   border-color: #ef4444;  color: #b91c1c;  }
.vn-stage-item.pending-indicator { background: var(--warning-soft); border-color: #fcd34d;  color: #92400e;  }
.vn-stage-item.pending-indicator.inactive {
    background: var(--surface-2); border-color: var(--border); color: var(--text-muted);
}

.vn-cb {
    width: 15px; height: 15px; flex-shrink: 0;
    border: 2px solid currentColor; border-radius: 3px;
    display: flex; align-items: center; justify-content: center;
}
.vn-cb.checked { background: currentColor; }
.vn-cb-tick {
    width: 8px; height: 5px;
    border-left: 2px solid #fff; border-bottom: 2px solid #fff;
    transform: rotate(-45deg) translateY(-1px); display: none;
}
.vn-cb.checked .vn-cb-tick { display: block; }

.vn-total-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--success-soft); color: #15803d;
    border: 1px solid #bbf7d0; padding: 4px 12px; border-radius: 20px;
    font-size: 14px; font-weight: 700; font-family: var(--font-mono);
}

.vn-workflow-bar {
    display: flex; align-items: center; gap: 0; overflow: hidden;
    border-radius: var(--radius-sm); border: 1px solid var(--border);
}
.vn-workflow-step {
    flex: 1; padding: 8px 10px; text-align: center;
    font-size: 11.5px; font-weight: 600; position: relative;
    background: var(--surface-2); color: var(--text-muted);
    border-right: 1px solid var(--border); transition: all .2s;
}
.vn-workflow-step:last-child { border-right: none; }
.vn-workflow-step i { display: block; font-size: 16px; margin-bottom: 2px; }
.vn-workflow-step.done-warning  { background: var(--warning-soft); color: #92400e; }
.vn-workflow-step.done-info     { background: var(--info-soft);    color: #0e7490; }
.vn-workflow-step.done-primary  { background: var(--primary-soft); color: var(--primary); }
.vn-workflow-step.done-success  { background: var(--success-soft); color: #15803d; }
.vn-workflow-step.done-danger   { background: var(--danger-soft);  color: #b91c1c; }
.vn-workflow-step.current::after {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0;
    height: 3px; background: currentColor; border-radius: 0 0 2px 2px;
}

.vn-action-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; background: var(--surface);
    border: 1px solid var(--border); border-radius: var(--radius);
    box-shadow: var(--shadow-md); margin-top: 8px;
    animation: fadeSlideUp 0.4s ease .15s both;
}
.vn-action-left, .vn-action-right { display: flex; align-items: center; gap: 10px; }

.vn-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 9px;
    font-size: 14px; font-weight: 600;
    cursor: pointer; border: none;
    transition: all .18s; text-decoration: none;
    white-space: nowrap; font-family: var(--font);
}
.vn-btn i { font-size: 17px; }
.vn-btn-primary { background: var(--primary); color: #fff; box-shadow: 0 2px 8px rgba(95,107,250,.35); }
.vn-btn-primary:hover { background: #4b56e8; color: #fff; transform: translateY(-1px); }
.vn-btn-warning { background: #fef3c7; color: #92400e; border: 1.5px solid #fcd34d; }
.vn-btn-warning:hover { background: #fde68a; color: #78350f; transform: translateY(-1px); }
.vn-btn-ghost { background: transparent; color: var(--text-secondary); border: 1.5px solid var(--border); }
.vn-btn-ghost:hover { background: var(--surface-2); color: var(--text-primary); }

.vn-supplier-pill {
    display: inline-flex; align-items: center; gap: 5px;
    background: #f1f5f9; color: var(--text-secondary);
    border: 1px solid var(--border); border-radius: 6px;
    padding: 3px 9px; font-size: 12.5px; font-weight: 500;
}

.vn-table-wrap { overflow-x: auto; border-radius: var(--radius-sm); }
.vn-table-wrap::-webkit-scrollbar { height: 6px; }
.vn-table-wrap::-webkit-scrollbar-track { background: var(--surface-2); }
.vn-table-wrap::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

@media (max-width: 768px) {
    .vn-grid-3 { grid-template-columns: 1fr 1fr; }
    .vn-grid-2 { grid-template-columns: 1fr; }
    .vn-action-bar { flex-direction: column; gap: 12px; }
    .vn-header { flex-direction: column; align-items: flex-start; gap: 10px; }
    .vn-workflow-bar { flex-wrap: wrap; }
    .vn-workflow-step { flex: none; width: 50%; }
}

/* Modal scroll — suggestion boxes use position:fixed so they escape naturally */
.modal-dialog {
    max-height: 90vh;
    margin-top: 5vh;
    margin-bottom: 5vh;
}
.modal-content {
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.modal-body {
    overflow-y: auto !important;
    overflow-x: hidden;
    flex: 1 1 auto;
}
/* Scrollbar styling */
.modal-body::-webkit-scrollbar { width: 6px; }
.modal-body::-webkit-scrollbar-track { background: var(--surface-2); }
.modal-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
</style>

<div class="vn-page">

    @php
    /*
     * $products  — always a Collection (1 item when $singleProduct=true, all when false)
     * $singleProduct — boolean flag set by controller
     */
    $isSingle   = isset($singleProduct) && $singleProduct === true;
    $totalQty   = $products->sum('quantity');
    $grandTotal = $products->sum('total_price');

    $stageMap = [
        'pending_supplier'       => ['cls' => 'done-warning',  'icon' => 'bx-time',        'label' => 'Pending Supplier'],
        'received_from_supplier' => ['cls' => 'done-info',     'icon' => 'bx-package',     'label' => 'Received'],
        'customer_contacted'     => ['cls' => 'done-primary',  'icon' => 'bx-phone-call',  'label' => 'Customer Contacted'],
        'delivered'              => ['cls' => 'done-success',  'icon' => 'bx-check-circle','label' => 'Delivered'],
        'returned'               => ['cls' => 'done-danger',   'icon' => 'bx-undo',        'label' => 'Returned'],
    ];

    // ── FIX: derive current stage from product flags when viewing a single product ──
    if ($isSingle) {
        $p = $products->first();
        if ($p->returned_status == 0) {
            $currentStage = 'returned';
        } elseif ($p->delivered_status == 0) {
            $currentStage = 'delivered';
        } elseif ($p->contacted_status == 0) {
            $currentStage = 'customer_contacted';
        } elseif ($p->received_status == 0) {
            $currentStage = 'received_from_supplier';
        } else {
            $currentStage = 'pending_supplier';
        }
    } else {
        $currentStage = $commitmentNote->workflow_stage ?? 'pending_supplier';
    }
    // ────────────────────────────────────────────────────────────────────────────────

    $stageOrder   = ['pending_supplier','received_from_supplier','customer_contacted','delivered','returned'];
    $currentIdx   = array_search($currentStage, $stageOrder);
@endphp

    {{-- ── Breadcrumb ── --}}
    <div class="vn-breadcrumb">
        <a href="{{ route('admin.commitment-notes.index') }}">Commitment Notes</a>
        <i class="bx bx-chevron-right"></i>
        <a href="{{ route('admin.commitment-notes.create') }}">Create</a>
        <i class="bx bx-chevron-right"></i>
        @if($isSingle)
            <a href="{{ route('admin.commitment-notes.show', $commitmentNote) }}">Note #{{ $commitmentNote->id }}</a>
            <i class="bx bx-chevron-right"></i>
            <span>{{ $products->first()->product_name }}</span>
        @else
            <span>View #{{ $commitmentNote->id }}</span>
        @endif
    </div>

    {{-- ── Header ── --}}
    <div class="vn-header">
        <div class="vn-header-left">
            <div class="vn-header-icon"><i class="bx bx-file-find"></i></div>
            <div>
                <div class="vn-header-title">
                    @if($isSingle){{ $products->first()->product_name }}
                    @else Commitment Note Details
                    @endif
                </div>
                <div class="vn-header-sub">
                    {{ $commitmentNote->cus_name ?? 'Unknown Customer' }}
                    &bull; Created {{ $commitmentNote->created_at->format('d M Y, h:i A') }}
                </div>
            </div>
        </div>
        <div class="vn-badge-id"><i class="bx bx-hash"></i>{{ $commitmentNote->id }}</div>
    </div>

    {{-- ── Workflow Bar ── --}}
    <div class="vn-section" style="animation-delay:0s;">
        <div class="vn-section-header">
            <div class="vn-section-title"><i class="bx bx-git-branch"></i> Workflow Progress</div>
            @php $si = $stageMap[$currentStage] ?? $stageMap['pending_supplier']; @endphp
            @php
                $stageCssMap = [
                    'pending_supplier'       => 'pending',
                    'received_from_supplier' => 'received',
                    'customer_contacted'     => 'contacted',
                    'delivered'              => 'delivered',
                    'returned'               => 'returned',
                ];
                $stageStyle = [
                    'pending'   => 'background:var(--warning-soft); color:#92400e; border:1px solid #fcd34d;',
                    'received'  => 'background:var(--info-soft); color:#0e7490; border:1px solid #bae6fd;',
                    'contacted' => 'background:var(--primary-soft); color:var(--primary); border:1px solid rgba(95,107,250,.3);',
                    'delivered' => 'background:var(--success-soft); color:#15803d; border:1px solid #bbf7d0;',
                    'returned'  => 'background:var(--danger-soft); color:#b91c1c; border:1px solid #fca5a5;',
                ];
                $scls = $stageCssMap[$currentStage] ?? 'pending';
            @endphp
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;{{ $stageStyle[$scls] }}">
                <i class="bx {{ $si['icon'] }}"></i>{{ $si['label'] }}
            </span>
        </div>
        <div class="vn-section-body" style="padding:16px 20px;">
            <div class="vn-workflow-bar">
                @foreach($stageOrder as $idx => $stage)
                    @php
                        $si2 = $stageMap[$stage];
                        $cls = ($idx <= $currentIdx) ? $si2['cls'] : '';
                        if ($stage === $currentStage) $cls .= ' current';
                    @endphp
                    <div class="vn-workflow-step {{ $cls }}">
                        <i class="bx {{ $si2['icon'] }}"></i>
                        {{ $si2['label'] }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Customer Details ── --}}
    <div class="vn-section">
        <div class="vn-section-header">
            <div class="vn-section-title"><i class="bx bx-user-circle"></i> Customer Details</div>
            <span style="font-size:12px; color:var(--text-muted);">Read-only</span>
        </div>
        <div class="vn-section-body">
            <div class="vn-grid-3">
                <div class="vn-field">
                    <span class="vn-label">Customer Name</span>
                    <div class="vn-value">{{ $commitmentNote->cus_name ?: '—' }}</div>
                </div>
                <div class="vn-field">
                    <span class="vn-label">Phone</span>
                    <div class="vn-value mono">{{ $commitmentNote->customer_phone ?: '—' }}</div>
                </div>
                <div class="vn-field">
                    <span class="vn-label">Delivery Date</span>
                    <div class="vn-value {{ !$commitmentNote->delivery_date ? 'muted' : '' }}">
                        {{ $commitmentNote->delivery_date ? $commitmentNote->delivery_date->format('d M Y') : 'Not set' }}
                    </div>
                </div>
            </div>
            <div style="margin-top:16px;" class="vn-grid-2">
                <div class="vn-field">
                    <span class="vn-label">Delivery Type</span>
                    <div class="vn-value" style="background:transparent;border-color:transparent;padding:0;min-height:auto;">
                        @if($commitmentNote->delivery_type === 'home')
                            <span class="vn-pill vn-pill-home"><i class="bx bx-home-alt"></i> Home</span>
                        @elseif($commitmentNote->delivery_type === 'medical')
                            <span class="vn-pill vn-pill-medical"><i class="bx bx-plus-medical"></i> Medical</span>
                        @else
                            <span style="color:var(--text-muted);font-style:italic;">Not set</span>
                        @endif
                    </div>
                </div>
                <div class="vn-field">
                    <span class="vn-label">Comments</span>
                    <div class="vn-value multiline {{ !$commitmentNote->comments ? 'muted' : '' }}">
                        {{ $commitmentNote->comments ?: 'No comments' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Product Details ── --}}
    <div class="vn-section">
        <div class="vn-section-header">
            <div class="vn-section-title"><i class="bx bx-package"></i> Product Details</div>
            <div style="display:flex;align-items:center;gap:12px;">
                @if($grandTotal > 0)
                <span class="vn-total-chip">
                    <i class="bx bx-rupee" style="font-size:14px;"></i>
                    {{ number_format($grandTotal, 2) }}
                </span>
                @endif
                <span style="font-size:12px;color:var(--text-muted);">
                    {{ $products->count() }} product(s)
                    @if($isSingle)<span style="color:var(--info);font-weight:600;"> &bull; Filtered View</span>@endif
                </span>
            </div>
        </div>

        {{-- Filter notice for single-product view --}}
        @if($isSingle)
        <div class="vn-filter-notice">
            <i class="bx bx-filter-alt"></i>
            Showing 1 of {{ $commitmentNote->products()->count() }} product(s) from Note #{{ $commitmentNote->id }}.
            <a href="{{ route('admin.commitment-notes.show', $commitmentNote) }}">
                View all products <i class="bx bx-arrow-right" style="vertical-align:middle;"></i>
            </a>
        </div>
        @endif

        <div class="vn-section-body" style="padding:0;">
            <div class="vn-table-wrap">
                <table class="vn-products-table">
                    <thead>
                        <tr>
                            <th style="width:42px;text-align:center;">#</th>
                            <th style="min-width:180px;">Product Name</th>
                            <th style="width:82px;text-align:center;">Total Qty</th>
                            <th style="width:105px;text-align:right;">MRP (₹)</th>
                            <th style="width:112px;text-align:right;">Total (₹)</th>
                            <th style="width:82px;text-align:center;">Order Qty</th>
                            <th style="min-width:150px;">Supplier</th>
                            <th style="min-width:130px;">Remarks</th>
                            <th style="min-width:148px;">Stage</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($products as $i => $prod)
                        @php
                            $isPending   = ($prod->received_status == 1 && $prod->contacted_status == 1
                                         && $prod->delivered_status == 1 && $prod->returned_status == 1);
                            $isReceived  = ($prod->received_status  == 0);
                            $isContacted = ($prod->contacted_status == 0);
                            $isDelivered = ($prod->delivered_status == 0);
                            $isReturned  = ($prod->returned_status  == 0);
                        @endphp
                        <tr class="vn-product-row">
                            <td style="text-align:center;"><span class="vn-row-num">{{ $i + 1 }}</span></td>
                            <td><div class="vn-cell-val">{{ $prod->product_name ?: '—' }}</div></td>
                            <td style="text-align:center;"><span class="vn-qty-badge">{{ $prod->quantity }}</span></td>
                            <td style="text-align:right;"><span class="vn-cell-val mono">₹{{ number_format($prod->mrp, 2) }}</span></td>
                            <td style="text-align:right;"><span class="vn-money">₹{{ number_format($prod->total_price, 2) }}</span></td>
                            <td style="text-align:center;">
                                @if($prod->order_qty)
                                    <span class="vn-qty-badge" style="background:var(--primary-soft);color:var(--primary);border-color:rgba(95,107,250,.3);">{{ $prod->order_qty }}</span>
                                @else
                                    <span class="vn-cell-empty">—</span>
                                @endif
                            </td>
                            <td>
                                @if($prod->supplier)
                                    <span class="vn-supplier-pill"><i class="bx bx-store-alt" style="font-size:13px;"></i>{{ $prod->supplier->name }}</span>
                                @else
                                    <span class="vn-cell-empty">No supplier</span>
                                @endif
                            </td>
                            <td>
                                @if($prod->remarks)
                                    <span class="vn-cell-val" style="font-size:12.5px;">{{ $prod->remarks }}</span>
                                @else
                                    <span class="vn-cell-empty">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="vn-stage-checklist">
                                    <div class="vn-stage-item pending-indicator {{ $isPending ? '' : 'inactive' }}">
                                        <i class="bx bx-time-five" style="font-size:13px;flex-shrink:0;"></i><span>Pending</span>
                                    </div>
                                    <div class="vn-stage-item {{ $isReceived ? 'active-received' : '' }}">
                                        <span class="vn-cb {{ $isReceived ? 'checked' : '' }}"><span class="vn-cb-tick"></span></span>
                                        <i class="bx bx-package" style="font-size:13px;"></i><span>Received</span>
                                    </div>
                                    <div class="vn-stage-item {{ $isContacted ? 'active-contacted' : '' }}">
                                        <span class="vn-cb {{ $isContacted ? 'checked' : '' }}"><span class="vn-cb-tick"></span></span>
                                        <i class="bx bx-phone-call" style="font-size:13px;"></i><span>Contacted</span>
                                    </div>
                                    <div class="vn-stage-item {{ $isDelivered ? 'active-delivered' : '' }}">
                                        <span class="vn-cb {{ $isDelivered ? 'checked' : '' }}"><span class="vn-cb-tick"></span></span>
                                        <i class="bx bx-check-circle" style="font-size:13px;"></i><span>Delivered</span>
                                    </div>
                                    <div class="vn-stage-item {{ $isReturned ? 'active-returned' : '' }}">
                                        <span class="vn-cb {{ $isReturned ? 'checked' : '' }}"><span class="vn-cb-tick"></span></span>
                                        <i class="bx bx-undo" style="font-size:13px;"></i><span>Returned</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:40px;color:var(--text-muted);">
                                <i class="bx bx-package" style="font-size:36px;display:block;margin-bottom:10px;color:var(--border);"></i>
                                No products found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                    @if($products->count() > 0)
                    <tfoot>
                        <tr style="background:var(--success-soft);border-top:2px solid #bbf7d0;">
                            <td colspan="4" style="padding:10px 12px;font-size:12px;font-weight:700;color:#15803d;text-align:right;letter-spacing:.5px;text-transform:uppercase;">
                                Grand Total
                            </td>
                            <td style="padding:10px 12px;text-align:right;">
                                <span class="vn-money" style="font-size:15px;">₹{{ number_format($grandTotal, 2) }}</span>
                            </td>
                            <td colspan="4" style="padding:10px 12px;">
                                <span style="font-size:12px;color:#15803d;font-weight:600;">
                                    {{ $products->count() }} product(s) &bull; Total Qty: {{ $totalQty }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- ── Meta Info ── --}}
    <div class="vn-section" style="animation-delay:.15s;">
        <div class="vn-section-header">
            <div class="vn-section-title"><i class="bx bx-info-circle"></i> Meta Information</div>
        </div>
        <div class="vn-section-body">
            <div class="vn-grid-3">
                <div class="vn-field">
                    <span class="vn-label">Created By</span>
                    <div class="vn-value">
                        <i class="bx bx-user" style="color:var(--text-muted);margin-right:6px;"></i>
                        {{ $commitmentNote->user->name ?? 'Unknown' }}
                    </div>
                </div>
                <div class="vn-field">
                    <span class="vn-label">Created At</span>
                    <div class="vn-value mono" style="font-size:13px;">{{ $commitmentNote->created_at->format('d M Y, h:i A') }}</div>
                </div>
                <div class="vn-field">
                    <span class="vn-label">Last Updated</span>
                    <div class="vn-value mono" style="font-size:13px;">{{ $commitmentNote->updated_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Action Bar ── --}}
    <div class="vn-action-bar">
        <div class="vn-action-left">
            <a href="{{ route('admin.commitment-notes.create') }}" class="vn-btn vn-btn-ghost">
                <i class="bx bx-arrow-back"></i> Back
            </a>
            @if($isSingle)
            <!--
<a href="{{ route('admin.commitment-notes.all', $commitmentNote) }}" class="vn-btn vn-btn-ghost">
    <i class="bx bx-list-ul"></i> All Products
</a>
-->
            @endif
        </div>
        <div class="vn-action-right">
            @if($isSingle)
            <a href="{{ route('admin.commitment-notes.edit-product', $products->first()->id) }}" class="vn-btn vn-btn-warning">
                <i class="bx bx-edit-alt"></i> Edit Product
            </a>
            @else
            <a href="{{ route('admin.commitment-notes.edit', $commitmentNote) }}" class="vn-btn vn-btn-warning">
                <i class="bx bx-edit-alt"></i> Edit Note
            </a>
            @endif
        </div>
    </div>

</div>
@endsection
