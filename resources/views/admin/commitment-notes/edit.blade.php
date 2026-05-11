@extends('layouts.sneat')

@section('title', 'Edit Commitment Note #' . $commitmentNote->id)

@section('content')

{{-- ═══════════════════════════════ PAGE STYLES ═══════════════════════════════ --}}
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
    --info:          #38bdf8;
    --surface:       #ffffff;
    --surface-2:     #f8fafc;
    --border:        #e2e8f0;
    --border-focus:  #5f6bfa;
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

.en-page * { font-family: var(--font); box-sizing: border-box; }
/* Allow suggestion boxes to escape modal overflow */
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

/* ── Page Header ── */
.en-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 0 20px;
    border-bottom: 2px solid var(--border);
    margin-bottom: 28px;
}
.en-header-left { display: flex; align-items: center; gap: 14px; }
.en-header-icon {
    width: 48px; height: 48px;
    background: var(--primary-soft);
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
}
.en-header-icon i { font-size: 24px; color: var(--primary); }
.en-header-title { font-size: 20px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.3px; }
.en-header-sub   { font-size: 13px; color: var(--text-muted); margin-top: 2px; font-family: var(--font-mono); }
.en-badge-id {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--primary-soft); color: var(--primary);
    font-size: 12px; font-weight: 600; font-family: var(--font-mono);
    padding: 4px 12px; border-radius: 20px;
    border: 1px solid rgba(95,107,250,.2);
}

/* ── Section Cards ── */
.en-section {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    margin-bottom: 22px;
    overflow: hidden;
    animation: fadeSlideUp 0.35s ease both;
}
.en-section:nth-child(2) { animation-delay: .05s; }
.en-section:nth-child(3) { animation-delay: .10s; }

@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.en-section-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    background: var(--surface-2);
}
.en-section-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 600; color: var(--text-primary);
}
.en-section-title i { font-size: 18px; color: var(--primary); }
.en-section-body { padding: 20px; }

/* ── Grid helpers ── */
.en-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.en-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

.en-field { display: flex; flex-direction: column; gap: 6px; }
.en-label {
    font-size: 11.5px; font-weight: 600; color: var(--text-secondary);
    text-transform: uppercase; letter-spacing: .6px;
}
.en-label .required { color: var(--danger); margin-left: 2px; }

.en-input, .en-select, .en-textarea {
    width: 100%;
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 10px 13px;
    font-size: 14px; font-weight: 500;
    color: var(--text-primary);
    transition: border-color .18s, box-shadow .18s, background .18s;
    outline: none;
    font-family: var(--font);
}
.en-input:focus, .en-select:focus, .en-textarea:focus {
    border-color: var(--border-focus);
    background: var(--surface);
    box-shadow: 0 0 0 3px var(--primary-glow);
}
.en-input::placeholder { color: var(--text-muted); font-weight: 400; }
.en-textarea { resize: vertical; min-height: 72px; }

/* Delivery pills */
.delivery-pills { display: flex; gap: 8px; }
.delivery-pill { display: none; }
.delivery-pill-label {
    display: flex; align-items: center; gap: 7px;
    padding: 9px 16px;
    border: 1.5px solid var(--border); border-radius: 8px;
    cursor: pointer; font-size: 14px; font-weight: 500;
    color: var(--text-secondary); background: var(--surface-2);
    transition: all .18s; user-select: none;
}
.delivery-pill-label i { font-size: 17px; }
.delivery-pill:checked + .delivery-pill-label {
    border-color: var(--primary); background: var(--primary-soft); color: var(--primary);
}

/* ── Products table ── */
.en-products-table {
    width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13px;
}
.en-products-table thead th {
    padding: 10px 12px;
    background: var(--surface-2);
    color: var(--text-secondary);
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .6px;
    border-bottom: 2px solid var(--border);
    white-space: nowrap;
}
.en-product-row { border-bottom: 1px solid var(--border); transition: background .15s; }
.en-product-row:last-child { border-bottom: none; }
.en-product-row:hover { background: #fafbff; }
.en-product-row td { padding: 10px 12px; vertical-align: top; }

.en-row-num {
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px;
    background: var(--primary-soft); color: var(--primary);
    border-radius: 50%; font-size: 12px; font-weight: 700;
    font-family: var(--font-mono); margin-top: 5px;
}

/* Table inputs */
.en-table-input {
    width: 100%;
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: 6px;
    padding: 7px 10px;
    font-size: 13px; font-weight: 500;
    color: var(--text-primary);
    transition: border-color .15s, box-shadow .15s;
    outline: none; font-family: var(--font);
}
.en-table-input:focus {
    border-color: var(--border-focus);
    background: #fff;
    box-shadow: 0 0 0 2.5px var(--primary-glow);
}
.en-table-input::placeholder { color: var(--text-muted); font-weight: 400; }

/* Auto-calculated total — green, read-only */
.en-table-input.total-readonly {
    background: var(--success-soft);
    border-color: #bbf7d0;
    color: #15803d;
    font-weight: 700;
    font-family: var(--font-mono);
    cursor: default;
}

/* ── Stage area ── */
.stage-box-list {
    display: flex; flex-direction: column; gap: 5px; min-width: 138px;
}

/* Pending — auto, non-clickable indicator */
.stage-pending-indicator {
    display: flex; align-items: center; gap: 7px;
    padding: 5px 10px;
    border-radius: 7px;
    font-size: 12px; font-weight: 600;
    color: #92400e;
    background: #fffbeb;
    border: 1.5px solid #fcd34d;
    transition: all .2s;
    user-select: none;
    cursor: default;
}
.stage-pending-indicator.inactive {
    background: var(--surface-2);
    border-color: var(--border);
    color: var(--text-muted);
    font-weight: 500;
}
.stage-pending-indicator i { font-size: 14px; }

/* Individual checkboxes */
.stage-cb-row {
    display: flex; align-items: center; gap: 8px;
    padding: 5px 10px;
    border-radius: 7px;
    border: 1.5px solid var(--border);
    background: var(--surface-2);
    cursor: pointer;
    font-size: 12.5px; font-weight: 500;
    color: var(--text-secondary);
    transition: all .15s;
    user-select: none;
}
.stage-cb-row:hover { border-color: #cbd5e1; background: #f1f5f9; }

/* Checked colours per stage */
.stage-cb-row.cb-received.is-checked   { background: #ecfeff; border-color: #38bdf8; color: #0e7490; }
.stage-cb-row.cb-contacted.is-checked  { background: var(--primary-soft); border-color: var(--primary); color: var(--primary); }
.stage-cb-row.cb-delivered.is-checked  { background: var(--success-soft); border-color: #22c55e; color: #15803d; }
.stage-cb-row.cb-returned.is-checked   { background: #fef2f2; border-color: #ef4444; color: #b91c1c; }

/* Custom checkbox visual */
.cb-box {
    width: 15px; height: 15px;
    border: 2px solid currentColor;
    border-radius: 3px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: background .12s;
}
.is-checked .cb-box { background: currentColor; }
.cb-tick {
    width: 8px; height: 5px;
    border-left: 2px solid #fff;
    border-bottom: 2px solid #fff;
    transform: rotate(-45deg) translateY(-1px);
    display: none;
}
.is-checked .cb-tick { display: block; }

/* ── Supplier autocomplete ── */
.en-supplier-wrap { position: relative; }
.en-supplier-suggestions {
    display: none;
    position: fixed;
    z-index: 2147483647;
    background: #fff;
    border: 1.5px solid var(--border-focus);
    border-top: none;
    border-radius: 0 0 8px 8px;
    max-height: 180px;
    overflow-y: auto;
    box-shadow: var(--shadow-md);
}
.en-supplier-option {
    padding: 9px 12px; cursor: pointer; font-size: 13px;
    border-bottom: 1px solid var(--border); transition: background .12s;
}
.en-supplier-option:last-child { border-bottom: none; }
.en-supplier-option:hover { background: var(--primary-soft); color: var(--primary); }

/* ── Grand total chip ── */
.en-total-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--success-soft); color: #15803d;
    border: 1px solid #bbf7d0;
    padding: 4px 10px; border-radius: 20px;
    font-size: 13px; font-weight: 700; font-family: var(--font-mono);
}

/* ── Action bar ── */
.en-action-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    margin-top: 8px;
    animation: fadeSlideUp 0.4s ease .15s both;
}
.en-action-left, .en-action-right { display: flex; align-items: center; gap: 10px; }

.en-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 9px;
    font-size: 14px; font-weight: 600;
    cursor: pointer; border: none;
    transition: all .18s; text-decoration: none;
    white-space: nowrap; font-family: var(--font);
}
.en-btn i { font-size: 17px; }
.en-btn-primary {
    background: var(--primary); color: #fff;
    box-shadow: 0 2px 8px rgba(95,107,250,.35);
}
.en-btn-primary:hover {
    background: #4b56e8; color: #fff;
    box-shadow: 0 4px 14px rgba(95,107,250,.45);
    transform: translateY(-1px);
}
.en-btn-ghost {
    background: transparent; color: var(--text-secondary);
    border: 1.5px solid var(--border);
}
.en-btn-ghost:hover { background: var(--surface-2); color: var(--text-primary); }

/* ── Alerts ── */
.en-alert {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 16px; border-radius: var(--radius-sm);
    margin-bottom: 18px; font-size: 14px;
    animation: fadeSlideUp .3s ease both;
}
.en-alert-success { background: var(--success-soft); border: 1px solid #bbf7d0; color: #15803d; }
.en-alert-error   { background: #fef2f2; border: 1px solid #fca5a5; color: #b91c1c; }
.en-alert i { font-size: 19px; margin-top: 1px; flex-shrink: 0; }

/* Breadcrumb */
.en-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 12.5px; color: var(--text-muted); margin-bottom: 6px;
}
.en-breadcrumb a { color: var(--primary); text-decoration: none; }
.en-breadcrumb a:hover { text-decoration: underline; }
.en-breadcrumb i { font-size: 14px; }

/* No-spinner */
.no-spin::-webkit-outer-spin-button,
.no-spin::-webkit-inner-spin-button { -webkit-appearance: none; }
.no-spin { -moz-appearance: textfield; }

/* Table scroll */
.en-table-wrap { overflow-x: auto; border-radius: var(--radius-sm); }
.en-table-wrap::-webkit-scrollbar { height: 6px; }
.en-table-wrap::-webkit-scrollbar-track { background: var(--surface-2); }
.en-table-wrap::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

@media (max-width: 768px) {
    .en-grid-3 { grid-template-columns: 1fr 1fr; }
    .en-grid-2 { grid-template-columns: 1fr; }
    .en-action-bar { flex-direction: column; gap: 12px; }
    .en-header { flex-direction: column; align-items: flex-start; gap: 10px; }
}
</style>

{{-- ═══════════════════════════════ PAGE ════════════════════════════════════ --}}
<div class="en-page">

    @if(session('success'))
    <div class="en-alert en-alert-success">
        <i class="bx bx-check-circle"></i><span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="en-alert en-alert-error">
        <i class="bx bx-error-circle"></i>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="en-breadcrumb">
        <a href="{{ route('admin.commitment-notes.index') }}">Commitment Notes</a>
        <i class="bx bx-chevron-right"></i>
        <span>Edit #{{ $commitmentNote->id }}</span>
    </div>

    <div class="en-header">
        <div class="en-header-left">
            <div class="en-header-icon"><i class="bx bx-edit-alt"></i></div>
            <div>
                <div class="en-header-title">Edit Commitment Note</div>
                <div class="en-header-sub">{{ $commitmentNote->cus_name ?? 'N/A' }} &bull; Last updated {{ $commitmentNote->updated_at->diffForHumans() }}</div>
            </div>
        </div>
        <div class="en-badge-id"><i class="bx bx-hash"></i>{{ $commitmentNote->id }}</div>
    </div>

    <form action="{{ route('admin.commitment-notes.update', $commitmentNote) }}" method="POST" id="editForm">
        @csrf
        @method('PUT')

        {{-- ── Customer Details ── --}}
        <div class="en-section">
            <div class="en-section-header">
                <div class="en-section-title"><i class="bx bx-user-circle"></i> Customer Details</div>
                <span style="font-size:12px; color:var(--text-muted);">Fields marked <span style="color:var(--danger);">*</span> are required</span>
            </div>
            <div class="en-section-body">
                <div class="en-grid-3">
                    <div class="en-field">
                        <label class="en-label">Customer Name <span class="required">*</span></label>
                        <input type="text" name="cus_name" class="en-input"
                               value="{{ old('cus_name', $commitmentNote->cus_name) }}"
                               placeholder="Enter customer name" required>
                    </div>
                    <div class="en-field">
                        <label class="en-label">Phone <span class="required">*</span></label>
                        <input type="text" name="customer_phone" class="en-input"
                               value="{{ old('customer_phone', $commitmentNote->customer_phone) }}"
                               placeholder="Enter phone number" required>
                    </div>
                    <div class="en-field">
                        <label class="en-label">Delivery Date</label>
                        <input type="date" name="delivery_date" class="en-input"
                               value="{{ old('delivery_date', $commitmentNote->delivery_date ? $commitmentNote->delivery_date->format('Y-m-d') : '') }}">
                    </div>
                </div>
                <div style="margin-top:16px;" class="en-grid-2">
                    <div class="en-field">
                        <label class="en-label">Delivery Type</label>
                        <div class="delivery-pills">
                            <input type="radio" name="delivery_type" id="dt-home" value="home" class="delivery-pill"
                                   {{ old('delivery_type', $commitmentNote->delivery_type) === 'home' ? 'checked' : '' }}>
                            <label for="dt-home" class="delivery-pill-label">
                                <i class="bx bx-home-alt"></i> Home
                            </label>
                            <input type="radio" name="delivery_type" id="dt-medical" value="medical" class="delivery-pill"
                                   {{ old('delivery_type', $commitmentNote->delivery_type) === 'medical' ? 'checked' : '' }}>
                            <label for="dt-medical" class="delivery-pill-label">
                                <i class="bx bx-plus-medical"></i> Medical
                            </label>
                        </div>
                    </div>
                    <div class="en-field">
                        <label class="en-label">Comments</label>
                        <textarea name="comments" class="en-textarea"
                                  placeholder="Enter any notes or instructions...">{{ old('comments', $commitmentNote->comments) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Product Details ── --}}
        <div class="en-section">
            <div class="en-section-header">
                <div class="en-section-title"><i class="bx bx-package"></i> Product Details</div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span id="grandTotalChip" class="en-total-chip" style="display:none;">
                        <i class="bx bx-rupee" style="font-size:14px;"></i>
                        <span id="grandTotalVal">0.00</span>
                    </span>
                    <span style="font-size:12px; color:var(--text-muted);">{{ $products->count() }} product(s)</span>
                </div>
            </div>
            <div class="en-section-body" style="padding:0;">
                <div class="en-table-wrap">
                    <table class="en-products-table">
                        <thead>
                            <tr>
                                <th style="width:42px; text-align:center;">#</th>
                                <th style="min-width:190px;">Product Name</th>
                                <th style="width:82px; text-align:center;">Total Qty</th>
                                <th style="width:105px; text-align:right;">MRP (₹)</th>
                                <th style="width:112px; text-align:right;">Total (₹)</th>
                                <th style="width:82px; text-align:center;">Order Qty</th>
                                <th style="min-width:150px;">Supplier</th>
                                <th style="min-width:135px;">Remarks</th>
                                <th style="min-width:148px;">Current Stage</th>
                            </tr>
                        </thead>
                        <tbody>

                        @forelse($products as $i => $prod)
                        @php
                            /*
                             * DB convention: 0 = status IS done (active), 1 = not done yet.
                             * Pending = all four are 1 (nothing done).
                             */
                            $isPending   = ($prod->received_status == 1 && $prod->contacted_status == 1
                                         && $prod->delivered_status == 1 && $prod->returned_status == 1);
                            $isReceived  = ($prod->received_status  == 0);
                            $isContacted = ($prod->contacted_status == 0);
                            $isDelivered = ($prod->delivered_status == 0);
                            $isReturned  = ($prod->returned_status  == 0);
                        @endphp
                        <tr class="en-product-row" data-product-id="{{ $prod->id }}">

                            {{-- # --}}
                            <td style="text-align:center;">
                                <span class="en-row-num">{{ $i + 1 }}</span>
                            </td>

                            {{-- Product Name with autocomplete --}}
    <td class="position-relative en-product-cell">
        <input type="text"
               name="products[{{ $prod->id }}][product_name]"
               class="en-table-input en-product-name"
               value="{{ $prod->product_name }}"
               placeholder="Search medicine (min 2 letters)..."
               autocomplete="off">
        {{-- hidden fields updated when medicine is picked --}}
        <input type="hidden" name="products[{{ $prod->id }}][medicine_id]" class="en-medicine-id" value="">
        <div class="en-suggestions-box" style="display:none;"></div>
    </td>


                            {{-- Total Qty --}}
                            <td>
                                <input type="number"
                                       name="products[{{ $prod->id }}][quantity]"
                                       class="en-table-input no-spin prod-qty"
                                       style="text-align:center;"
                                       value="{{ $prod->quantity }}"
                                       min="0" placeholder="0">
                            </td>

                            {{-- MRP (₹) — NEW COLUMN --}}
                            <td>
                                <input type="number"
                                       name="products[{{ $prod->id }}][mrp]"
                                       class="en-table-input no-spin prod-mrp"
                                       style="text-align:right; font-family:var(--font-mono);"
                                       value="{{ $prod->mrp }}"
                                       step="0.01" min="0" placeholder="0.00">
                            </td>

                            {{-- Total (₹) — auto-calculated, read-only --}}
                            <td>
                                <input type="number"
                                       name="products[{{ $prod->id }}][total_price]"
                                       class="en-table-input no-spin prod-total total-readonly"
                                       style="text-align:right;"
                                       value="{{ $prod->total_price }}"
                                       step="0.01" min="0" placeholder="0.00"
                                       readonly tabindex="-1">
                            </td>

                            {{-- Order Qty --}}
                            <td>
                                <input type="number"
                                       name="products[{{ $prod->id }}][order_qty]"
                                       class="en-table-input no-spin"
                                       style="text-align:center;"
                                       value="{{ $prod->order_qty }}"
                                       min="0" placeholder="0">
                            </td>

                            {{-- Supplier --}}
                            <td>
        <div class="en-supplier-wrap">
            <input type="text"
                   class="en-table-input supplier-search"
                   placeholder="Search supplier..."
                   value="{{ $prod->supplier->name ?? '' }}"
                   autocomplete="off">
            <input type="hidden"
                   name="products[{{ $prod->id }}][supplier_id]"
                   class="supplier-id-hidden"
                   value="{{ $prod->supplier_id }}">
            <div class="en-supplier-suggestions"></div>
        </div>
    </td>


                            {{-- Remarks --}}
                            <td>
                                <input type="text"
                                       name="products[{{ $prod->id }}][remarks]"
                                       class="en-table-input"
                                       value="{{ $prod->remarks }}"
                                       placeholder="Remarks...">
                            </td>

                            {{-- Current Stage — 4 independent checkboxes + auto Pending ── --}}
                            <td>
                                {{-- Hidden fields sent to server --}}
                                <input type="hidden" name="products[{{ $prod->id }}][received_status]"
                                       class="hidden-received"  value="{{ $prod->received_status }}">
                                <input type="hidden" name="products[{{ $prod->id }}][contacted_status]"
                                       class="hidden-contacted" value="{{ $prod->contacted_status }}">
                                <input type="hidden" name="products[{{ $prod->id }}][delivered_status]"
                                       class="hidden-delivered" value="{{ $prod->delivered_status }}">
                                <input type="hidden" name="products[{{ $prod->id }}][returned_status]"
                                       class="hidden-returned"  value="{{ $prod->returned_status }}">

                                <div class="stage-box-list" data-product-id="{{ $prod->id }}">

                                    {{-- PENDING — read-only auto indicator --}}
                                    <div class="stage-pending-indicator {{ $isPending ? '' : 'inactive' }}"
                                         id="pending-ind-{{ $prod->id }}"
                                         title="Automatically active when no stage is checked">
                                        <i class="bx bx-time-five"></i>
                                        <span>Pending</span>
                                    </div>

                                    {{-- RECEIVED --}}
                                    <div class="stage-cb-row cb-received {{ $isReceived ? 'is-checked' : '' }}"
                                         data-field="received" data-product-id="{{ $prod->id }}"
                                         title="Received from supplier">
                                        <span class="cb-box"><span class="cb-tick"></span></span>
                                        <span>Received</span>
                                    </div>

                                    {{-- CONTACTED --}}
                                    <div class="stage-cb-row cb-contacted {{ $isContacted ? 'is-checked' : '' }}"
                                         data-field="contacted" data-product-id="{{ $prod->id }}"
                                         title="Customer contacted">
                                        <span class="cb-box"><span class="cb-tick"></span></span>
                                        <span>Contacted</span>
                                    </div>

                                    {{-- DELIVERED --}}
                                    <div class="stage-cb-row cb-delivered {{ $isDelivered ? 'is-checked' : '' }}"
                                         data-field="delivered" data-product-id="{{ $prod->id }}"
                                         title="Delivered to customer">
                                        <span class="cb-box"><span class="cb-tick"></span></span>
                                        <span>Delivered</span>
                                    </div>

                                    {{-- RETURNED --}}
                                    <div class="stage-cb-row cb-returned {{ $isReturned ? 'is-checked' : '' }}"
                                         data-field="returned" data-product-id="{{ $prod->id }}"
                                         title="Returned by customer">
                                        <span class="cb-box"><span class="cb-tick"></span></span>
                                        <span>Returned</span>
                                    </div>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" style="text-align:center; padding:32px; color:var(--text-muted);">
                                <i class="bx bx-package" style="font-size:34px; display:block; margin-bottom:8px;"></i>
                                No products found for this commitment note.
                            </td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── Action Bar ── --}}
        <div class="en-action-bar">
            <div class="en-action-left">
                <a href="{{ route('admin.commitment-notes.show', $commitmentNote) }}" class="en-btn en-btn-ghost">
                    <i class="bx bx-show"></i> View Note
                </a>
            </div>
            <div class="en-action-right">
                <a href="{{ route('admin.commitment-notes.create') }}" class="en-btn en-btn-ghost">
                    <i class="bx bx-x"></i> Cancel
                </a>
                <button type="submit" class="en-btn en-btn-primary" id="saveBtn">
                    <i class="bx bx-save"></i> Save Changes
                </button>
            </div>
        </div>

    </form>
</div>

{{-- ═══════════════════════════════ SCRIPTS ════════════════════════════════ --}}
<script>
window._editBaseUrl = '{{ url('/') }}';
const BASE_URL = '{{ url('/') }}';

function enEscHtml(str) {
    const d = document.createElement('div');
    d.textContent = str || '';
    return d.innerHTML;
}

function enGetCsrf() {
    return document.querySelector('meta[name="csrf-token"]')?.content
        || document.querySelector('input[name="_token"]')?.value || '';
}

/* ─── call this once after the DOM (or modal body) is ready ─── */
function initEditPage(root) {
    root = root || document;
    var BASE_URL = (typeof window._editBaseUrl !== 'undefined') ? window._editBaseUrl : '{{ url('/') }}';

    /* ══════════════════════════════════════
       1. TOTAL = MRP × Total Qty  (dynamic)
    ══════════════════════════════════════ */
    function calcRowTotal(row) {
        var mrp   = parseFloat(row.querySelector('.prod-mrp')?.value)  || 0;
        var qty   = parseFloat(row.querySelector('.prod-qty')?.value)  || 0;
        var total = row.querySelector('.prod-total');
        if (total) {
            total.value = (mrp * qty).toFixed(2);
            total.style.background = (mrp * qty) > 0 ? 'var(--success-soft, #f0fdf4)' : '';
        }
        recalcGrandTotal(root);
    }

    function recalcGrandTotal(root) {
        var grand = 0;
        root.querySelectorAll('.prod-total').forEach(function (inp) {
            grand += parseFloat(inp.value) || 0;
        });
        var chip = root.querySelector('#grandTotalChip');
        var val  = root.querySelector('#grandTotalVal');
        if (val)  val.textContent = grand.toFixed(2);
        if (chip) chip.style.display = grand > 0 ? 'inline-flex' : 'none';
    }

    /* Attach qty/mrp listeners */
    root.querySelectorAll('.prod-mrp, .prod-qty').forEach(function (inp) {
        if (inp.dataset.enTotalBound) return;
        inp.dataset.enTotalBound = '1';
        inp.addEventListener('input', function () {
            var row = this.closest('.en-product-row');
            if (row) calcRowTotal(row);
        });
    });

    /* Initial calculation */
    root.querySelectorAll('.en-product-row').forEach(calcRowTotal);
    recalcGrandTotal(root);

    /* ══════════════════════════════════════
       2. MEDICINE NAME AUTOCOMPLETE
       — suggestion box appended to body to escape modal overflow
    ══════════════════════════════════════ */
    root.querySelectorAll('.en-product-name').forEach(function (input) {
        if (input.dataset.enAcBound) return;
        input.dataset.enAcBound = '1';

        var row         = input.closest('.en-product-row');
        var cell        = input.closest('.en-product-cell');
        var medIdHidden = cell ? cell.querySelector('.en-medicine-id') : null;
        var mrpInput    = row  ? row.querySelector('.prod-mrp')  : null;
        var qtyInput    = row  ? row.querySelector('.prod-qty')  : null;

        /* Create a floating suggestion box appended to body */
        var sugBox = document.createElement('div');
        sugBox.className = 'en-suggestions-box-float';
        sugBox.style.cssText = [
            'display:none',
            'position:fixed',
            'background:#fff',
            'border:2px solid #5f6bfa',
            'border-radius:10px',
            'max-height:380px',
            'overflow-y:auto',
            'box-shadow:0 8px 28px rgba(0,0,0,.22)',
            'z-index:2147483647',
            'min-width:420px',
            'font-family:DM Sans,sans-serif'
        ].join(';');
        document.body.appendChild(sugBox);

        function positionBox() {
            var r    = input.getBoundingClientRect();
            var bw   = Math.max(420, r.width * 1.6);
            var top  = r.bottom + 4;
            var left = r.left;
            if (left + bw > window.innerWidth - 8) left = window.innerWidth - bw - 8;
            if (left < 4) left = 4;
            var bh = Math.min(380, window.innerHeight * 0.5);
            if (top + bh > window.innerHeight - 8) top = r.top - bh - 4;
            sugBox.style.top       = top  + 'px';
            sugBox.style.left      = left + 'px';
            sugBox.style.width     = bw   + 'px';
            sugBox.style.maxHeight = bh   + 'px';
        }

        /* Reposition on scroll/resize */
        window.addEventListener('scroll', function() {
            if (sugBox.style.display !== 'none') positionBox();
        }, true);
        window.addEventListener('resize', function() {
            if (sugBox.style.display !== 'none') positionBox();
        });

        var searchTimer;
        input.addEventListener('input', function () {
            clearTimeout(searchTimer);
            var q = this.value.trim();
            if (q.length < 2) { sugBox.style.display = 'none'; return; }
            positionBox();
            sugBox.innerHTML = '<div style="padding:14px;text-align:center;color:#64748b;">Searching…</div>';
            sugBox.style.display = 'block';
            searchTimer = setTimeout(function () {
                fetch(BASE_URL + '/admin/medicines/search-with-details?query=' + encodeURIComponent(q) + '&starts_with=true')
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (!data || data.error || !data.length) {
                            sugBox.innerHTML = '<div style="padding:14px;text-align:center;color:#94a3b8;">No medicines found</div>';
                            positionBox(); return;
                        }
                        sugBox.innerHTML = data.map(function (item) {
                            return '<div class="en-sug-item" style="padding:12px 14px;cursor:pointer;border-bottom:1px solid #f1f5f9;transition:background .13s;"'
                                + ' data-id="'       + item.id              + '"'
                                + ' data-mrp="'      + item.mrp             + '"'
                                + ' data-name="'     + enEscHtml(item.name) + '"'
                                + ' data-stock="'    + item.stock_quantity  + '"'
                                + ' data-supplier="' + enEscHtml(item.supplier_name) + '">'
                                + '<div style="font-weight:600;font-size:13.5px;color:#0f172a;">' + enEscHtml(item.name) + '</div>'
                                + '<div style="font-size:12px;color:#64748b;margin-top:3px;display:flex;gap:12px;">'
                                + '<span>💰 ₹' + parseFloat(item.mrp).toFixed(2) + '</span>'
                                + '<span>📦 Stock: ' + item.stock_quantity + '</span>'
                                + '<span>🏷️ ' + enEscHtml(item.category) + '</span>'
                                + '</div>'
                                + '<div style="font-size:11.5px;color:#5f6bfa;margin-top:2px;">Supplier: ' + enEscHtml(item.supplier_name) + '</div>'
                                + '</div>';
                        }).join('');
                        positionBox();

                        sugBox.querySelectorAll('.en-sug-item').forEach(function (el) {
                            el.addEventListener('mouseenter', function () { this.style.background = '#eef0ff'; });
                            el.addEventListener('mouseleave', function () { this.style.background = ''; });
                            el.addEventListener('click', function () {
                                input.value = this.dataset.name;
                                if (medIdHidden) medIdHidden.value = this.dataset.id;
                                if (mrpInput)    mrpInput.value    = this.dataset.mrp;
                                /* Do NOT override qty — user sets that manually */
                                sugBox.style.display = 'none';
                                if (row) calcRowTotal(row);
                                /* focus qty field */
                                var qtyField = row ? row.querySelector('.prod-qty') : null;
                                if (qtyField) { qtyField.focus(); qtyField.select(); }
                            });
                        });
                    })
                    .catch(function () {
                        sugBox.innerHTML = '<div style="padding:14px;color:red;text-align:center;">Search failed</div>';
                    });
            }, 280);
        });

        /* Close on outside click */
        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !sugBox.contains(e.target)) {
                sugBox.style.display = 'none';
            }
        });

        /* Clean up floating box if modal closes */
        var modal = document.getElementById('editProductModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function () {
                sugBox.remove();
            }, { once: true });
        }
    });

    /* ══════════════════════════════════════
       3. SUPPLIER AUTOCOMPLETE
       — suggestion box appended to body to escape modal overflow
    ══════════════════════════════════════ */
    root.querySelectorAll('.supplier-search').forEach(function (input) {
        if (input.dataset.enSupBound) return;
        input.dataset.enSupBound = '1';

        var wrap     = input.closest('.en-supplier-wrap');
        var hiddenId = wrap ? wrap.querySelector('.supplier-id-hidden') : null;

        /* Create floating supplier suggestion box appended to body */
        var sugBox = document.createElement('div');
        sugBox.className = 'en-supplier-suggestions-float';
        sugBox.style.cssText = [
            'display:none',
            'position:fixed',
            'background:#fff',
            'border:1.5px solid #5f6bfa',
            'border-radius:8px',
            'max-height:200px',
            'overflow-y:auto',
            'box-shadow:0 4px 16px rgba(0,0,0,.15)',
            'z-index:2147483647',
            'font-family:DM Sans,sans-serif'
        ].join(';');
        document.body.appendChild(sugBox);

        function posSupBox() {
            var r = input.getBoundingClientRect();
            sugBox.style.top   = (r.bottom + 2) + 'px';
            sugBox.style.left  = r.left + 'px';
            sugBox.style.width = Math.max(180, r.width) + 'px';
        }

        var timer;
        input.addEventListener('input', function () {
            clearTimeout(timer);
            var q = this.value.trim();
            if (q.length < 2) { sugBox.style.display = 'none'; return; }
            timer = setTimeout(function () {
                fetch(BASE_URL + '/admin/suppliers/search?query=' + encodeURIComponent(q), {
                    headers: { 'X-CSRF-TOKEN': enGetCsrf() }
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data || !data.length) { sugBox.style.display = 'none'; return; }
                    posSupBox();
                    sugBox.innerHTML = data.map(function (s) {
                        return '<div class="en-sup-opt" data-id="' + s.id + '" data-name="' + enEscHtml(s.name) + '"'
                            + ' style="padding:9px 12px;cursor:pointer;font-size:13px;border-bottom:1px solid #f1f5f9;transition:background .12s;">'
                            + enEscHtml(s.name) + '</div>';
                    }).join('');
                    sugBox.style.display = 'block';

                    sugBox.querySelectorAll('.en-sup-opt').forEach(function (opt) {
                        opt.addEventListener('mouseenter', function () { this.style.background = '#eef0ff'; });
                        opt.addEventListener('mouseleave', function () { this.style.background = ''; });
                        opt.addEventListener('click', function () {
                            input.value = this.dataset.name;
                            if (hiddenId) hiddenId.value = this.dataset.id;
                            sugBox.style.display = 'none';
                        });
                    });
                })
                .catch(function () { sugBox.style.display = 'none'; });
            }, 260);
        });

        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !sugBox.contains(e.target)) {
                sugBox.style.display = 'none';
            }
        });

        /* Clean up on modal close */
        var modal = document.getElementById('editProductModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function () {
                sugBox.remove();
            }, { once: true });
        }
    });

    /* ══════════════════════════════════════
       4. PENDING INDICATOR
    ══════════════════════════════════════ */
    var hiddenFieldMap = {
        received:  '.hidden-received',
        contacted: '.hidden-contacted',
        delivered: '.hidden-delivered',
        returned:  '.hidden-returned',
    };

    function refreshPending(productId) {
        var row = root.querySelector('tr[data-product-id="' + productId + '"]');
        if (!row) return;
        var allUnchecked =
            (row.querySelector('.hidden-received')?.value  == '1') &&
            (row.querySelector('.hidden-contacted')?.value == '1') &&
            (row.querySelector('.hidden-delivered')?.value == '1') &&
            (row.querySelector('.hidden-returned')?.value  == '1');
        var ind = document.getElementById('pending-ind-' + productId);
        if (ind) ind.classList.toggle('inactive', !allUnchecked);
    }

    root.querySelectorAll('.stage-cb-row').forEach(function (cb) {
        if (cb.dataset.enCbBound) return;
        cb.dataset.enCbBound = '1';
        cb.addEventListener('click', function () {
            var productId = this.dataset.productId;
            var field     = this.dataset.field;
            var row2      = root.querySelector('tr[data-product-id="' + productId + '"]')
                         || document.querySelector('tr[data-product-id="' + productId + '"]');
            if (!row2) return;
            var nowChecked = !this.classList.contains('is-checked');
            this.classList.toggle('is-checked', nowChecked);
            var hidden = row2.querySelector(hiddenFieldMap[field]);
            if (hidden) hidden.value = nowChecked ? '0' : '1';
            refreshPending(productId);
        });
    });

    root.querySelectorAll('.stage-box-list[data-product-id]').forEach(function (el) {
        refreshPending(el.dataset.productId);
    });

    /* ══════════════════════════════════════
       5. SAVE BUTTON SPINNER
    ══════════════════════════════════════ */
    var editForm = root.querySelector('#editForm');
    if (editForm && !editForm.dataset.enSaveBound) {
        editForm.dataset.enSaveBound = '1';
        editForm.addEventListener('submit', function () {
            var btn = root.querySelector('#saveBtn') || document.getElementById('saveBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Saving…';
            }
        });
    }
}

/* Run immediately for the standalone page */
document.addEventListener('DOMContentLoaded', function () {
    initEditPage(document);
});

/* Also expose so the modal can call it after injecting HTML */
window.initEditPage = initEditPage;
</script>


@endsection
