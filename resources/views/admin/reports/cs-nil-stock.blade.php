@extends('layouts.sneat')

@section('title', 'CS Nil-Stock Report')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 text-primary">
                    <i class="bx bx-x-circle me-2"></i>Commitment Stock Nil-Stock Report
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" id="scrollLeftBtn"
                            title="Scroll to left end"
                            style="width:32px; height:32px; border-radius:50%;
                                   background:rgba(99,102,241,0.12); border:2px solid rgba(99,102,241,0.3);
                                   color:#4f46e5; display:flex; align-items:center; justify-content:center;
                                   cursor:pointer; transition:all 0.2s; flex-shrink:0; padding:0;">
                        <i class="bx bx-chevrons-left" style="font-size:18px;"></i>
                    </button>
                    <button type="button" id="scrollRightBtn"
                            title="Scroll to right end"
                            style="width:32px; height:32px; border-radius:50%;
                                   background:rgba(99,102,241,0.12); border:2px solid rgba(99,102,241,0.3);
                                   color:#4f46e5; display:flex; align-items:center; justify-content:center;
                                   cursor:pointer; transition:all 0.2s; flex-shrink:0; padding:0;">
                        <i class="bx bx-chevrons-right" style="font-size:18px;"></i>
                    </button>
                    <div class="input-group input-group-sm" style="width: 260px;">
                        <span class="input-group-text">
                            <i class="bx bx-search text-primary"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control form-control-sm"
                               placeholder="Search by name, phone, product..." autocomplete="off">
                        <button class="btn btn-sm btn-outline-secondary" type="button" id="clearSearchBtn" style="display:none;">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($commitments->count() > 0)

                    <div id="topScroll" style="overflow-x:auto; overflow-y:hidden; height:17px; margin-bottom:2px;">
                        <div id="topScrollInner" style="height:1px;"></div>
                    </div>

                    <div class="table-responsive" id="mainScroll">
                        <table class="table table-bordered align-middle" id="nilTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="sortable sticky-col" data-sort="name" style="cursor:pointer;">Customer Name</th>
                                    <th class="text-center sortable" data-sort="sno" style="width:80px; cursor:pointer;">S.No</th>
                                    <th class="sortable" data-sort="phone" style="cursor:pointer;">Phone</th>
                                    <th class="sortable text-center" data-sort="salesperson" style="cursor:pointer;">Sales<br>Person<br>Name</th>
                                    <th class="sortable text-center" data-sort="advance" style="cursor:pointer;">Advance<br>Amount</th>
                                    <th class="sortable text-center" data-sort="date" style="cursor:pointer;">Delivery<br>Date</th>
                                    <th class="sortable text-center" data-sort="type" style="cursor:pointer;">Delivery<br>Type</th>
                                    <th class="sortable" data-sort="comments" style="cursor:pointer;">Comments</th>
                                    <th class="sortable" data-sort="products" style="cursor:pointer; min-width:200px; width:200px;">Products</th>
                                    <th class="sortable text-center" data-sort="qty" style="cursor:pointer;">
                                        <div class="d-flex flex-column align-items-center">
                                            <span>Cus Qty</span>
                                            <small style="font-size:12px; text-transform:none; font-weight:bold;">(in single unit)</small>
                                        </div>
                                    </th>
                                    {{-- Order Qty, Supplier, Remarks columns removed for Nil-Stock report --}}
                                </tr>
                            </thead>
                            <tbody id="nilTableBody">
                                @foreach($commitments as $note)
                                @php
                                    $products     = $note->products; // filtered: ns_status=0
                                    $productCount = $products->count();
                                    $totalQty     = $products->sum('quantity');
                                @endphp
                                <tr class="nil-row"
                                    data-id="{{ $note->id }}"
                                    data-name="{{ strtolower($note->cus_name ?? '') }}"
                                    data-phone="{{ $note->customer_phone ?? '' }}"
                                    data-date="{{ $note->delivery_date ? strtotime($note->delivery_date) : 0 }}"
                                    data-type="{{ $note->delivery_type ?? '' }}"
                                    data-comments="{{ strtolower($note->comments ?? '') }}"
                                    data-products-count="{{ $productCount }}"
                                    data-qty="{{ $totalQty }}"
                                    data-sno="{{ $note->id }}"
                                    data-salesperson="{{ strtolower($note->sales_person_name ?? '') }}"
                                    data-advance="{{ $note->advance_amount ?? 0 }}">

                                    <td class="sticky-col">{{ $note->cus_name }}</td>
                                    <td class="text-center fw-bold text-muted" style="font-family:'JetBrains Mono',monospace; font-size:12px;">
                                        con-{{ $note->id }}
                                    </td>
                                    <td>{{ $note->customer_phone }}</td>
                                    <td class="text-center">{{ $note->sales_person_name ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($note->advance_amount > 0)
                                            <span class="fw-bold text-primary">₹{{ number_format($note->advance_amount, 2) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $note->delivery_date ? date('d/m/Y', strtotime($note->delivery_date)) : 'Not set' }}</td>
                                    <td>
                                        @if($note->delivery_type)
                                            <span class="badge {{ $note->delivery_type == 'home' ? 'badge-delivery-home' : 'badge-delivery-medical' }}">
                                                {{ $note->delivery_type }}
                                            </span>
                                        @else
                                            <span class="text-muted small">Not set</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($note->comments, 30) ?: 'No comments' }}</td>

                                    {{-- Products --}}
                                    <td>
                                        @if($productCount > 0)
                                            <div class="d-flex flex-column gap-1">
                                                @foreach($products as $prod)
                                                    <div style="height:38px;" class="text-start d-flex align-items-center">
                                                        <div class="small text-muted fw-bold">
                                                            {{ $loop->iteration }}) {{ $prod->product_name }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">No products</span>
                                        @endif
                                    </td>

                                    {{-- Cus Qty --}}
                                    <td class="text-center">
                                        @if($productCount > 0)
                                            <div class="d-flex flex-column gap-1" style="align-items:center !important;">
                                                @foreach($products as $prod)
                                                    <div style="height:38px; display:flex; align-items:center; justify-content:center; width:100%;">
                                                        <span class="badge bg-warning">{{ $prod->quantity }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="badge bg-warning">0</span>
                                        @endif
                                    </td>

                                    {{-- Order Qty, Supplier, Remarks intentionally omitted --}}

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="noResultsMessage" class="alert alert-info text-center mt-3" style="display:none;">
                        <i class="bx bx-info-circle me-2"></i>No matching records found
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            <i class="bx bx-info-circle me-1"></i>
                            Showing {{ $commitments->count() }} commitment(s) with nil-stock products &nbsp;|&nbsp;
                            Click on any column header to sort
                        </div>
                    </div>

                @else
                    <div class="alert alert-info text-center py-5">
                        <i class="bx bx-info-circle fs-3 d-block mb-2"></i>
                        No records found with nil-stock products.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap');

:root {
  --brand-primary:    #4f46e5;
  --brand-primary-lt: #eef2ff;
  --brand-primary-md: #c7d2fe;
  --color-warning:    #d97706;
  --bg-header:        #f1f5f9;
  --border-default:   #e2e8f0;
  --border-strong:    #cbd5e1;
  --text-heading:     #1e293b;
  --text-body:        #334155;
  --text-muted:       #64748b;
  --shadow-sm:  0 2px 8px rgba(15,23,42,.08);
}

body, .card, .table, th, td, label { font-family: 'Inter', system-ui, sans-serif !important; }

.card.shadow-sm {
  border: 1px solid var(--border-default) !important;
  border-radius: 18px !important;
  box-shadow: var(--shadow-sm) !important;
  overflow: hidden;
}
.card-header.bg-white { border-bottom: 1px solid var(--border-default) !important; padding: 16px 24px !important; }
.card-body { padding: 20px 24px !important; background: #f8fafc; }

#nilTable {
  border-collapse: separate !important;
  border-spacing: 0 !important;
  border: 1px solid var(--border-default) !important;
  border-radius: 10px !important;
  overflow: hidden;
  background: #fff;
}
#nilTable thead th {
  background: var(--bg-header) !important;
  font-size: 10.5px !important;
  font-weight: 700 !important;
  text-transform: uppercase !important;
  letter-spacing: .08em !important;
  color: var(--text-muted) !important;
  padding: 11px 14px !important;
  border-bottom: 1.5px solid var(--border-strong) !important;
  border-right: 1px solid var(--border-default) !important;
  white-space: nowrap;
}
#nilTable thead th:last-child { border-right: none !important; }
#nilTable thead .sticky-col { background: var(--brand-primary-lt) !important; color: var(--brand-primary) !important; z-index: 3; }

#nilTable tbody td {
  padding: 0 14px !important;
  font-size: 13px !important;
  color: var(--text-body) !important;
  border-bottom: 1px solid var(--border-default) !important;
  border-right: 1px solid var(--border-default) !important;
  vertical-align: middle !important;
}
#nilTable tbody td:last-child { border-right: none !important; }
#nilTable tbody tr:last-child td { border-bottom: none !important; }
#nilTable tbody tr.nil-row:hover td { background: #fafbff !important; }
#nilTable tbody tr.nil-row:hover .sticky-col { background: var(--brand-primary-lt) !important; }

#nilTable .sticky-col {
  position: sticky; left: 0; z-index: 2; background: #fff;
  min-width: 130px; max-width: 160px; white-space: nowrap;
  overflow: hidden; text-overflow: ellipsis;
  box-shadow: 3px 0 8px -2px rgba(15,23,42,.08);
  font-weight: 600 !important; color: var(--text-heading) !important;
}

.badge { font-size: 10.5px !important; font-weight: 600 !important; padding: 3.5px 9px !important; border-radius: 20px !important; }
.badge.bg-warning { background: var(--color-warning) !important; color:#fff !important; font-family:'JetBrains Mono',monospace !important; }

.badge-delivery-home    { background-color:#2563EB !important; color:#fff !important; border:1px solid #c7d2fe !important; padding:6px 16px !important; border-radius:10px !important; text-transform:uppercase; font-weight:700; font-size:10px; }
.badge-delivery-medical { background-color:#16A34A !important; color:#fff !important; border:1px solid #bbf7d0 !important; padding:6px 16px !important; border-radius:10px !important; text-transform:uppercase; font-weight:700; font-size:10px; }

.sortable { cursor:pointer; transition:background .15s; user-select:none; padding-right:22px !important; position:relative; }
.sortable:hover { background: var(--brand-primary-lt) !important; color: var(--brand-primary) !important; }
.sortable.active { color: var(--brand-primary) !important; }
.sort-asc::after  { content:" ↑"; font-size:11px; }
.sort-desc::after { content:" ↓"; font-size:11px; }

#nilTable .small.text-muted.fw-bold { font-size:12.5px !important; color:var(--text-body) !important; font-weight:600 !important; white-space:normal !important; line-height:1.4 !important; }

#topScroll::-webkit-scrollbar { height:5px; }
#topScroll::-webkit-scrollbar-track { background:var(--bg-header); border-radius:3px; }
#topScroll::-webkit-scrollbar-thumb { background:var(--border-strong); border-radius:3px; }
.table-responsive::-webkit-scrollbar { height:6px; }
.table-responsive::-webkit-scrollbar-thumb { background:var(--border-strong); border-radius:4px; }

.fw-bold.text-primary { font-family:'JetBrains Mono',monospace !important; font-size:13px !important; }

.alert-info { background:var(--brand-primary-lt) !important; border:1px solid var(--brand-primary-md) !important; border-radius:10px !important; color:#312e81 !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Dual scrollbar ──────────────────────────────────────────────────
    const mainScroll = document.getElementById('mainScroll');
    const topScroll  = document.getElementById('topScroll');
    const topInner   = document.getElementById('topScrollInner');

    function syncWidth() {
        if (topInner && mainScroll) topInner.style.width = mainScroll.scrollWidth + 'px';
    }
    syncWidth();
    if (typeof ResizeObserver !== 'undefined') new ResizeObserver(syncWidth).observe(mainScroll || document.body);

    let syncTop = false, syncMain = false;
    if (topScroll && mainScroll) {
        topScroll.addEventListener('scroll', function () {
            if (syncMain) return; syncTop = true;
            mainScroll.scrollLeft = topScroll.scrollLeft;
            setTimeout(() => syncTop = false, 50);
        });
        mainScroll.addEventListener('scroll', function () {
            if (syncTop) return; syncMain = true;
            topScroll.scrollLeft = mainScroll.scrollLeft;
            setTimeout(() => syncMain = false, 50);
        });
    }

    const scrollLeftBtn  = document.getElementById('scrollLeftBtn');
    const scrollRightBtn = document.getElementById('scrollRightBtn');
    if (scrollLeftBtn)  scrollLeftBtn.addEventListener('click',  () => { if (mainScroll) mainScroll.scrollLeft = 0; if (topScroll) topScroll.scrollLeft = 0; });
    if (scrollRightBtn) scrollRightBtn.addEventListener('click', () => { if (mainScroll) mainScroll.scrollLeft = mainScroll.scrollWidth; if (topScroll) topScroll.scrollLeft = mainScroll.scrollWidth; });

    // ── Search ──────────────────────────────────────────────────────────
    const searchInput    = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearchBtn');

    function filterRows() {
        const term = (searchInput?.value || '').toLowerCase().trim();
        if (clearSearchBtn) clearSearchBtn.style.display = term ? 'block' : 'none';
        let visible = 0;
        document.querySelectorAll('.nil-row').forEach(row => {
            const match = !term ||
                (row.dataset.name    || '').includes(term) ||
                (row.dataset.phone   || '').includes(term) ||
                (row.dataset.comments|| '').includes(term);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        const noMsg = document.getElementById('noResultsMessage');
        if (noMsg) noMsg.style.display = (visible === 0 && term) ? 'block' : 'none';
    }

    if (searchInput)    searchInput.addEventListener('input', filterRows);
    if (clearSearchBtn) clearSearchBtn.addEventListener('click', () => { searchInput.value = ''; filterRows(); searchInput.focus(); });

    // ── Sorting ─────────────────────────────────────────────────────────
    let currentSort = { col: 'sno', dir: 'desc' };

    function sortRows(col, dir) {
        const tbody = document.getElementById('nilTableBody');
        if (!tbody) return;
        const rows = Array.from(tbody.querySelectorAll('.nil-row'));
        rows.sort((a, b) => {
            switch (col) {
                case 'name':        return dir === 'asc' ? (a.dataset.name||'').localeCompare(b.dataset.name||'') : (b.dataset.name||'').localeCompare(a.dataset.name||'');
                case 'phone':       return dir === 'asc' ? (a.dataset.phone||'').localeCompare(b.dataset.phone||'') : (b.dataset.phone||'').localeCompare(a.dataset.phone||'');
                case 'salesperson': return dir === 'asc' ? (a.dataset.salesperson||'').localeCompare(b.dataset.salesperson||'') : (b.dataset.salesperson||'').localeCompare(a.dataset.salesperson||'');
                case 'type':        return dir === 'asc' ? (a.dataset.type||'').localeCompare(b.dataset.type||'') : (b.dataset.type||'').localeCompare(a.dataset.type||'');
                case 'comments':    return dir === 'asc' ? (a.dataset.comments||'').localeCompare(b.dataset.comments||'') : (b.dataset.comments||'').localeCompare(a.dataset.comments||'');
                case 'advance':     return dir === 'asc' ? (parseFloat(a.dataset.advance)||0) - (parseFloat(b.dataset.advance)||0) : (parseFloat(b.dataset.advance)||0) - (parseFloat(a.dataset.advance)||0);
                case 'date':        return dir === 'asc' ? (parseInt(a.dataset.date)||0) - (parseInt(b.dataset.date)||0) : (parseInt(b.dataset.date)||0) - (parseInt(a.dataset.date)||0);
                case 'products':    return dir === 'asc' ? (parseInt(a.dataset.productsCount)||0) - (parseInt(b.dataset.productsCount)||0) : (parseInt(b.dataset.productsCount)||0) - (parseInt(a.dataset.productsCount)||0);
                case 'qty':         return dir === 'asc' ? (parseInt(a.dataset.qty)||0) - (parseInt(b.dataset.qty)||0) : (parseInt(b.dataset.qty)||0) - (parseInt(a.dataset.qty)||0);
                case 'sno':
                default:            return dir === 'asc' ? (parseInt(a.dataset.sno)||0) - (parseInt(b.dataset.sno)||0) : (parseInt(b.dataset.sno)||0) - (parseInt(a.dataset.sno)||0);
            }
        });
        rows.forEach(r => tbody.appendChild(r));

        document.querySelectorAll('#nilTable .sortable').forEach(th => {
            th.classList.remove('active', 'sort-asc', 'sort-desc');
            if (th.dataset.sort === col) {
                th.classList.add('active', dir === 'asc' ? 'sort-asc' : 'sort-desc');
            }
        });
        currentSort = { col, dir };
    }

    document.querySelectorAll('#nilTable .sortable').forEach(th => {
        th.addEventListener('click', function () {
            const col = this.dataset.sort;
            const dir = (currentSort.col === col && currentSort.dir === 'asc') ? 'desc' : 'asc';
            sortRows(col, dir);
        });
    });

    setTimeout(() => sortRows('sno', 'desc'), 100);
});
</script>
@endsection
