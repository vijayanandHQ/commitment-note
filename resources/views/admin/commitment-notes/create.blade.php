@extends('layouts.sneat')

@section('title', 'Add Commitment Note')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 text-primary">
                    <i class="bx bx-plus-circle me-2"></i>Add Commitment Note
                </h5>
                <div id="kbnav-hint-bar-inline"></div>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.commitment-notes.store') }}" method="POST" id="commitmentForm" novalidate>
                    @csrf

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle" id="mainTable">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="5" class="text-center bg-primary text-white">Customer Details</th>
                                    <th colspan="4" class="text-center bg-success text-white">Product Details</th>
                                </tr>
                                <tr>
                                    <th width="12%">Customer Name <span class="text-danger">*</span></th>
                                    <th width="10%">Phone <span class="text-danger">*</span></th>
                                    <th width="12%">Delivery Date</th>
                                    <th width="10%">Delivery Type</th>
                                    <th width="12%">Comments</th>
                                    <th width="25%">Medicine Name</th>
                                    <th width="8%">Qty</th>
                                    <th width="8%">MRP (₹)</th>
                                    <th width="10%" class="text-center">
                                        Total (₹)
                                        <div class="d-flex justify-content-center gap-1 mt-1">
                                            <button type="button" class="btn btn-primary btn-sm px-2 py-0" id="addRowBtn" title="Add product row">
                                                <i class="bx bx-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm px-2 py-0" id="removeRowBtn" title="Remove last product row" style="display:none;">
                                                <i class="bx bx-minus"></i>
                                            </button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="mainTableBody">
                                @for($i = 0; $i < 3; $i++)
                                <tr class="product-row" data-row="{{ $i }}">
                                    @if($i == 0)
                                    <td rowspan="3" class="align-middle" style="background: #f8f9fa;">
                                        <input type="text" name="cus_name" class="form-control form-control-sm"
                                               value="{{ old('cus_name') }}" placeholder="Customer Name"
                                               id="customer-name" autofocus style="min-width: 135px;">
                                    </td>
                                    <td rowspan="3" class="align-middle" style="background: #f8f9fa;">
    <input type="text" 
           name="customer_phone" 
           id="customer-phone"
           class="form-control form-control-sm"
           value="{{ old('customer_phone') }}"
           placeholder="Phone Number"
           style="min-width: 95px;"
           maxlength="10"
           pattern="[0-9]{10}"
           inputmode="numeric"
           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);">
</td>
                                    <td rowspan="3" class="align-middle" style="background: #f8f9fa;">
                                        <input type="text" id="delivery-date-input" name="delivery_date"
                                               class="form-control form-control-sm"
                                               placeholder="DD/MM/YY" maxlength="8">
                                    </td>
                                    <td rowspan="3" class="align-middle" style="background: #f8f9fa;">
                                        <div class="d-flex flex-column gap-1">
                                          <div class="form-check">
                                                <input class="form-check-input delivery-radio" type="radio" name="delivery_type"
                                                       id="delivery-medical" value="medical" checked>
                                                <label class="form-check-label" for="delivery-medical">Medical</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input delivery-radio" type="radio" name="delivery_type"
                                                       id="delivery-home" value="home" >
                                                <label class="form-check-label" for="delivery-home">Home</label>
                                            </div>
                                            
                                        </div>
                                    </td>
                                    <td rowspan="3" class="align-middle" style="background: #f8f9fa;">
                                        <textarea name="commands" class="form-control form-control-sm" rows="2"
                                                  placeholder="Enter instructions...">{{ old('commands') }}</textarea>
                                    </td>
                                    @endif

                                    <td class="position-relative product-cell">
                                        <input type="text"
                                               name="products[{{ $i }}][product_name]"
                                               class="form-control form-control-sm product-name"
                                               id="product-{{ $i }}"
                                               placeholder="Search medicine (min 2 letters)..."
                                               autocomplete="off"
                                               data-row="{{ $i }}">
                                        <input type="hidden" name="products[{{ $i }}][medicine_id]" class="medicine-id">
                                        <input type="hidden" name="products[{{ $i }}][mrp]" class="product-mrp">
                                        <input type="hidden" name="products[{{ $i }}][stock_qty]" class="product-qty">
                                        <input type="hidden" name="products[{{ $i }}][supplier_name]" class="supplier-name">
                                        <input type="hidden" name="products[{{ $i }}][category]" class="product-category">
                                        <div class="suggestions-box" style="display: none;"></div>
                                    </td>
                                    <td>
                                        <input type="text"
                                               name="products[{{ $i }}][order_qty]"
                                               class="form-control form-control-sm product-order-qty"
                                               placeholder="Qty"
                                               inputmode="numeric">
                                    </td>

                                    <td class="text-center position-relative mrp-cell">
                                        <span class="product-mrp-display text-success fw-bold"
                                              style="display: none; cursor: pointer;"
                                              title="Click to edit MRP price">
                                            ₹<span class="mrp-value"></span>
                                        </span>
                                        <div class="mrp-edit-tooltip"
                                             style="display:none; position:absolute; z-index:9999;
                                                    background:#fff; border:2px solid #28a745;
                                                    border-radius:8px; padding:10px 12px;
                                                    box-shadow:0 4px 15px rgba(0,0,0,0.25);
                                                    min-width:190px; top:110%; left:50%;
                                                    transform:translateX(-50%);">
                                            <div style="font-size:12px; font-weight:700; color:#28a745; margin-bottom:6px;">
                                                ✏️ Edit MRP Price
                                            </div>
                                            <div class="d-flex gap-1 align-items-center">
                                                <input type="number"
                                                       class="mrp-edit-input form-control form-control-sm"
                                                       step="0.01" min="0" placeholder="New price"
                                                       style="width:105px;">
                                                <button type="button"
                                                        class="btn btn-success btn-sm mrp-update-btn"
                                                        style="white-space:nowrap; font-size:12px;">
                                                    Update
                                                </button>
                                            </div>
                                            <div class="mrp-edit-msg" style="font-size:11px; margin-top:5px; min-height:14px;"></div>
                                            <div style="position:absolute; top:-9px; left:50%;
                                                        transform:translateX(-50%);
                                                        width:0; height:0;
                                                        border-left:9px solid transparent;
                                                        border-right:9px solid transparent;
                                                        border-bottom:9px solid #28a745;">
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <span class="product-total-display text-primary fw-bold" style="display: none;">
                                            ₹<span class="total-value"></span>
                                        </span>
                                    </td>
                                    
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                   <div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Left Side: Inputs Group -->
    <div class="d-flex gap-2">
        <div style="width: 250px;">
            <input type="text" name="sales_person_name" class="form-control"
                   placeholder="Sales Person Name" value="{{ old('sales_person_name') }}">
        </div>
        <div style="width: 180px;">
            <input type="text"
                   name="advance_amount"
                   id="advance_amount"
                   class="form-control"
                   placeholder="Advance Amount"
                   value="{{ old('advance_amount') }}"
                   onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46"
                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
        </div>
    </div>

    <!-- Right Side: Action Buttons Group -->
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bx bx-save me-2"></i>SAVE
        </button>
        <button type="button" class="btn btn-outline-danger px-4" id="clearFormBtn">
            <i class="bx bx-refresh me-2"></i>Clear
        </button>
        <a href="{{ route('admin.commitment-notes.index') }}" class="btn btn-outline-secondary px-4" id="cancelBtn">
            <i class="bx bx-x me-2"></i>Cancel
        </a>
    </div>
</div>
                </form>

                @if(isset($recentCommitments) && $recentCommitments->count() > 0)
                <div class="saved-details-section mt-4">
                    <div class="card border-success">

                        <div class="card-header bg-success text-white py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="bx bx-history me-2"></i>Recently Saved Commitment Notes</h6>

                                <div class="d-flex align-items-center gap-2">

                                    <button type="button" id="scrollLeftBtn"
                                            title="Scroll to left end"
                                            style="width:32px; height:32px; border-radius:50%;
                                                   background:rgba(255,255,255,0.25); border:2px solid rgba(255,255,255,0.7);
                                                   color:#fff; display:flex; align-items:center; justify-content:center;
                                                   cursor:pointer; transition:all 0.2s; flex-shrink:0; padding:0;">
                                        <i class="bx bx-chevrons-left" style="font-size:18px; line-height:1;"></i>
                                    </button>

                                    <button type="button" id="scrollRightBtn"
                                            title="Scroll to right end"
                                            style="width:32px; height:32px; border-radius:50%;
                                                   background:rgba(255,255,255,0.25); border:2px solid rgba(255,255,255,0.7);
                                                   color:#fff; display:flex; align-items:center; justify-content:center;
                                                   cursor:pointer; transition:all 0.2s; flex-shrink:0; padding:0;">
                                        <i class="bx bx-chevrons-right" style="font-size:18px; line-height:1;"></i>
                                    </button>

                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <span class="input-group-text bg-white border-success">
                                            <i class="bx bx-search text-success"></i>
                                        </span>
                                        <input type="text" id="recentSearchInput" class="form-control form-control-sm border-success"
                                               placeholder="Search by name, phone, product..." autocomplete="off">
                                        <button class="btn btn-sm btn-outline-success" type="button" id="clearSearchBtn" style="display: none;">
                                            <i class="bx bx-x"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card-body">

                            {{-- View Mode Menu Buttons --}}
<div class="d-flex gap-2 mb-3">
    <button type="button" id="btnCommitmentOrder"
            class="btn btn-sm btn-outline-primary"
            onclick="switchTableView('commitment')">
        <i class="bx bx-receipt me-1"></i>Commitment Order
    </button>
    <button type="button" id="btnFollowUp"
            class="btn btn-sm btn-outline-primary"
            onclick="switchTableView('followup')">
        <i class="bx bx-phone-call me-1"></i>Follow Up
    </button>
    <button type="button" id="btnResetAll"
            class="btn btn-sm btn-outline-secondary"
            onclick="resetTableView()">
        <i class="bx bx-reset me-1"></i>Reset All
    </button>
</div>

                            <div id="topScroll" style="overflow-x: auto; overflow-y: hidden; height: 17px; margin-bottom: 2px;">
                                <div id="topScrollInner" style="height: 1px;"></div>
                            </div>

                            <div class="table-responsive" id="mainScroll">
                                <table class="table table-bordered align-middle" id="recentTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="sortable sticky-col" data-sort="name" style="cursor: pointer;">Customer Name </th>
                                            <th class="text-center sortable" data-sort="sno" style="width: 80px; cursor: pointer;">S.No</th>
                                            <th class="sortable" data-sort="phone" style="cursor: pointer;">Phone </th>
                                            <th class="sortable text-center" data-sort="salesperson" style="cursor: pointer;">Sales<br>Person<br>Name</th>
                                            <th class="sortable text-center" data-sort="advance" style="cursor: pointer;">Advance<br>Amount</th>
                                            <th class="sortable text-center" data-sort="date" style="cursor: pointer;">Delivery<br>Date</th>
<th class="sortable text-center" data-sort="type" style="cursor: pointer;">Delivery<br>Type</th>
                                            <th class="sortable" data-sort="comments" style="cursor: pointer;">Comments </th>
                                            <th class="sortable" data-sort="products" style="cursor: pointer; min-width: 200px; width: 200px;">Products </th>
                                            <th class="sortable text-center" data-sort="qty" style="cursor: pointer;">
    <div class="d-flex flex-column align-items-center">
        <span>Cus Qty</span>
        <small  style="font-size: 12px;  text-transform: none; font-weight:bold;"> (in single unit) </small>
    </div>
</th>
                                            <th class="sortable text-center" data-sort="orderqty" style="cursor: pointer;" width="8%">Order <br> Qty</th>
                                            <th class="sortable text-start" data-sort="supplier" style="cursor: pointer;" width="15%">Supplier</th>
                                            <th class="sortable text-start" data-sort="remarks" style="cursor: pointer;" width="15%">Remarks</th>
                                            <th class="sortable text-center" data-sort="stage" style="cursor: pointer;">
    Current <br> Stage
</th>
                                            {{-- Replace the existing globalMoreToggle button in your <th> with this: --}}

<th style="min-width: 280px;" class="text-start">
    <div style="display:flex; flex-direction:column; gap:6px; align-items:flex-start;">
        <span>Actions</span>
        <button type="button" id="globalMoreToggle"
                style="
                    font-size: 11px;
                    font-weight: 600;
                    color: #4f46e5;
                    background: #eef2ff;
                    border: 1.5px solid #c7d2fe;
                    border-radius: 20px;
                    padding: 3px 10px 3px 10px;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                    margin-top: 2px;
                    transition: all 0.18s;
                    line-height: 1.6;
                    letter-spacing: 0.01em;
                "
                onmouseover="this.style.background='#4f46e5';this.style.color='#fff';this.style.borderColor='#4f46e5';"
                onmouseout="this.style.background='#eef2ff';this.style.color='#4f46e5';this.style.borderColor='#c7d2fe';">
            <span id="globalMoreLabel">More</span>
            {{-- Chevron Down (shown in "More" state) --}}
            <svg id="globalMoreIcon" xmlns="http://www.w3.org/2000/svg"
                 width="13" height="13" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
            {{-- Chevron Up (shown in "Less" state) --}}
            <svg id="globalLessIcon" xmlns="http://www.w3.org/2000/svg"
                 width="13" height="13" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round"
                 style="display:none;">
                <polyline points="18 15 12 9 6 15"></polyline>
            </svg>
        </button>
    </div>
</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recentCommitmentsBody">
                                        @foreach($recentCommitments as $note)
                                        @php
                                            $products     = $note->products;
                                            $productCount = $products->count();
                                            $totalQty     = $products->sum('quantity');
                                            $totalPrice   = $products->sum('total_price');
                                            $firstOrderQty  = $products->first()?->order_qty ?? 0;
                                            $firstSupplier  = $products->first()?->supplier?->name ?? '';
                                            $firstRemarks   = $products->first()?->remarks ?? '';
                                            $totalOrderQty  = $products->sum('order_qty');

                                            $stageColors = [
                                                'pending_supplier'       => ['bg' => 'warning',   'icon' => 'bx-time',        'text' => 'Pending'],
                                                'received_from_supplier' => ['bg' => 'info',      'icon' => 'bx-package',     'text' => 'Received'],
                                                'customer_contacted'     => ['bg' => 'primary',   'icon' => 'bx-phone-call',  'text' => 'Contacted'],
                                                'delivered'              => ['bg' => 'success',   'icon' => 'bx-check-circle','text' => 'Delivered'],
                                                'returned'               => ['bg' => 'danger',    'icon' => 'bx-undo',        'text' => 'Returned'],
                                            ];

                                            $currentStage = $note->workflow_stage ?? 'pending_supplier';
                                            $stageInfo    = $stageColors[$currentStage] ?? ['bg' => 'secondary', 'icon' => 'bx-question-mark', 'text' => 'Unknown'];
                                        @endphp
                                        <tr class="recent-note-row"
                                        data-id="{{ $note->id }}"
                                        data-name="{{ strtolower($note->cus_name ?? '') }}"
                                        data-phone="{{ $note->customer_phone ?? '' }}"
                                        data-date="{{ $note->delivery_date ? strtotime($note->delivery_date) : 0 }}"
                                        data-type="{{ $note->delivery_type ?? '' }}"
                                        data-stage="{{ $currentStage }}"
                                        data-stage-text="{{ $stageInfo['text'] }}"
                                        data-comments="{{ strtolower($note->comments ?? '') }}"
                                        data-products-count="{{ $productCount }}"
                                        data-qty="{{ $totalQty }}"
                                        data-amount="{{ $totalPrice }}"
                                        data-sno="{{ $note->id }}"
                                        data-salesperson="{{ strtolower($note->sales_person_name ?? '') }}"
                                        data-advance="{{ $note->advance_amount ?? 0 }}"
                                        data-orderqty="{{ $totalOrderQty }}"
                                        data-supplier="{{ strtolower($firstSupplier) }}"
                                        data-remarks="{{ strtolower($firstRemarks) }}">
                                            
                                            <td class="sticky-col">{{ $note->cus_name }}</td>
                                            <td class="text-center fw-bold text-muted" style="font-family: 'JetBrains Mono', monospace; font-size: 12px;">
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

                                            <td class="text-center">
                                                @if($productCount > 0)
                                                    <div class="d-flex flex-column gap-1">
                                                        @foreach($products as $prod)
                                                            <div style="height: 38px;" class="text-start d-flex align-items-center">
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

                                            <td class="text-center">
    @if($productCount > 0)
        <div class="d-flex flex-column gap-1" style="align-items: center !important;">
            @foreach($products as $prod)
                <div style="height: 38px; display:flex; align-items:center; justify-content:center; width:100%;">
                    <span class="badge bg-warning">{{ $prod->quantity }}</span>
                </div>
            @endforeach
        </div>
    @else
        <span class="badge bg-warning">0</span>
    @endif
</td>

                                            <td class="text-center position-relative">
                                                @if($productCount > 0)
                                                    <div class="d-flex flex-column gap-1">
                                                        @foreach($products as $prod)
                                                            <div style="height: 38px;" class="d-flex align-items-center justify-content-center">
                                                                <input type="number"
                                                                       class="form-control form-control-sm text-center px-1 no-spinner open-update-tooltip"
                                                                       style="width: 50px; height: 30px; font-size: 12px; border-color: #d9dee3; cursor: pointer;"
                                                                       value="{{ $prod->order_qty ?? 0 }}" readonly
                                                                       data-product-id="{{ $prod->id }}"
                                                                       data-qty="{{ $prod->order_qty ?? 0 }}"
                                                                       data-supplier-id="{{ $prod->supplier_id ?? '' }}"
                                                                       data-supplier-name="{{ $prod->supplier->name ?? '' }}"
                                                                       data-remarks="{{ $prod->remarks ?? '' }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="text-start position-relative">
                                                @if($productCount > 0)
                                                    <div class="d-flex flex-column gap-1">
                                                        @foreach($products as $prod)
                                                            <div style="height: 38px;" class="d-flex align-items-center">
                                                                <input type="text"
                                                                       class="form-control form-control-sm px-2 open-update-tooltip"
                                                                       style="width: 140px; height: 30px; font-size: 12px; border-color: #d9dee3; cursor: pointer;"
                                                                       value="{{ $prod->supplier->name ?? '' }}" readonly placeholder="Supplier..."
                                                                       data-product-id="{{ $prod->id }}"
                                                                       data-qty="{{ $prod->order_qty ?? 0 }}"
                                                                       data-supplier-id="{{ $prod->supplier_id ?? '' }}"
                                                                       data-supplier-name="{{ $prod->supplier->name ?? '' }}"
                                                                       data-remarks="{{ $prod->remarks ?? '' }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="text-start position-relative">
                                                @if($productCount > 0)
                                                    <div class="d-flex flex-column gap-1">
                                                        @foreach($products as $prod)
                                                            <div style="height: 38px;" class="d-flex align-items-center">
                                                                <input type="text"
                                                                       class="form-control form-control-sm px-2 open-update-tooltip"
                                                                       style="width: 150px; height: 30px; font-size: 11px; border-color: #d9dee3; cursor: pointer;"
                                                                       value="{{ $prod->remarks ?? '' }}" readonly placeholder="Remarks..."
                                                                       data-product-id="{{ $prod->id }}"
                                                                       data-qty="{{ $prod->order_qty ?? 0 }}"
                                                                       data-supplier-id="{{ $prod->supplier_id ?? '' }}"
                                                                       data-supplier-name="{{ $prod->supplier->name ?? '' }}"
                                                                       data-remarks="{{ $prod->remarks ?? '' }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <div class="product-update-tooltip shadow-lg"
                                                     style="display:none; position:fixed; z-index:99999; background:#fff; border:2px solid #696cff; border-radius:8px; padding:12px; min-width:480px;">
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <input type="number" class="form-control form-control-sm t-qty no-spinner" placeholder="Qty" style="width:65px;">
                                                        <div class="position-relative" style="flex:1;">
                                                            <input type="text" class="form-control form-control-sm t-supplier" placeholder="Search Supplier...">
                                                            <input type="hidden" class="t-supplier-id">
                                                            <div class="supplier-suggestions shadow-sm border"
                                                                 style="display:none; position:absolute; width:100%; background:#fff; z-index:100002; max-height:180px; overflow-y:auto;"></div>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm t-remarks" placeholder="Remarks" style="flex:1;">
                                                        <button type="button" class="btn btn-primary btn-sm btn-update-row">Update</button>
                                                    </div>
                                                    <div class="update-msg mt-1 small" style="height:15px; font-weight: bold;"></div>
                                                </div>
                                            </td>

                                           <td class="text-center">
    @if($productCount > 0)
        <div class="d-flex flex-column gap-2 py-1">
            @foreach($products as $prod)
                @php
                    if ($prod->returned_status == 0) {
                        $stageInfo = ['bg' => 'danger', 'icon' => 'bx-undo', 'text' => 'Returned'];
                    } elseif ($prod->delivered_status == 0) {
                        // Using success class for Delivered (now styled as Teal)
                        $stageInfo = ['bg' => 'success', 'icon' => 'bx-check-circle', 'text' => 'Delivered'];
                    } elseif ($prod->contacted_status == 0) {
                        $stageInfo = ['bg' => 'primary', 'icon' => 'bx-phone-call', 'text' => 'Contacted'];
                    } elseif ($prod->received_status == 0) {
                        // Using info class for Received (now styled as Green per your request)
                        $stageInfo = ['bg' => 'info', 'icon' => 'bx-package', 'text' => 'Received'];
                    } else {
                        $stageInfo = ['bg' => 'warning', 'icon' => 'bx-time', 'text' => 'Pending'];
                    }
                @endphp
                <div style="height: 38px;" class="d-flex align-items-center justify-content-center">
                    <span class="badge bg-{{ $stageInfo['bg'] }} d-flex align-items-center gap-2 stage-badge"
                          data-index="{{ $loop->index }}">
                        <i class="bx {{ $stageInfo['icon'] }} fs-6"></i>
                        <span>{{ $stageInfo['text'] }}</span>
                    </span>
                </div>
            @endforeach
        </div>
    @else
        <span class="badge bg-secondary stage-badge">No products</span>
    @endif
</td>

                                            <td class="text-center">
    @if($productCount > 0)
        <div class="d-flex flex-column gap-1">
            @foreach($products as $prod)
            <div style="height: 38px;" class="d-flex gap-1 justify-content-center align-items-center">

                <button class="btn action-btn product-status-btn p-1
               {{ $prod->received_status == 0 ? 'btn-secondary disabled-status' : 'btn-outline-danger' }}"
        data-product-id="{{ $prod->id }}"
        data-status-field="received_status"
        {{ $prod->received_status == 0 ? 'disabled' : '' }}
        @if($prod->received_status == 0)
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            data-bs-custom-class="custom-tooltip"
            data-bs-html="true"
            title="Already Received<br>{{ \Carbon\Carbon::parse($prod->updated_at)->format('h:i A') }}<br>{{ \Carbon\Carbon::parse($prod->updated_at)->format('l') }}<br>{{ \Carbon\Carbon::parse($prod->updated_at)->format('d-m-Y') }}"
        @else
            title="Mark as Received"
        @endif>
    <i class="bx bx-package fs-5"></i>
</button>

                <button class="btn action-btn product-status-btn p-1
               {{ $prod->contacted_status == 0 ? 'btn-secondary disabled-status' : 'btn-outline-danger' }}"
        data-product-id="{{ $prod->id }}"
        data-status-field="contacted_status"
        {{ $prod->contacted_status == 0 ? 'disabled' : '' }}
        @if($prod->contacted_status == 0)
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            data-bs-custom-class="custom-tooltip"
            data-bs-html="true"
            title="Already Contacted<br>{{ \Carbon\Carbon::parse($prod->updated_at)->format('h:i A') }}<br>{{ \Carbon\Carbon::parse($prod->updated_at)->format('l') }}<br>{{ \Carbon\Carbon::parse($prod->updated_at)->format('d-m-Y') }}"
        @else
            title="Mark as Contacted"
        @endif>
    <i class="bx bx-phone-call fs-5"></i>
</button>

                <button class="btn action-btn product-status-btn p-1
                               {{ $prod->delivered_status == 0 ? 'btn-secondary disabled-status' : 'btn-outline-danger' }}"
                        data-product-id="{{ $prod->id }}"
                        data-status-field="delivered_status"
                        {{ $prod->delivered_status == 0 ? 'disabled' : '' }}
                        title="{{ $prod->delivered_status == 0 ? 'Already Delivered' : 'Mark as Delivered' }}">
                    <i class="bx bx-check-circle fs-5"></i>
                </button>

                @if($note->delivery_type == 'home')
                <button class="btn action-btn p-1 btn-outline-info" style="cursor:default;"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-custom-class="custom-tooltip" title="Delivery at home">
                    <i class="bx bx-home-alt fs-5" style="color:#0284c7;"></i>
                </button>
                @elseif($note->delivery_type == 'medical')
                <button class="btn action-btn p-1 btn-outline-success" style="cursor:default;"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-custom-class="custom-tooltip" title="Delivery at medical">
                    <i class="bx bx-plus-medical fs-5" style="color:#16a34a;"></i>
                </button>
                @endif

                {{-- Place this BEFORE the existing returned_status button --}}

<button class="btn action-btn product-status-btn p-1
                {{ $prod->ns_status == 0 ? 'btn-primary disabled-status' : 'btn-outline-primary' }}"
        data-product-id="{{ $prod->id }}"
        data-status-field="ns_status"
        {{ $prod->ns_status == 0 ? 'disabled' : '' }}
        title="{{ $prod->ns_status == 0 ? 'Already NS' : 'Mark as Not Supply' }}"
        style="font-size:11px; font-weight:700; min-width:32px;">
    NS
</button>

                <button class="btn action-btn product-status-btn p-1
                               {{ $prod->returned_status == 0 ? 'btn-danger disabled-status' : 'btn-outline-danger' }}"
                        data-product-id="{{ $prod->id }}"
                        data-status-field="returned_status"
                        {{ $prod->returned_status == 0 ? 'disabled' : '' }}
                        title="{{ $prod->returned_status == 0 ? 'Already Returned' : 'Mark as Returned' }}">
                    <i class="bx bx-undo fs-5"></i>
                </button>

                <div style="width: 1px; height: 20px; background: #dee2e6; margin: 0 2px;"></div>

                {{-- Edit / View / Delete hidden by default, shown by global toggle --}}
                <div class="extra-action-btns" style="display:none; align-items:center; gap:4px;">
                    <button type="button" class="btn btn-outline-warning p-1 open-edit-modal"
                            style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;"
                            title="Edit" data-product-id="{{ $prod->id }}">
                        <i class="bx bx-edit fs-6"></i>
                    </button>
                    <button type="button" class="btn btn-outline-info p-1 open-show-modal"
                            style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;"
                            title="View" data-product-id="{{ $prod->id }}">
                        <i class="bx bx-show fs-6"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger p-1"
                            style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;"
                            title="Delete" onclick="confirmDelete({{ $prod->id }})">
                        <i class="bx bx-trash fs-6"></i>
                    </button>
                </div>

            </div>
            @endforeach
        </div>
    @else
        <span class="text-muted small">No Actions</span>
    @endif
</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="noResultsMessage" class="alert alert-info text-center mt-3" style="display: none;">
                                <i class="bx bx-info-circle me-2"></i>No matching records found
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">
                                    <i class="bx bx-info-circle me-1"></i>Click on any column header to sort
                                </div>
                                <a href="{{ route('admin.commitment-notes.all') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bx bx-list-ul me-1"></i>View All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('success'))
                <!-- <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div> -->
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                    <i class="bx bx-error me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Edit Product Modal --}}
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:14px;overflow:hidden;">
            <div class="modal-header" style="background:#5f6bfa;padding:14px 20px;">
                <h5 class="modal-title text-white" style="font-size:14px;font-weight:700;">
                    <i class="bx bx-edit-alt me-2"></i>Edit Product
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="editProductModalBody" style="padding:0;background:#f8fafc;">
                <div class="text-center py-5">
                    <div class="spinner-border" style="color:#5f6bfa;"></div>
                    <p class="mt-3 text-muted">Loading edit form…</p>
                </div>
            </div>
            <div class="modal-footer" style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:12px 20px;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" id="editModalSaveBtn"
                        style="background:#5f6bfa;color:#fff;font-weight:600;border:none;border-radius:8px;padding:8px 22px;font-size:13px;cursor:pointer;">
                    <i class="bx bx-save me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Show (View) Product Modal --}}
<div class="modal fade" id="showProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:14px;overflow:hidden;">
            <div class="modal-header" style="background:#38bdf8;padding:14px 20px;">
                <h5 class="modal-title text-white" style="font-size:14px;font-weight:700;">
                    <i class="bx bx-file-find me-2"></i>View Product
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="showProductModalBody" style="padding:0;background:#f8fafc;">
                <div class="text-center py-5">
                    <div class="spinner-border text-info"></div>
                    <p class="mt-3 text-muted">Loading details…</p>
                </div>
            </div>
            <div class="modal-footer" style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:12px 20px;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this commitment note? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.sortable { cursor: pointer; transition: background-color 0.2s ease; user-select: none; position: relative; padding-right: 25px !important; }
.sortable:hover { background-color: #e9ecef !important; }
.sort-icon { display: inline-block; font-size: 14px; color: #6c757d; transition: all 0.2s ease; }
.sortable.active .sort-icon i { color: #28a745 !important; }
.sortable.active .sort-icon { color: #28a745; }
.sort-asc .sort-icon::after  { content: "↑"; margin-left: 3px; }
.sort-desc .sort-icon::after { content: "↓"; margin-left: 3px; }

.action-buttons { display: flex; gap: 0.5rem; justify-content: center; }
.action-btn { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; }
.action-btn i { font-size: 1.25rem; }
.action-btn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 2px 8px rgba(0,0,0,0.15); }

.disabled-status {
    opacity: 0.40 !important;
    cursor: not-allowed !important;
    pointer-events: auto !important;
}

.suggestions-box { display: none; position: fixed; background: white; border: 2px solid #28a745; border-radius: 8px; max-height: 450px; overflow-y: auto; box-shadow: 0 8px 20px rgba(0,0,0,0.25); z-index: 999999; min-width: 500px; }
.suggestion-item { padding: 15px; cursor: pointer; border-bottom: 1px solid #e0e0e0; background: white; transition: all 0.2s; }
.suggestion-item:hover  { background-color: #28a745; color: white; }
.suggestion-item.selected { background-color: #28a745; color: white; }
.suggestion-main strong { font-size: 16px; display: block; margin-bottom: 6px; }
.medicine-details { display: flex; gap: 15px; font-size: 12px; flex-wrap: wrap; }
.supplier-section { margin-top: 8px; padding-top: 8px; border-top: 1px dashed #e0e0e0; }
.supplier-info { color: #28a745; font-weight: 600; }

.product-order-qty { -moz-appearance: textfield; appearance: textfield; }
.product-order-qty::-webkit-outer-spin-button,
.product-order-qty::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

.product-mrp-display { cursor: pointer; border-bottom: 1px dashed #28a745; padding-bottom: 1px; transition: opacity 0.2s; }
.product-mrp-display:hover { opacity: 0.75; }
.mrp-cell { overflow: visible !important; }

.highlight { background-color: #fff3cd !important; transition: background-color 0.3s ease; }
.fade-in { animation: fadeIn 0.3s ease-in; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

.view-products-btn { white-space: nowrap; transition: all 0.2s ease; }
.view-products-btn:hover { background-color: #0dcaf0; border-color: #0dcaf0; color: white; transform: translateY(-1px); }

.info-box { border: 1px solid #dee2e6; border-radius: 8px; background: #f8f9fa; }
.product-table th { background-color: #e8f5e9; }

.mrp-edit-input::-webkit-outer-spin-button,
.mrp-edit-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.mrp-edit-input { -moz-appearance: textfield; }

#addRowBtn, #removeRowBtn { font-size: 13px; line-height: 1; border-radius: 4px; }

.recent-note-row td .d-flex.flex-column div { display: flex; align-items: center; justify-content: center; margin-bottom: 2px; }
.action-btn { width: 32px !important; height: 32px !important; }

.no-spinner::-webkit-outer-spin-button,
.no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.no-spinner { -moz-appearance: textfield; }

#topScroll::-webkit-scrollbar { height: 8px; }
#topScroll::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
#topScroll::-webkit-scrollbar-thumb { background: #adb5bd; border-radius: 4px; }
#topScroll::-webkit-scrollbar-thumb:hover { background: #6c757d; }

#scrollLeftBtn:hover, #scrollRightBtn:hover {
    background: rgba(255,255,255,0.45) !important;
    border-color: #fff !important;
    transform: scale(1.12);
}
#scrollLeftBtn:active, #scrollRightBtn:active {
    transform: scale(0.92);
    background: rgba(255,255,255,0.6) !important;
}

#recentTable .sticky-col {
    position: sticky;
    left: 0;
    z-index: 2;
    background-color: #fff;
    min-width: 130px;
    max-width: 160px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    box-shadow: 3px 0 6px -2px rgba(0,0,0,0.15);
}
#recentTable thead .sticky-col {
    background-color: #f8f9fa;
    z-index: 3;
}
#recentTable tbody tr:hover .sticky-col {
    background-color: #f0fff4;
}

.col-fold-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: rgba(0,0,0,0.10);
    border: 1px solid rgba(0,0,0,0.15);
    cursor: pointer;
    font-size: 10px;
    line-height: 1;
    margin-left: 5px;
    vertical-align: middle;
    transition: background 0.18s, transform 0.12s;
    color: #444;
    flex-shrink: 0;
    padding: 0;
}
.col-fold-btn:hover  { background: rgba(0,0,0,0.25); transform: scale(1.18); }
.col-fold-btn:active { transform: scale(0.9); }

.col-restore-btn {
    background: rgba(40,167,69,0.18);
    border-color: rgba(40,167,69,0.4);
    color: #155724;
}
.col-restore-btn:hover { background: rgba(40,167,69,0.38); }

#recentTable thead th { white-space: nowrap; }

.th-inner {
    display: inline-flex;
    align-items: center;
    gap: 2px;
    flex-wrap: nowrap;
}

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap');

:root {
  --brand-primary:    #4f46e5;
  --brand-primary-lt: #eef2ff;
  --brand-primary-md: #c7d2fe;
  --brand-teal:       #0d9488;
  --brand-teal-lt:    #f0fdfa;
  --brand-teal-md:    #99f6e4;
  --color-success:    #059669;
  --color-warning:    #d97706;
  --color-danger:     #dc2626;
  --color-info:       #0284c7;
  --bg-page:          #f1f5f9;
  --bg-card:          #ffffff;
  --bg-subtle:        #f8fafc;
  --bg-header:        #f1f5f9;
  --border-default:   #e2e8f0;
  --border-strong:    #cbd5e1;
  --text-heading:     #1e293b;
  --text-body:        #334155;
  --text-muted:       #64748b;
  --text-faint:       #94a3b8;
  --shadow-xs:  0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
  --shadow-sm:  0 2px 8px rgba(15,23,42,.08), 0 1px 3px rgba(15,23,42,.04);
  --shadow-md:  0 4px 20px rgba(15,23,42,.10), 0 2px 6px rgba(15,23,42,.06);
  --shadow-lg:  0 10px 40px rgba(15,23,42,.14), 0 4px 12px rgba(15,23,42,.08);
  --r-xs: 4px;
  --r-sm: 6px;
  --r-md: 10px;
  --r-lg: 14px;
  --r-xl: 18px;
  --transition: .16s cubic-bezier(.4,0,.2,1);
}

body, .card, .table, .form-control, .btn, th, td, label,
.modal, .badge, .alert, input, textarea, select {
  font-family: 'Inter', system-ui, sans-serif !important;
  color: var(--text-body);
}

.product-mrp-display,
.product-total-display,
.badge.bg-warning,
.fw-bold.text-success,
#recentTable td .text-success.fw-bold {
  font-family: 'JetBrains Mono', monospace !important;
}

.row { background: transparent; }

.card.shadow-sm {
  border: 1px solid var(--border-default) !important;
  border-radius: var(--r-xl) !important;
  box-shadow: var(--shadow-sm) !important;
  background: var(--bg-card);
  overflow: hidden;
}

.card-header.bg-white {
  background: var(--bg-card) !important;
  border-bottom: 1px solid var(--border-default) !important;
  padding: 16px 24px !important;
  border-radius: 0 !important;
}

.card-header .card-title {
  font-size: 15px !important;
  font-weight: 700 !important;
  letter-spacing: -.015em !important;
  color: var(--text-heading) !important;
}
.card-header .card-title i {
  color: var(--brand-primary) !important;
}

.card-body {
  padding: 20px 24px !important;
  background: var(--bg-subtle);
}

#mainTable {
  border-radius: var(--r-md) !important;
  overflow: hidden;
  border-collapse: separate !important;
  border-spacing: 0 !important;
  border: 1px solid var(--border-default) !important;
  background: #fff;
  box-shadow: var(--shadow-xs);
}

#mainTable thead tr:first-child th.bg-primary {
  background: var(--brand-primary) !important;
  color: #fff !important;
  font-size: 10.5px !important;
  font-weight: 700 !important;
  letter-spacing: .09em !important;
  text-transform: uppercase !important;
  padding: 10px 16px !important;
  border: none !important;
}
#mainTable thead tr:first-child th.bg-success {
  background: var(--brand-teal) !important;
  color: #fff !important;
  font-size: 10.5px !important;
  font-weight: 700 !important;
  letter-spacing: .09em !important;
  text-transform: uppercase !important;
  padding: 10px 16px !important;
  border: none !important;
}

#mainTable thead tr:last-child th {
  background: var(--bg-header) !important;
  font-size: 11px !important;
  font-weight: 600 !important;
  color: var(--text-muted) !important;
  text-transform: uppercase !important;
  letter-spacing: .07em !important;
  padding: 9px 12px !important;
  border-bottom: 1px solid var(--border-strong) !important;
  border-top: none !important;
  white-space: nowrap;
}

#mainTable tbody td {
  padding: 10px 12px !important;
  border-color: var(--border-default) !important;
  background: #fff !important;
  vertical-align: middle !important;
  transition: background var(--transition);
}
#mainTable tbody td[style*="background"] {
  background: var(--bg-subtle) !important;
}

.form-control,
.form-control-sm {
  border: 1.5px solid var(--border-default) !important;
  border-radius: var(--r-sm) !important;
  font-size: 13px !important;
  color: var(--text-body) !important;
  background: #fff !important;
  transition: border-color var(--transition), box-shadow var(--transition) !important;
  padding: 5px 10px !important;
  height: auto !important;
}
.form-control:focus,
.form-control-sm:focus {
  border-color: var(--brand-primary) !important;
  box-shadow: 0 0 0 3px rgba(79,70,229,.10) !important;
  outline: none !important;
}
.form-control::placeholder,
.form-control-sm::placeholder {
  color: var(--text-faint) !important;
  font-size: 12px !important;
}

textarea.form-control {
  resize: vertical;
  line-height: 1.5;
}

.form-check-input[type="radio"] { accent-color: var(--brand-primary); }
.form-check-label {
  font-size: 13px !important;
  font-weight: 500 !important;
  color: var(--text-body) !important;
}

.btn-primary {
  background: var(--brand-primary) !important;
  border-color: var(--brand-primary) !important;
  color: #fff !important;
  border-radius: var(--r-sm) !important;
  font-size: 13px !important;
  font-weight: 600 !important;
  letter-spacing: .01em !important;
  box-shadow: 0 1px 4px rgba(79,70,229,.30) !important;
  transition: all var(--transition) !important;
}
.btn-primary:hover:not(:disabled) {
  background: #4338ca !important;
  border-color: #4338ca !important;
  box-shadow: 0 4px 14px rgba(79,70,229,.35) !important;
  transform: translateY(-1px) !important;
}
.btn-primary:active { transform: translateY(0) !important; }

.btn-success {
  background: var(--color-success) !important;
  border-color: var(--color-success) !important;
  border-radius: var(--r-sm) !important;
  font-weight: 600 !important;
  transition: all var(--transition) !important;
}
.btn-success:hover:not(:disabled) {
  background: #047857 !important;
  transform: translateY(-1px) !important;
}

.btn-outline-danger {
  color: var(--color-danger) !important;
  border-color: #fca5a5 !important;
  border-radius: var(--r-sm) !important;
  font-weight: 600 !important;
  font-size: 13px !important;
  transition: all var(--transition) !important;
}
.btn-outline-danger:hover:not(:disabled) {
  background: var(--color-danger) !important;
  border-color: var(--color-danger) !important;
  color: #fff !important;
  transform: translateY(-1px) !important;
}

.btn-outline-secondary {
  color: var(--text-muted) !important;
  border-color: var(--border-strong) !important;
  border-radius: var(--r-sm) !important;
  font-weight: 600 !important;
  font-size: 13px !important;
  transition: all var(--transition) !important;
}
.btn-outline-secondary:hover {
  background: var(--bg-header) !important;
  border-color: var(--border-strong) !important;
  color: var(--text-heading) !important;
}

.btn-outline-primary {
  color: var(--brand-primary) !important;
  border-color: var(--brand-primary-md) !important;
  border-radius: var(--r-sm) !important;
  font-weight: 600 !important;
  font-size: 12.5px !important;
  transition: all var(--transition) !important;
}
.btn-outline-primary:hover {
  background: var(--brand-primary-lt) !important;
  border-color: var(--brand-primary) !important;
  color: var(--brand-primary) !important;
  transform: translateY(-1px) !important;
}

.btn-outline-warning {
  color: var(--color-warning) !important;
  border-color: #fde68a !important;
  border-radius: var(--r-sm) !important;
  transition: all var(--transition) !important;
}
.btn-outline-warning:hover {
  background: var(--color-warning) !important;
  border-color: var(--color-warning) !important;
  color: #fff !important;
  transform: translateY(-1px) !important;
}

.btn-outline-info {
  color: var(--color-info) !important;
  border-color: #bae6fd !important;
  border-radius: var(--r-sm) !important;
  transition: all var(--transition) !important;
}
.btn-outline-info:hover {
  background: var(--color-info) !important;
  border-color: var(--color-info) !important;
  color: #fff !important;
  transform: translateY(-1px) !important;
}

.d-flex.gap-2.justify-content-end.mb-4 .btn {
  min-width: 110px;
  height: 40px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}

#addRowBtn {
  background: var(--brand-primary) !important;
  border: none !important;
  border-radius: var(--r-xs) !important;
  width: 26px !important; height: 26px !important;
  display: inline-flex !important;
  align-items: center !important; justify-content: center !important;
  padding: 0 !important;
  box-shadow: 0 2px 6px rgba(79,70,229,.30) !important;
  transition: all var(--transition) !important;
}
#addRowBtn:hover:not(:disabled) { background: #4338ca !important; transform: scale(1.1) !important; }

#removeRowBtn {
  border-radius: var(--r-xs) !important;
  width: 26px !important; height: 26px !important;
  display: inline-flex !important;
  align-items: center !important; justify-content: center !important;
  padding: 0 !important;
  transition: all var(--transition) !important;
}

.saved-details-section .card.border-success {
  border: 1px solid var(--border-default) !important;
  border-radius: var(--r-xl) !important;
  box-shadow: var(--shadow-sm) !important;
  overflow: hidden;
}

.card-header.bg-success {
  background: #22c55e !important;
  border-radius: 0 !important;
  padding: 13px 20px !important;
}
.card-header.bg-success h6 {
  font-size: 13px !important;
  font-weight: 700 !important;
  letter-spacing: .04em !important;
  color: #f0fdfa !important;
}
.card-header.bg-success h6 i {
  color: var(--brand-teal-md) !important;
}

#scrollLeftBtn,
#scrollRightBtn {
  background: rgba(255,255,255,.12) !important;
  border: 1.5px solid rgba(255,255,255,.35) !important;
  color: #fff !important;
  backdrop-filter: blur(4px);
}
#scrollLeftBtn:hover, #scrollRightBtn:hover {
  background: rgba(255,255,255,.28) !important;
  border-color: rgba(255,255,255,.7) !important;
  transform: scale(1.1);
}
#scrollLeftBtn:active, #scrollRightBtn:active { transform: scale(.93); }

#recentSearchInput {
  font-size: 12.5px !important;
  background: rgba(255,255,255,.95) !important;
  border-color: rgba(255,255,255,.3) !important;
  color: var(--text-body) !important;
}
#recentSearchInput::placeholder { color: var(--text-faint) !important; }
.input-group-text.bg-white.border-success {
  background: rgba(255,255,255,.95) !important;
  border-color: rgba(255,255,255,.3) !important;
}
.btn-outline-success {
  color: rgba(255,255,255,.85) !important;
  border-color: rgba(255,255,255,.3) !important;
  border-radius: 0 var(--r-sm) var(--r-sm) 0 !important;
}
.btn-outline-success:hover {
  background: rgba(255,255,255,.15) !important;
  color: #fff !important;
}

#recentTable {
  border-collapse: separate !important;
  border-spacing: 0 !important;
  border: 1px solid var(--border-default) !important;
  border-radius: var(--r-md) !important;
  overflow: hidden;
  background: #fff;
}

#recentTable thead th {
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
#recentTable thead th:last-child { border-right: none !important; }

#recentTable thead .sticky-col {
  background: var(--brand-primary-lt) !important;
  color: var(--brand-primary) !important;
  z-index: 3;
}

#recentTable tbody td {
  padding: 0 14px !important;
  font-size: 13px !important;
  color: var(--text-body) !important;
  border-bottom: 1px solid var(--border-default) !important;
  border-right: 1px solid var(--border-default) !important;
  vertical-align: middle !important;
}
#recentTable tbody td:last-child { border-right: none !important; }
#recentTable tbody tr:last-child td { border-bottom: none !important; }

#recentTable tbody tr.recent-note-row {
  transition: background var(--transition);
}
#recentTable tbody tr.recent-note-row:hover td {
  background: #fafbff !important;
}
#recentTable tbody tr.recent-note-row:hover .sticky-col {
  background: var(--brand-primary-lt) !important;
}

#recentTable .sticky-col {
  position: sticky;
  left: 0;
  z-index: 2;
  background: #fff;
  min-width: 130px;
  max-width: 160px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  box-shadow: 3px 0 8px -2px rgba(15,23,42,.08);
  font-weight: 600 !important;
  color: var(--text-heading) !important;
}

#recentTable td .d-flex.flex-column {
  align-items: flex-start !important;
}
#recentTable td .d-flex.flex-column div {
  justify-content: flex-start !important;
  text-align: left !important;
}
#recentTable .small.text-muted.fw-bold {
  text-align: left !important;
  width: 100%;
  font-size: 12.5px !important;
  color: var(--text-body) !important;
  font-weight: 600 !important;
  padding-left: 0 !important;
  white-space: normal !important;
  line-height: 1.4 !important;
}
#recentTable td:nth-child(6),
#recentTable td:nth-child(6) .d-flex,
#recentTable td:nth-child(6) .d-flex div {
  text-align: left !important;
  align-items: flex-start !important;
  justify-content: flex-start !important;
}

#recentTable td .d-flex.align-items-center.justify-content-center {
  justify-content: center !important;
}

.badge {
  font-family: 'Inter', sans-serif !important;
  font-size: 10.5px !important;
  font-weight: 600 !important;
  padding: 3.5px 9px !important;
  border-radius: 20px !important;
  letter-spacing: .02em !important;
}

.badge.bg-primary   { background: var(--brand-primary) !important; color: #fff !important; }
.badge.bg-success   { background: var(--color-success) !important; color: #fff !important; }
.badge.bg-info      { background: var(--color-info) !important;    color: #fff !important; }
.badge.bg-warning   { background: var(--color-warning) !important; color: #fff !important; font-family: 'JetBrains Mono', monospace !important; }
.badge.bg-danger    { background: var(--color-danger) !important;  color: #fff !important; }
.badge.bg-secondary { background: #64748b !important;              color: #fff !important; }

.badge.bg-info    { background: #0369a1 !important; }
.badge.bg-secondary { background: #475569 !important; }

.stage-badge {
  font-size: 10px !important;
  padding: 3px 8px !important;
  gap: 4px !important;
  border-radius: 20px !important;
}

.product-mrp-display {
  background: #f0fdf4 !important;
  color: var(--color-success) !important;
  border: 1px solid #bbf7d0 !important;
  border-radius: 20px !important;
  padding: 2px 10px 2px 8px !important;
  font-size: 12px !important;
  font-weight: 600 !important;
  display: inline-flex !important;
  align-items: center !important;
  gap: 1px !important;
  border-bottom: 1px solid #bbf7d0 !important;
  cursor: pointer !important;
  transition: all var(--transition) !important;
}
.product-mrp-display:hover {
  background: #dcfce7 !important;
  border-color: #86efac !important;
}

.product-total-display {
  background: var(--brand-primary-lt) !important;
  color: var(--brand-primary) !important;
  border: 1px solid var(--brand-primary-md) !important;
  border-radius: 20px !important;
  padding: 2px 10px 2px 8px !important;
  font-size: 12px !important;
  font-weight: 600 !important;
  display: inline-flex !important;
  align-items: center !important;
}

.fw-bold.text-success {
  color: var(--color-success) !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-size: 13px !important;
  font-weight: 600 !important;
}

.mrp-edit-tooltip {
  border: 1.5px solid var(--brand-primary) !important;
  border-radius: var(--r-md) !important;
  box-shadow: var(--shadow-lg) !important;
}
.mrp-edit-tooltip > div:first-child {
  color: var(--brand-primary) !important;
}
.mrp-update-btn {
  background: var(--color-success) !important;
  border: none !important;
  border-radius: var(--r-sm) !important;
  font-weight: 600 !important;
}
.mrp-edit-tooltip > div:last-child {
  border-bottom-color: var(--brand-primary) !important;
}

.product-update-tooltip {
  border: 1.5px solid var(--brand-primary) !important;
  border-radius: var(--r-md) !important;
  box-shadow: var(--shadow-lg) !important;
}
.btn-update-row {
  background: var(--brand-primary) !important;
  border: none !important;
  border-radius: var(--r-sm) !important;
  font-weight: 600 !important;
}

.action-btn {
  width: 32px !important;
  height: 32px !important;
  border-radius: var(--r-sm) !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  transition: all var(--transition) !important;
  padding: 0 !important;
}
.action-btn i { font-size: 1.1rem !important; }
.action-btn:hover:not(:disabled):not(.disabled-status) {
  transform: translateY(-2px) !important;
  box-shadow: var(--shadow-md) !important;
}
.disabled-status {
  opacity: .35 !important;
  cursor: not-allowed !important;
  pointer-events: auto !important;
}

div[style*="width: 1px"][style*="background: #dee2e6"] {
  background: var(--border-default) !important;
}

.suggestions-box {
  border: 1.5px solid var(--brand-primary) !important;
  border-radius: var(--r-md) !important;
  box-shadow: var(--shadow-lg) !important;
}
.suggestion-item {
  font-size: 13px !important;
  border-bottom: 1px solid var(--border-default) !important;
  transition: background var(--transition) !important;
}
.suggestion-item:hover,
.suggestion-item.selected {
  background: var(--brand-primary) !important;
  color: #fff !important;
}
.suggestion-item:hover .supplier-info,
.suggestion-item.selected .supplier-info {
  color: #c7d2fe !important;
}
.supplier-info { color: var(--brand-primary) !important; font-weight: 600 !important; }
.suggestion-main strong { font-size: 14px !important; color: var(--text-heading) !important; }

.open-update-tooltip {
  border: 1.5px solid var(--border-default) !important;
  border-radius: var(--r-sm) !important;
  background: var(--bg-subtle) !important;
  cursor: pointer !important;
  transition: all var(--transition) !important;
  font-size: 12.5px !important;
}
.open-update-tooltip:hover {
  border-color: var(--brand-primary) !important;
  box-shadow: 0 0 0 3px rgba(79,70,229,.08) !important;
  background: #fff !important;
}

.sortable { transition: background var(--transition) !important; }
.sortable:hover {
  background: var(--brand-primary-lt) !important;
  color: var(--brand-primary) !important;
}
.sortable.active { color: var(--brand-primary) !important; }
.sortable.active .sort-icon i,
.sortable.active .sort-icon { color: var(--brand-primary) !important; }

.col-fold-btn {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 22px !important;
  height: 22px !important;
  min-width: 22px !important;
  border-radius: var(--r-xs) !important;
  background: rgba(79,70,229,.10) !important;
  border: 1px solid rgba(79,70,229,.20) !important;
  cursor: pointer !important;
  font-size: 11px !important;
  line-height: 1 !important;
  margin-left: 6px !important;
  vertical-align: middle !important;
  transition: all var(--transition) !important;
  color: var(--brand-primary) !important;
  flex-shrink: 0 !important;
  padding: 0 !important;
}
.col-fold-btn:hover  {
  background: var(--brand-primary) !important;
  border-color: var(--brand-primary) !important;
  color: #fff !important;
  transform: scale(1.15) !important;
}
.col-fold-btn:active { transform: scale(.92) !important; }

.col-fold-btn i.bx-left-arrow-alt {
  font-size: 16px !important;
  width: auto !important;
  display: block !important;
  font-weight: 900 !important;
}

.col-restore-btn {
  background: rgba(13,148,136,.12) !important;
  border-color: rgba(13,148,136,.28) !important;
  color: var(--brand-teal) !important;
}
.col-restore-btn:hover {
  background: var(--brand-teal) !important;
  border-color: var(--brand-teal) !important;
  color: #fff !important;
}

.th-inner {
  display: inline-flex !important;
  align-items: center !important;
  gap: 3px !important;
  flex-wrap: nowrap !important;
  white-space: nowrap !important;
}

#topScroll::-webkit-scrollbar       { height: 5px; }
#topScroll::-webkit-scrollbar-track { background: var(--bg-header); border-radius: 3px; }
#topScroll::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 3px; }
#topScroll::-webkit-scrollbar-thumb:hover { background: var(--brand-primary); }

.table-responsive::-webkit-scrollbar       { height: 6px; }
.table-responsive::-webkit-scrollbar-track { background: var(--bg-header); }
.table-responsive::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 4px; }
.table-responsive::-webkit-scrollbar-thumb:hover { background: var(--brand-primary); }

.alert-success {
  background: #f0fdf4 !important;
  border: 1px solid #86efac !important;
  border-radius: var(--r-md) !important;
  color: #14532d !important;
  font-weight: 500 !important;
}
.alert-danger {
  background: #fef2f2 !important;
  border: 1px solid #fca5a5 !important;
  border-radius: var(--r-md) !important;
  color: #7f1d1d !important;
  font-weight: 500 !important;
}
.alert-info {
  background: var(--brand-primary-lt) !important;
  border: 1px solid var(--brand-primary-md) !important;
  border-radius: var(--r-md) !important;
  color: #312e81 !important;
}
#noResultsMessage {
  background: var(--brand-primary-lt) !important;
  border: 1px solid var(--brand-primary-md) !important;
  border-radius: var(--r-md) !important;
  color: var(--brand-primary) !important;
  font-weight: 500 !important;
}

.modal-content {
  border: none !important;
  border-radius: var(--r-xl) !important;
  box-shadow: var(--shadow-lg) !important;
  overflow: hidden;
}
.modal-header.bg-success {
  background: #134e4a !important;
  padding: 14px 20px !important;
}
.modal-header.bg-danger {
  background: #7f1d1d !important;
  padding: 14px 20px !important;
}
.modal-title { font-size: 14px !important; font-weight: 700 !important; }
.modal-body  { font-size: 13.5px !important; padding: 20px 24px !important; color: var(--text-body) !important; }
.modal-footer {
  padding: 12px 20px !important;
  border-top: 1px solid var(--border-default) !important;
  background: var(--bg-subtle) !important;
}

.toast {
  border-radius: var(--r-md) !important;
  font-size: 13px !important;
  font-weight: 500 !important;
}
.toast.bg-success { background: #14532d !important; border: none !important; }
.toast.bg-danger  { background: #7f1d1d !important; border: none !important; }

.d-flex.justify-content-between.align-items-center.mt-3 {
  border-top: 1px solid var(--border-default) !important;
  padding-top: 12px !important;
}
.text-muted.small {
  font-size: 11.5px !important;
  color: var(--text-faint) !important;
}

.fade-in { animation: fadeIn .25s ease-out; }
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-6px); }
  to   { opacity: 1; transform: translateY(0); }
}

.highlight {
  background: #fefce8 !important;
  transition: background .3s ease;
}

.recent-note-row td .d-flex.flex-column div {
  margin-bottom: 0 !important;
}

div[style*="width: 1px"][style*="height: 20px"] {
  margin: 0 3px !important;
  opacity: .5;
}

.no-spinner::-webkit-outer-spin-button,
.no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.no-spinner { -moz-appearance: textfield; }

.product-order-qty::-webkit-outer-spin-button,
.product-order-qty::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.product-order-qty { -moz-appearance: textfield; }

.mrp-edit-input::-webkit-outer-spin-button,
.mrp-edit-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.mrp-edit-input { -moz-appearance: textfield; }

.card-header .card-title {
  font-size: 17px !important;
  font-weight: 700 !important;
  letter-spacing: -.02em !important;
}
.card-header.bg-success h6,
.card-header[class*="bg-"] h6 {
  font-size: 15px !important;
  font-weight: 700 !important;
}

.suggestions-box {
  position: fixed !important;
  z-index: 2147483647 !important;
}

.suggestion-item:hover *,
.suggestion-item.selected *,
.suggestion-item:hover .suggestion-main strong,
.suggestion-item.selected .suggestion-main strong,
.suggestion-item:hover .medicine-details,
.suggestion-item.selected .medicine-details,
.suggestion-item:hover .supplier-section,
.suggestion-item.selected .supplier-section,
.suggestion-item:hover .supplier-info,
.suggestion-item.selected .supplier-info {
  color: #ffffff !important;
}

#comments-tooltip-overlay {
  display: none;
  position: fixed;
  inset: 0;
  z-index: 99998;
  background: rgba(15,23,42,.35);
  backdrop-filter: blur(2px);
  animation: ctOverlayIn .18s ease;
}
@keyframes ctOverlayIn {
  from { opacity: 0; } to { opacity: 1; }
}

#comments-tooltip-box {
  position: fixed;
  z-index: 99999;
  background: #fff;
  border: 1.5px solid #4f46e5;
  border-radius: 12px;
  box-shadow: 0 16px 48px rgba(15,23,42,.22), 0 4px 14px rgba(15,23,42,.10);
  padding: 16px 18px 14px;
  width: 340px;
  font-family: 'Inter', system-ui, sans-serif;
  animation: ctBoxIn .2s cubic-bezier(.34,1.56,.64,1);
}
@keyframes ctBoxIn {
  from { opacity: 0; transform: translateY(-8px) scale(.97); }
  to   { opacity: 1; transform: translateY(0)   scale(1); }
}

#comments-tooltip-box .ct-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}
#comments-tooltip-box .ct-title {
  font-size: 12px;
  font-weight: 700;
  color: #4f46e5;
  text-transform: uppercase;
  letter-spacing: .07em;
  display: flex;
  align-items: center;
  gap: 6px;
}
#comments-tooltip-box .ct-close {
  width: 24px; height: 24px;
  border-radius: 50%;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  color: #64748b;
  font-size: 14px;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .15s;
  padding: 0; line-height: 1;
}
#comments-tooltip-box .ct-close:hover {
  background: #fee2e2; border-color: #fca5a5; color: #dc2626;
}

#comments-tooltip-box .ct-toolbar {
  display: flex;
  gap: 3px;
  margin-bottom: 8px;
  padding: 5px 6px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 7px;
}
#comments-tooltip-box .ct-toolbar button {
  width: 28px; height: 28px;
  border-radius: 5px;
  border: 1px solid transparent;
  background: transparent;
  color: #475569;
  font-size: 13px;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .13s;
  padding: 0;
  font-family: inherit;
}
#comments-tooltip-box .ct-toolbar button:hover {
  background: #fff;
  border-color: #c7d2fe;
  color: #4f46e5;
}
#comments-tooltip-box .ct-toolbar button.active {
  background: #eef2ff;
  border-color: #a5b4fc;
  color: #4f46e5;
}
#comments-tooltip-box .ct-toolbar .ct-sep {
  width: 1px; background: #e2e8f0; margin: 3px 2px; flex-shrink: 0;
}

#comments-tooltip-box .ct-editor {
  min-height: 88px;
  max-height: 180px;
  overflow-y: auto;
  border: 1.5px solid #e2e8f0;
  border-radius: 7px;
  padding: 8px 10px;
  font-size: 13px;
  font-family: 'Inter', system-ui, sans-serif;
  color: #334155;
  line-height: 1.6;
  outline: none;
  background: #fff;
  transition: border-color .15s, box-shadow .15s;
  word-break: break-word;
}
#comments-tooltip-box .ct-editor:focus {
  border-color: #4f46e5;
  box-shadow: 0 0 0 3px rgba(79,70,229,.10);
}
#comments-tooltip-box .ct-editor:empty::before {
  content: attr(data-placeholder);
  color: #94a3b8;
  pointer-events: none;
}

#comments-tooltip-box .ct-actions {
  display: flex;
  gap: 8px;
  justify-content: flex-end;
  margin-top: 10px;
}
#comments-tooltip-box .ct-ok {
  background: #4f46e5;
  color: #fff;
  border: none;
  border-radius: 7px;
  font-size: 13px;
  font-weight: 600;
  padding: 6px 20px;
  cursor: pointer;
  transition: all .15s;
  display: flex; align-items: center; gap: 5px;
}
#comments-tooltip-box .ct-ok:hover {
  background: #4338ca;
  box-shadow: 0 3px 10px rgba(79,70,229,.30);
  transform: translateY(-1px);
}
#comments-tooltip-box .ct-cancel {
  background: transparent;
  color: #64748b;
  border: 1px solid #e2e8f0;
  border-radius: 7px;
  font-size: 13px;
  font-weight: 500;
  padding: 6px 14px;
  cursor: pointer;
  transition: all .15s;
}
#comments-tooltip-box .ct-cancel:hover {
  background: #f8fafc;
  border-color: #cbd5e1;
  color: #334155;
}

#comments-tooltip-box::before {
  content: '';
  position: absolute;
  top: -9px;
  left: 28px;
  width: 0; height: 0;
  border-left: 9px solid transparent;
  border-right: 9px solid transparent;
  border-bottom: 9px solid #4f46e5;
}
#comments-tooltip-box::after {
  content: '';
  position: absolute;
  top: -7px;
  left: 29.5px;
  width: 0; height: 0;
  border-left: 7.5px solid transparent;
  border-right: 7.5px solid transparent;
  border-bottom: 7.5px solid #fff;
}

.custom-tooltip .tooltip-inner {
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: #fff;
    font-size: 13px;
    padding: 8px 14px;
    border-radius: 10px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.custom-tooltip.bs-tooltip-top .tooltip-arrow::before {
    border-top-color: #2563eb;
}

.custom-tooltip.bs-tooltip-bottom .tooltip-arrow::before {
    border-bottom-color: #2563eb;
}

/* Custom Blue style for disabled NS button */
.disabled-status[data-status-field="ns_status"] {
    background-color: #eef2ff !important;
    color: #4f46e5 !important;
    border-color: #c7d2fe !important;
    opacity: 1 !important; /* Makes it look like a badge instead of faded out */
}
/* NS Button Hover Effect */
.product-status-btn[data-status-field="ns_status"]:hover:not(:disabled):not(.disabled-status) {
    background-color: #4f46e5 !important; /* Deep Blue */
    color: #ffffff !important;           /* White Text */
    border-color: #4f46e5 !important;
}
.badge-delivery-home {
    background-color: #2563EB !important;
    color: #fff !important;
    border: 1px solid #c7d2fe !important;
    padding: 6px 16px !important;
    border-radius: 10px !important;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 10px;
}

.badge-delivery-medical {
    background-color: #16A34A !important;
    color: #fff !important;
    border: 1px solid #bbf7d0 !important;
    padding: 6px 16px !important;
    border-radius: 10px !important;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 10px;
}

/* Center Cus Qty badges specifically */
#recentTable td .d-flex.flex-column div.d-flex.justify-content-center {
    justify-content: center !important;
    align-items: center !important;
}

/* Prevent the grey background on Reset All button when clicked */
#btnResetAll:active, 
#btnResetAll:focus {
    background-color: var(--bg-header) !important;
    color: var(--text-heading) !important;
    border-color: var(--border-strong) !important;
    box-shadow: none !important;
}

#btnResetAll:hover {
    background-color: #e2e8f0 !important; /* Slight light grey on hover only */
    color: var(--text-heading) !important;
}

/* Professional Polish for Stage Badges */
.stage-badge {
    padding: 6px 14px !important; /* Increased padding */
    border-radius: 8px !important;  /* Your requested 8px radius */
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 10px !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.1);
    transition: all 0.2s ease;
}

/* Attractive Professional Colors */
.badge.bg-warning.stage-badge { 
    background: linear-gradient(135deg, #ff9800, #f57c00) !important; 
    color: #fff !important;
    border-color: #e67e22 !important;
}
.badge.bg-info.stage-badge { 
    background: linear-gradient(135deg, #0ea5e9, #0284c7) !important; 
    border-color: #0369a1 !important;
}
.badge.bg-primary.stage-badge { 
    background: linear-gradient(135deg, #6366f1, #4f46e5) !important; 
    border-color: #4338ca !important;
}
.badge.bg-success.stage-badge { 
    background: linear-gradient(135deg, #22c55e, #16a34a) !important; 
    border-color: #15803d !important;
}
.badge.bg-danger.stage-badge { 
    background: linear-gradient(135deg, #ef4444, #dc2626) !important; 
    border-color: #b91c1c !important;
}


/* Hover effect to make it feel premium */
.stage-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.12);
    filter: brightness(1.1);
}

/* Updated: Received Stage now uses a professional green gradient */
.badge.bg-info.stage-badge { 
    background: linear-gradient(135deg, #22c55e, #16a34a) !important; 
    border-color: #15803d !important;
    color: #fff !important;
}

/* Updated: Delivered Stage (formerly success) can use a deep teal or blue to stay distinct */
.badge.bg-success.stage-badge { 
    background: linear-gradient(135deg, #0d9488, #0f766e) !important; 
    border-color: #115e59 !important;
    color: #fff !important;
}
</style>

<script>
const BASE_URL = '{{ url('/') }}';

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) return meta.getAttribute('content');
    const inp = document.querySelector('input[name="_token"]');
    if (inp) return inp.value;
    return null;
}

function confirmDelete(productId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = BASE_URL + '/admin/commitment-notes-product/' + productId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

document.addEventListener('DOMContentLoaded', function () {

    // ── Changed: start with 3 rows, minimum 3 rows ──
    let currentRowCount = 3;
    const maxRows        = 10;
    const minRows        = 3;
    const addRowBtn      = document.getElementById('addRowBtn');
    const removeRowBtn   = document.getElementById('removeRowBtn');
    const mainTableBody  = document.getElementById('mainTableBody');
    const commitmentForm = document.getElementById('commitmentForm');
    const clearFormBtn   = document.getElementById('clearFormBtn');

    function updateHeaderButtons() {
        const rowCount = mainTableBody.querySelectorAll('.product-row').length;
        if (removeRowBtn) removeRowBtn.style.display = rowCount > minRows ? 'inline-flex' : 'none';
        if (addRowBtn)    addRowBtn.disabled = rowCount >= maxRows;
    }

    const dateInput = document.getElementById('delivery-date-input');
    if (dateInput) {
        dateInput.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, '');
            if (v.length > 6) v = v.substring(0, 6);
            if (v.length > 4)      this.value = v.substring(0,2)+'/'+v.substring(2,4)+'/'+v.substring(4,6);
            else if (v.length > 2) this.value = v.substring(0,2)+'/'+v.substring(2,4);
            else                   this.value = v;
        });
    }

    function calculateTotal(row) {
        const mrpInput      = row.querySelector('.product-mrp');
        const orderQtyInput = row.querySelector('.product-order-qty');
        const mrpDisplay    = row.querySelector('.product-mrp-display');
        const mrpValue      = row.querySelector('.mrp-value');
        const totalDisplay  = row.querySelector('.product-total-display');
        const totalValue    = row.querySelector('.total-value');
        const mrp = parseFloat(mrpInput?.value) || 0;
        const qty = parseInt(orderQtyInput?.value) || 0;
        if (mrp > 0 && qty > 0) {
            const total = mrp * qty;
            if (mrpValue)     mrpValue.textContent       = mrp.toFixed(2);
            if (totalValue)   totalValue.textContent     = total.toFixed(2);
            if (mrpDisplay)   mrpDisplay.style.display   = 'inline';
            if (totalDisplay) totalDisplay.style.display = 'inline';
        } else if (mrp > 0) {
            if (mrpValue)     mrpValue.textContent       = mrp.toFixed(2);
            if (mrpDisplay)   mrpDisplay.style.display   = 'inline';
            if (totalDisplay) totalDisplay.style.display = 'none';
        }
    }

    function setupMrpInlineEdit(row) {
        const mrpDisplay = row.querySelector('.product-mrp-display');
        const tooltip    = row.querySelector('.mrp-edit-tooltip');
        const input      = row.querySelector('.mrp-edit-input');
        const updateBtn  = row.querySelector('.mrp-update-btn');
        const msgDiv     = row.querySelector('.mrp-edit-msg');
        const mrpHidden  = row.querySelector('.product-mrp');
        const medicineId = row.querySelector('.medicine-id');
        if (!mrpDisplay || !tooltip || !input || !updateBtn || !msgDiv) return;

        mrpDisplay.addEventListener('click', function (e) {
            e.stopPropagation();
            document.querySelectorAll('.mrp-edit-tooltip').forEach(t => { if (t !== tooltip) t.style.display = 'none'; });
            input.value        = row.querySelector('.mrp-value')?.textContent || '';
            msgDiv.textContent = '';
            msgDiv.style.color = '';
            tooltip.style.display = 'block';
            input.focus(); input.select();
        });
        document.addEventListener('click', function (e) {
            if (!tooltip.contains(e.target) && e.target !== mrpDisplay) tooltip.style.display = 'none';
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') tooltip.style.display = 'none';
            if (e.key === 'Enter')  updateBtn.click();
        });
        updateBtn.addEventListener('click', function () {
            const newPrice = parseFloat(input.value);
            const medId    = medicineId?.value;
            if (!newPrice || newPrice <= 0) { msgDiv.style.color='red'; msgDiv.textContent='⚠ Enter a valid price'; return; }
            if (!medId)                     { msgDiv.style.color='red'; msgDiv.textContent='⚠ No medicine selected'; return; }
            updateBtn.disabled = true; updateBtn.textContent = '...';
            msgDiv.style.color = '#555'; msgDiv.textContent = 'Saving...';
            fetch(`${BASE_URL}/admin/medicines/${medId}/update-price`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
                body: JSON.stringify({ price: newPrice })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const mrpValueSpan = row.querySelector('.mrp-value');
                    if (mrpValueSpan) mrpValueSpan.textContent = data.new_price;
                    if (mrpHidden)    mrpHidden.value           = newPrice;
                    calculateTotal(row);
                    msgDiv.style.color = 'green'; msgDiv.textContent = '✓ Price updated!';
                    setTimeout(() => { tooltip.style.display = 'none'; }, 900);
                } else {
                    msgDiv.style.color = 'red'; msgDiv.textContent = '✗ ' + (data.message || 'Update failed');
                }
            })
            .catch(() => { msgDiv.style.color = 'red'; msgDiv.textContent = '✗ Network error.'; })
            .finally(() => { updateBtn.disabled = false; updateBtn.textContent = 'Update'; });
        });
    }

    function setupAutocomplete(input) {
        if (!input) return;
        const row               = input.closest('tr');
        const container         = input.closest('td');
        const suggestionsBox    = container.querySelector('.suggestions-box');
        const mrpInput          = row.querySelector('.product-mrp');
        const qtyInput          = row.querySelector('.product-qty');
        const medicineIdInput   = row.querySelector('.medicine-id');
        const supplierNameInput = row.querySelector('.supplier-name');
        const categoryInput     = row.querySelector('.product-category');
        const orderQtyInput     = row.querySelector('.product-order-qty');
        const mrpDisplay        = row.querySelector('.product-mrp-display');
        let searchTimeout;

        function positionSuggestionsBox() {
            const rect = input.getBoundingClientRect();
            const boxW = Math.max(500, rect.width * 1.5);
            let top  = rect.bottom + 5;
            let left = rect.left;
            if (left + boxW > window.innerWidth - 8) {
                left = window.innerWidth - boxW - 8;
            }
            if (left < 4) left = 4;
            const boxH = Math.min(450, window.innerHeight * 0.55);
            if (top + boxH > window.innerHeight - 8) {
                top = rect.top - boxH - 5;
            }
            suggestionsBox.style.position  = 'fixed';
            suggestionsBox.style.top       = top  + 'px';
            suggestionsBox.style.left      = left + 'px';
            suggestionsBox.style.width     = boxW + 'px';
            suggestionsBox.style.maxHeight = boxH + 'px';
        }

        if (orderQtyInput) orderQtyInput.addEventListener('input', () => calculateTotal(row));

        input.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            if (query.length < 2) { suggestionsBox.style.display = 'none'; return; }
            positionSuggestionsBox();
            suggestionsBox.innerHTML     = '<div style="padding:15px;text-align:center;">Searching...</div>';
            suggestionsBox.style.display = 'block';
            searchTimeout = setTimeout(() => {
                fetch(`${BASE_URL}/admin/medicines/search-with-details?query=${encodeURIComponent(query)}&starts_with=true`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.error) throw new Error(data.error);
                        if (data && data.length > 0) {
                            suggestionsBox.innerHTML = data.map(item => `
                                <div class="suggestion-item"
                                     data-id="${item.id}" data-mrp="${item.mrp}"
                                     data-stock="${item.stock_quantity}" data-supplier="${item.supplier_name}"
                                     data-category="${item.category}" data-name="${item.name.replace(/'/g,"\\'")}">
                                    <div class="suggestion-main">
                                        <strong>${escapeHtml(item.name)}</strong>
                                        <div class="medicine-details">
                                            <span>💰 ₹${parseFloat(item.mrp).toFixed(2)}</span>
                                            <span>📦 Stock: ${item.stock_quantity}</span>
                                            <span>🏷️ ${item.category}</span>
                                        </div>
                                    </div>
                                    <div class="supplier-section">
                                        <span class="supplier-info">Supplier:</span> ${escapeHtml(item.supplier_name)}
                                    </div>
                                </div>`).join('');
                            positionSuggestionsBox();
                        } else {
                            suggestionsBox.innerHTML = '<div style="padding:20px;text-align:center;">No medicines found</div>';
                        }
                    })
                    .catch(err => {
                        console.error('Search error:', err);
                        suggestionsBox.innerHTML = '<div style="padding:20px;color:red;text-align:center;">Error loading data</div>';
                    });
            }, 300);
        });

        suggestionsBox.addEventListener('click', function (e) {
            const item = e.target.closest('.suggestion-item');
            if (!item) return;
            input.value = item.dataset.name;
            if (medicineIdInput)   medicineIdInput.value   = item.dataset.id;
            if (mrpInput)          mrpInput.value           = item.dataset.mrp;
            if (qtyInput)          qtyInput.value           = item.dataset.stock;
            if (supplierNameInput) supplierNameInput.value  = item.dataset.supplier;
            if (categoryInput)     categoryInput.value      = item.dataset.category;
            if (orderQtyInput) { orderQtyInput.placeholder = `Max: ${item.dataset.stock}`; orderQtyInput.focus(); }
            if (mrpDisplay) {
                const mrpValue = row.querySelector('.mrp-value');
                if (mrpValue) mrpValue.textContent = parseFloat(item.dataset.mrp).toFixed(2);
                mrpDisplay.style.display = 'inline';
            }
            suggestionsBox.style.display = 'none';
            calculateTotal(row);
        });

        document.addEventListener('click', function (e) {
            if (!container.contains(e.target) && !suggestionsBox.contains(e.target)) suggestionsBox.style.display = 'none';
        });
    }

    document.querySelectorAll('.product-name').forEach(setupAutocomplete);
    document.querySelectorAll('.product-row').forEach(setupMrpInlineEdit);
    document.querySelectorAll('.product-order-qty').forEach(input => {
        input.addEventListener('input', () => calculateTotal(input.closest('tr')));
    });

    function addNewRow() {
        if (currentRowCount >= maxRows) { alert(`Maximum ${maxRows} products allowed`); return; }
        const newRow = document.createElement('tr');
        newRow.className = 'product-row';
        newRow.setAttribute('data-row', currentRowCount);
        newRow.innerHTML = `
            <td></td><td></td><td></td><td></td><td></td>
            <td class="position-relative product-cell">
                <input type="text" name="products[${currentRowCount}][product_name]"
                       class="form-control form-control-sm product-name"
                       placeholder="Search medicine (min 2 letters)..." autocomplete="off">
                <input type="hidden" name="products[${currentRowCount}][medicine_id]" class="medicine-id">
                <input type="hidden" name="products[${currentRowCount}][mrp]" class="product-mrp">
                <input type="hidden" name="products[${currentRowCount}][stock_qty]" class="product-qty">
                <input type="hidden" name="products[${currentRowCount}][supplier_name]" class="supplier-name">
                <input type="hidden" name="products[${currentRowCount}][category]" class="product-category">
                <div class="suggestions-box" style="display:none;"></div>
            </td>
            <td>
                <input type="text" name="products[${currentRowCount}][order_qty]"
                       class="form-control form-control-sm product-order-qty"
                       placeholder="Qty" inputmode="numeric">
            </td>
            <td class="text-center position-relative mrp-cell">
                <span class="product-mrp-display text-success fw-bold" style="display:none; cursor:pointer;" title="Click to edit MRP price">
                    ₹<span class="mrp-value"></span>
                </span>
                <div class="mrp-edit-tooltip"
                     style="display:none; position:absolute; z-index:9999; background:#fff; border:2px solid #28a745;
                            border-radius:8px; padding:10px 12px; box-shadow:0 4px 15px rgba(0,0,0,0.25);
                            min-width:190px; top:110%; left:50%; transform:translateX(-50%);">
                    <div style="font-size:12px; font-weight:700; color:#28a745; margin-bottom:6px;">✏️ Edit MRP Price</div>
                    <div class="d-flex gap-1 align-items-center">
                        <input type="number" class="mrp-edit-input form-control form-control-sm" step="0.01" min="0" placeholder="New price" style="width:105px;">
                        <button type="button" class="btn btn-success btn-sm mrp-update-btn" style="white-space:nowrap; font-size:12px;">Update</button>
                    </div>
                    <div class="mrp-edit-msg" style="font-size:11px; margin-top:5px; min-height:14px;"></div>
                    <div style="position:absolute; top:-9px; left:50%; transform:translateX(-50%);
                                width:0; height:0; border-left:9px solid transparent;
                                border-right:9px solid transparent; border-bottom:9px solid #28a745;"></div>
                </div>
            </td>
            <td class="text-center">
                <span class="product-total-display text-primary fw-bold" style="display:none;">
                    ₹<span class="total-value"></span>
                </span>
            </td>
            `;
        mainTableBody.appendChild(newRow);
        setupAutocomplete(newRow.querySelector('.product-name'));
        setupMrpInlineEdit(newRow);
        newRow.querySelector('.product-order-qty').addEventListener('input', () => calculateTotal(newRow));
        currentRowCount++;
        updateHeaderButtons();
        newRow.querySelector('.product-name')?.focus();
    }

    function removeLastRow() {
        const allRows = mainTableBody.querySelectorAll('.product-row');
        if (allRows.length <= minRows) return;
        allRows[allRows.length - 1].remove();
        currentRowCount--;
        updateHeaderButtons();
    }

    if (addRowBtn)    addRowBtn.addEventListener('click', addNewRow);
    if (removeRowBtn) removeRowBtn.addEventListener('click', removeLastRow);
    updateHeaderButtons();

    if (clearFormBtn) {
        clearFormBtn.addEventListener('click', function () {
            commitmentForm.reset();
            commitmentForm.querySelectorAll('input[type="hidden"]').forEach(h => { if (h.name !== '_token') h.value = ''; });
            commitmentForm.querySelectorAll('.product-mrp-display, .product-total-display').forEach(el => el.style.display = 'none');
            commitmentForm.querySelectorAll('.mrp-value, .total-value').forEach(el => { el.textContent = ''; });
            document.querySelectorAll('.mrp-edit-tooltip').forEach(t => t.style.display = 'none');
            const allRows = mainTableBody.querySelectorAll('.product-row');
            // ── Keep only the first minRows (3), remove extras ──
            allRows.forEach((row, index) => { if (index >= minRows) row.remove(); });
            currentRowCount = minRows;
            updateHeaderButtons();
            const homeRadio = document.getElementById('delivery-home');
            if (homeRadio) homeRadio.checked = true;
            const nameInput = document.getElementById('customer-name');
            if (nameInput) nameInput.focus();
            showToastMessage('success', 'Form cleared');
        });
    }

    // ── Sorting ──────────────────────────────────────────────────────────────
    let currentSort = { column: 'id', direction: 'desc' };
    let currentSearchTerm = '';

    function sortTable(column, direction) {
    const tbody = document.getElementById('recentCommitmentsBody');
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll('.recent-note-row'));
    rows.sort(function (a, b) {
        switch (column) {
            case 'sno':
    return direction === 'asc'
        ? (parseInt(a.dataset.sno) || 0) - (parseInt(b.dataset.sno) || 0)
        : (parseInt(b.dataset.sno) || 0) - (parseInt(a.dataset.sno) || 0);

case 'salesperson':
    return direction === 'asc'
        ? (a.dataset.salesperson || '').localeCompare(b.dataset.salesperson || '')
        : (b.dataset.salesperson || '').localeCompare(a.dataset.salesperson || '');

case 'advance':
    return direction === 'asc'
        ? (parseFloat(a.dataset.advance) || 0) - (parseFloat(b.dataset.advance) || 0)
        : (parseFloat(b.dataset.advance) || 0) - (parseFloat(a.dataset.advance) || 0);

case 'orderqty':
    return direction === 'asc'
        ? (parseInt(a.dataset.orderqty) || 0) - (parseInt(b.dataset.orderqty) || 0)
        : (parseInt(b.dataset.orderqty) || 0) - (parseInt(a.dataset.orderqty) || 0);

case 'supplier':
    return direction === 'asc'
        ? (a.dataset.supplier || '').localeCompare(b.dataset.supplier || '')
        : (b.dataset.supplier || '').localeCompare(a.dataset.supplier || '');

case 'remarks':
    return direction === 'asc'
        ? (a.dataset.remarks || '').localeCompare(b.dataset.remarks || '')
        : (b.dataset.remarks || '').localeCompare(a.dataset.remarks || '');
            case 'name':
                return direction === 'asc'
                    ? (a.dataset.name || '').localeCompare(b.dataset.name || '')
                    : (b.dataset.name || '').localeCompare(a.dataset.name || '');
            case 'phone':
                return direction === 'asc'
                    ? (a.dataset.phone || '').localeCompare(b.dataset.phone || '')
                    : (b.dataset.phone || '').localeCompare(a.dataset.phone || '');
            case 'date':
                return direction === 'asc'
                    ? (parseInt(a.dataset.date) || 0) - (parseInt(b.dataset.date) || 0)
                    : (parseInt(b.dataset.date) || 0) - (parseInt(a.dataset.date) || 0);
            case 'type':
                return direction === 'asc'
                    ? (a.dataset.type || '').localeCompare(b.dataset.type || '')
                    : (b.dataset.type || '').localeCompare(a.dataset.type || '');
            case 'comments':
                return direction === 'asc'
                    ? (a.dataset.comments || '').localeCompare(b.dataset.comments || '')
                    : (b.dataset.comments || '').localeCompare(a.dataset.comments || '');
            case 'products':
                return direction === 'asc'
                    ? (parseInt(a.dataset.productsCount) || 0) - (parseInt(b.dataset.productsCount) || 0)
                    : (parseInt(b.dataset.productsCount) || 0) - (parseInt(a.dataset.productsCount) || 0);
            case 'qty':
                return direction === 'asc'
                    ? (parseInt(a.dataset.qty) || 0) - (parseInt(b.dataset.qty) || 0)
                    : (parseInt(b.dataset.qty) || 0) - (parseInt(a.dataset.qty) || 0);
            case 'amount':
                return direction === 'asc'
                    ? (parseFloat(a.dataset.amount) || 0) - (parseFloat(b.dataset.amount) || 0)
                    : (parseFloat(b.dataset.amount) || 0) - (parseFloat(a.dataset.amount) || 0);
            case 'stage':
                return direction === 'asc'
                    ? (a.dataset.stageText || '').localeCompare(b.dataset.stageText || '')
                    : (b.dataset.stageText || '').localeCompare(a.dataset.stageText || '');
            case 'id':
                return direction === 'asc'
                    ? (parseInt(a.dataset.id) || 0) - (parseInt(b.dataset.id) || 0)
                    : (parseInt(b.dataset.id) || 0) - (parseInt(a.dataset.id) || 0);
            default:
                return 0;
        }
    });
    rows.forEach(row => tbody.appendChild(row));
    updateSortIndicators(column, direction);
    currentSort = { column, direction };
}

    function updateSortIndicators(activeColumn, activeDirection) {
        document.querySelectorAll('.sortable').forEach(header => {
            const column = header.dataset.sort;
            const icon   = header.querySelector('.sort-icon i');
            header.classList.remove('active', 'sort-asc', 'sort-desc');
            if (column === activeColumn) {
                header.classList.add('active');
                if (activeDirection === 'asc') { header.classList.add('sort-asc');  if (icon) icon.className = 'bx bx-sort-up'; }
                else                           { header.classList.add('sort-desc'); if (icon) icon.className = 'bx bx-sort-down'; }
            } else {
                if (icon) icon.className = 'bx bx-sort-alt-2';
            }
        });
    }

    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', function () {
            const column    = this.dataset.sort;
            const direction = (currentSort.column === column && currentSort.direction === 'asc') ? 'desc' : 'asc';
            sortTable(column, direction);
        });
    });

    // ── Search ───────────────────────────────────────────────────────────────
    const searchInput    = document.getElementById('recentSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchBtn');

    function filterAndSort() {
        currentSearchTerm = (searchInput?.value || '').toLowerCase().trim();
        if (clearSearchBtn) clearSearchBtn.style.display = currentSearchTerm.length > 0 ? 'block' : 'none';
        const rows = document.querySelectorAll('.recent-note-row');
        let visibleCount = 0;
        rows.forEach(row => {
            const matches = currentSearchTerm === '' ||
                            (row.dataset.name||'').includes(currentSearchTerm) ||
                            (row.dataset.phone||'').includes(currentSearchTerm) ||
                            (row.dataset.comments||'').includes(currentSearchTerm);
            row.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });
        const noResults = document.getElementById('noResultsMessage');
        if (noResults) noResults.style.display = (visibleCount === 0 && currentSearchTerm !== '') ? 'block' : 'none';
        sortTable(currentSort.column, currentSort.direction);
    }

    if (searchInput)    searchInput.addEventListener('input', filterAndSort);
    if (clearSearchBtn) clearSearchBtn.addEventListener('click', function () { searchInput.value = ''; filterAndSort(); searchInput.focus(); });

    // ── Product status buttons ────────────────────────────────────────────────
    document.querySelectorAll('.product-status-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (this.disabled || this.classList.contains('disabled-status')) return;

            const productId    = this.dataset.productId;
            const statusField  = this.dataset.statusField;
            const button       = this;
            const originalHtml = this.innerHTML;

            button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
            button.disabled  = true;

            fetch(`${BASE_URL}/admin/commitment-notes-product/${productId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ status_field: statusField })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToastMessage('success', data.message);

                    button.innerHTML = originalHtml;
                    button.className = 'btn action-btn product-status-btn p-1 btn-secondary disabled-status';
button.title     = 'Action Completed';

if ((statusField === 'received_status' || statusField === 'contacted_status') && data.updated_at) {
    const d = new Date(data.updated_at.replace(' ', 'T'));
    const timeStr = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    const dayStr  = d.toLocaleString('en-US', { weekday: 'long' });
    const dateStr = String(d.getDate()).padStart(2,'0') + '-'
                  + String(d.getMonth()+1).padStart(2,'0') + '-'
                  + d.getFullYear();
    const label   = statusField === 'received_status' ? 'Already Received' : 'Already Contacted';

    button.removeAttribute('title');
    button.setAttribute('data-bs-toggle', 'tooltip');
    button.setAttribute('data-bs-placement', 'top');
    button.setAttribute('data-bs-custom-class', 'custom-tooltip');
    button.setAttribute('data-bs-html', 'true');
    button.setAttribute('title', label + '<br>' + timeStr + '<br>' + dayStr + '<br>' + dateStr);
    new bootstrap.Tooltip(button);
}

                    // Find the correct stage badge using slot index
                    const actionDiv = button.closest('div[style*="height: 38px"]');
                    if (!actionDiv) return;

                    const parentFlex     = actionDiv.parentElement;
                    const allActionSlots = Array.from(parentFlex.children);
                    const slotIndex      = allActionSlots.indexOf(actionDiv);

                    const tr = button.closest('tr');
                    if (!tr) return;

                    const stageBadges = tr.querySelectorAll('.stage-badge');

                    if (stageBadges[slotIndex] && data.stage_info) {
    const badge = stageBadges[slotIndex];
    badge.className = `badge bg-${data.stage_info.bg} d-flex align-items-center gap-1 stage-badge`;
    badge.innerHTML = `<i class="bx ${data.stage_info.icon}"></i><span>${data.stage_info.text}</span>`;
}
// ── NEW: Remove the row if returned_status was set ──
                    // ── NEW: Remove the row if delivered or returned (no longer meets delivered_status=1 filter) ──
if (statusField === 'returned_status' || statusField === 'delivered_status' || statusField === 'ns_status') {
                        const tr = button.closest('tr');
                        const allFlexCols = tr.querySelectorAll('td > .d-flex.flex-column');

                        allFlexCols.forEach(col => {
                            const slots = col.children;
                            if (slots[slotIndex]) {
                                slots[slotIndex].style.transition = 'opacity 0.4s ease, max-height 0.4s ease';
                                slots[slotIndex].style.overflow = 'hidden';
                                slots[slotIndex].style.opacity = '0';
                                slots[slotIndex].style.maxHeight = '0';
                            }
                        });

                        setTimeout(() => {
                            allFlexCols.forEach(col => {
                                const slots = Array.from(col.children);
                                if (slots[slotIndex]) slots[slotIndex].remove();
                                if (col.children.length === 0) {
                                    col.closest('td').innerHTML = '<span class="text-muted small">-</span>';
                                }
                            });

                            const remainingSlots = tr.querySelectorAll('td > .d-flex.flex-column');
                            const hasSlots = Array.from(remainingSlots).some(col => col.children.length > 0);
                            if (!hasSlots) {
                                tr.style.transition = 'opacity 0.4s ease';
                                tr.style.opacity = '0';
                                setTimeout(() => tr.remove(), 400);
                            }
                        }, 420);
                    }
                } else {
                    button.innerHTML = originalHtml;
                    button.disabled  = false;
                    showToastMessage('error', data.message || 'Update failed');
                }
            })
            .catch(() => {
                button.innerHTML = originalHtml;
                button.disabled  = false;
                showToastMessage('error', 'Network error');
            });
        });
    });

    // ── Toast ────────────────────────────────────────────────────────────────
    window.showToastMessage = function(type, message) {
        const bg   = type === 'success' ? 'bg-success' : 'bg-danger';
        const html = `<div class="position-fixed bottom-0 end-0 p-3" style="z-index:1100">
            <div class="toast align-items-center text-white ${bg} border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${escapeHtml(message)}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div></div>`;
        document.body.insertAdjacentHTML('beforeend', html);
        setTimeout(() => { document.querySelector('.toast')?.parentElement.remove(); }, 3000);
    };

    setTimeout(() => sortTable('id', 'desc'), 100);

    // ── Dual scrollbar ───────────────────────────────────────────────────────
    const mainScroll = document.getElementById('mainScroll');
    const topScroll  = document.getElementById('topScroll');
    const topInner   = document.getElementById('topScrollInner');

    function syncTopScrollWidth() {
        if (topInner && mainScroll) topInner.style.width = mainScroll.scrollWidth + 'px';
    }

    if (mainScroll && topScroll && topInner) {
        syncTopScrollWidth();
        if (typeof ResizeObserver !== 'undefined') {
            new ResizeObserver(syncTopScrollWidth).observe(mainScroll);
        }
        let isSyncingFromTop  = false;
        let isSyncingFromMain = false;
        topScroll.addEventListener('scroll', function () {
            if (isSyncingFromMain) return;
            isSyncingFromTop = true;
            mainScroll.scrollLeft = topScroll.scrollLeft;
            setTimeout(() => { isSyncingFromTop = false; }, 50);
        });
        mainScroll.addEventListener('scroll', function () {
            if (isSyncingFromTop) return;
            isSyncingFromMain = true;
            topScroll.scrollLeft = mainScroll.scrollLeft;
            setTimeout(() => { isSyncingFromMain = false; }, 50);
        });
    }

    // ── Scroll shortcut buttons ──────────────────────────────────────────────
    const scrollLeftBtn  = document.getElementById('scrollLeftBtn');
    const scrollRightBtn = document.getElementById('scrollRightBtn');

    function scrollToEnd(direction) {
        if (!mainScroll) return;
        const target = direction === 'left' ? 0 : mainScroll.scrollWidth;
        mainScroll.scrollLeft = target;
        if (topScroll) topScroll.scrollLeft = target;
    }

    if (scrollLeftBtn)  scrollLeftBtn.addEventListener('click',  () => scrollToEnd('left'));
    if (scrollRightBtn) scrollRightBtn.addEventListener('click', () => scrollToEnd('right'));

    // ── Product update tooltip ───────────────────────────────────────────────
    document.querySelectorAll('.open-update-tooltip').forEach(input => {
        input.addEventListener('click', function (e) {
            e.stopPropagation();
            const row     = this.closest('tr');
            const tooltip = row.querySelector('.product-update-tooltip');
            document.querySelectorAll('.product-update-tooltip').forEach(t => t.style.display = 'none');
            tooltip.querySelector('.t-qty').value            = this.dataset.qty          || '0';
            tooltip.querySelector('.t-supplier').value       = this.dataset.supplierName || '';
            tooltip.querySelector('.t-supplier-id').value    = this.dataset.supplierId   || '';
            tooltip.querySelector('.t-remarks').value        = this.dataset.remarks       || '';
            tooltip.querySelector('.update-msg').textContent = '';
            tooltip.dataset.activeProductId                  = this.dataset.productId;
            const rect = this.getBoundingClientRect();
            tooltip.style.display = 'block';
            tooltip.style.top     = (rect.bottom + 5) + 'px';
            tooltip.style.left    = Math.max(5, rect.left - 150) + 'px';
            tooltip.querySelector('.t-qty').focus();
        });
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.product-update-tooltip')) {
            document.querySelectorAll('.product-update-tooltip').forEach(t => t.style.display = 'none');
        }
    });

    document.querySelectorAll('.btn-update-row').forEach(btn => {
        btn.addEventListener('click', function () {
            const tooltip   = this.closest('.product-update-tooltip');
            const productId = tooltip.dataset.activeProductId;
            const msg       = tooltip.querySelector('.update-msg');
            if (!productId) { msg.textContent = '✗ No product selected'; msg.style.color = 'red'; return; }
            msg.textContent = 'Updating...';
            msg.style.color = '#696cff';
            const payload = {
                qty:         tooltip.querySelector('.t-qty').value,
                supplier_id: tooltip.querySelector('.t-supplier-id').value,
                remarks:     tooltip.querySelector('.t-remarks').value,
            };
            fetch(`${BASE_URL}/admin/commitment-notes-product/${productId}/update-details`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken(), 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify(payload),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    msg.textContent = '✓ Updated Successfully';
                    msg.style.color = 'green';
                    setTimeout(() => location.reload(), 600);
                } else {
                    msg.textContent = '✗ ' + (data.message || 'Failed');
                    msg.style.color = 'red';
                }
            })
            .catch(() => { msg.textContent = '✗ Network Error'; msg.style.color = 'red'; });
        });
    });

    document.querySelectorAll('.t-supplier').forEach(input => {
        let timeout;
        input.addEventListener('input', function () {
            clearTimeout(timeout);
            const query = this.value;
            const box   = this.parentElement.querySelector('.supplier-suggestions');
            if (query.length < 2) { box.style.display = 'none'; return; }
            timeout = setTimeout(() => {
                fetch(`${BASE_URL}/admin/suppliers/search?query=${encodeURIComponent(query)}`)
                    .then(r => r.json())
                    .then(data => {
                        box.innerHTML = data.map(s => `
                            <div class="p-2 suggestion-item" data-id="${s.id}"
                                 style="cursor:pointer; border-bottom:1px solid #eee; background:#fff;">
                                ${escapeHtml(s.name)}
                            </div>`).join('');
                        box.style.display = 'block';
                        box.querySelectorAll('.suggestion-item').forEach(item => {
                            item.addEventListener('click', () => {
                                input.value = item.textContent.trim();
                                input.nextElementSibling.value = item.dataset.id;
                                box.style.display = 'none';
                            });
                        });
                    });
            }, 250);
        });
    });

    // ── Column folding ───────────────────────────────────────────────────────
    (function initColumnFolding() {
        const table = document.getElementById('recentTable');
        if (!table) return;

        const headerRow = table.querySelector('thead tr');
        if (!headerRow) return;

        const ths = Array.from(headerRow.querySelectorAll('th'));
        const hiddenCols = new Set();

        function setColVisible(colIndex, visible) {
            ths[colIndex].style.display = visible ? '' : 'none';
            table.querySelectorAll('tbody tr').forEach(function (tr) {
                const cells = tr.querySelectorAll('td');
                if (cells[colIndex]) cells[colIndex].style.display = visible ? '' : 'none';
            });
        }

        function applyAllVisibility() {
            ths.forEach(function (th, i) {
                setColVisible(i, !hiddenCols.has(i));
            });
            const restoreBtn = ths[0] ? ths[0].querySelector('.col-restore-btn') : null;
            if (restoreBtn) {
                restoreBtn.style.display = hiddenCols.size > 0 ? 'inline-flex' : 'none';
            }
            setTimeout(syncTopScrollWidth, 60);
        }

        ths.forEach(function (th, i) {
            const wrapper = document.createElement('span');
            wrapper.className = 'th-inner';
            wrapper.innerHTML = th.innerHTML;
            th.innerHTML = '';
            th.appendChild(wrapper);

            if (i === 0) {
                const restoreBtn = document.createElement('button');
                restoreBtn.type      = 'button';
                restoreBtn.className = 'col-fold-btn col-restore-btn';
                restoreBtn.title     = 'Restore all hidden columns';
                restoreBtn.innerHTML = '&#9654;';
                restoreBtn.style.display = 'none';
                restoreBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    hiddenCols.clear();
                    applyAllVisibility();
                });
                wrapper.appendChild(restoreBtn);
            } else if (i < ths.length - 1) {   // ← skip last column (Actions)
    const foldBtn = document.createElement('button');
    foldBtn.type      = 'button';
    foldBtn.className = 'col-fold-btn col-hide-btn';
    foldBtn.title     = 'Hide this column and all columns to its left';
    foldBtn.innerHTML = '<i class="bx bx-left-arrow-alt" style="display: inline-block; width: 60px;"></i>';
    foldBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        for (let j = 1; j <= i; j++) {
            hiddenCols.add(j);
        }
        applyAllVisibility();
    });
    wrapper.appendChild(foldBtn);
}
        });

        applyAllVisibility();
    })();

}); // end DOMContentLoaded
</script>

<script>
(function () {
  'use strict';

  function repositionOpenSuggestions () {
    document.querySelectorAll('.suggestions-box').forEach(function (box) {
      if (box.style.display === 'none') return;
      const td    = box.closest('td');
      if (!td) return;
      const input = td.querySelector('.product-name');
      if (!input) return;
      const rect  = input.getBoundingClientRect();
      const boxW  = Math.max(500, rect.width * 1.5);
      let top  = rect.bottom + 5;
      let left = rect.left;
      if (left + boxW > window.innerWidth - 8) left = window.innerWidth - boxW - 8;
      if (left < 4) left = 4;
      const boxH = Math.min(450, window.innerHeight * 0.55);
      if (top + boxH > window.innerHeight - 8) top = rect.top - boxH - 5;
      box.style.top       = top  + 'px';
      box.style.left      = left + 'px';
      box.style.width     = boxW + 'px';
      box.style.maxHeight = boxH + 'px';
    });
  }

  window.addEventListener('scroll', repositionOpenSuggestions, true);
  window.addEventListener('resize', repositionOpenSuggestions);

  // ── Comments Tooltip Mini Editor ─────────────────────────────────────────
  const overlay = document.createElement('div');
  overlay.id = 'comments-tooltip-overlay';
  document.body.appendChild(overlay);

  const box = document.createElement('div');
  box.id = 'comments-tooltip-box';
  box.innerHTML = `
    <div class="ct-header">
      <span class="ct-title">
        <i class="bx bx-comment-edit" style="font-size:16px;"></i>
        Comments
      </span>
      <button class="ct-close" id="ct-close-btn" title="Close">&times;</button>
    </div>
    <div class="ct-toolbar">
      <button data-cmd="bold"        title="Bold"><b>B</b></button>
      <button data-cmd="italic"      title="Italic"><i>I</i></button>
      <button data-cmd="underline"   title="Underline"><u>U</u></button>
      <div class="ct-sep"></div>
      <button data-cmd="insertUnorderedList" title="Bullet list"><i class="bx bx-list-ul" style="font-size:15px;"></i></button>
      <button data-cmd="insertOrderedList"   title="Numbered list"><i class="bx bx-list-ol" style="font-size:15px;"></i></button>
      <div class="ct-sep"></div>
      <button data-cmd="removeFormat" title="Clear formatting"><i class="bx bx-eraser" style="font-size:15px;"></i></button>
    </div>
    <div class="ct-editor" id="ct-editor-area" contenteditable="true"
         data-placeholder="Type your instructions here..."></div>
    <div class="ct-actions">
      <button class="ct-cancel" id="ct-cancel-btn">Cancel</button>
      <button class="ct-ok" id="ct-ok-btn">
        <i class="bx bx-check" style="font-size:15px;"></i> OK
      </button>
    </div>`;
  document.body.appendChild(box);

  const editorArea = document.getElementById('ct-editor-area');
  let   activeTextarea = null;

  box.querySelectorAll('.ct-toolbar button[data-cmd]').forEach(function (btn) {
    btn.addEventListener('mousedown', function (e) {
      e.preventDefault();
      const cmd = this.dataset.cmd;
      document.execCommand(cmd, false, null);
      updateToolbarState();
      editorArea.focus();
    });
  });

  function updateToolbarState () {
    ['bold','italic','underline'].forEach(function (cmd) {
      const btn = box.querySelector('[data-cmd="' + cmd + '"]');
      if (btn) btn.classList.toggle('active', document.queryCommandState(cmd));
    });
  }
  editorArea.addEventListener('keyup',   updateToolbarState);
  editorArea.addEventListener('mouseup', updateToolbarState);

  // ── FIX #2: Enter in comments editor → apply & advance (not new line) ──
  editorArea.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      e.stopPropagation();
      document.getElementById('ct-ok-btn').click();
    }
    // Shift+Enter inserts a line break inside the editor
  });

  function showTooltip (textarea) {
    activeTextarea = textarea;
    const rect = textarea.getBoundingClientRect();
    const currentVal = textarea.value.trim();
    if (currentVal) {
      editorArea.innerHTML = currentVal.replace(/\n/g, '<br>');
    } else {
      editorArea.innerHTML = '';
    }
    const tooltipH = 260;
    let   top = rect.bottom + 8;
    if (top + tooltipH > window.innerHeight) {
      top = rect.top - tooltipH - 8;
    }
    let left = rect.left;
    if (left + 340 > window.innerWidth) {
      left = window.innerWidth - 348;
    }
    box.style.top     = top  + 'px';
    box.style.left    = left + 'px';
    box.style.display = 'block';
    overlay.style.display = 'block';
    setTimeout(function () { editorArea.focus(); }, 80);
    updateToolbarState();
  }

  function hideTooltip () {
    box.style.display     = 'none';
    overlay.style.display = 'none';
    activeTextarea        = null;
  }

  function applyValue () {
    if (!activeTextarea) return;
    const html  = editorArea.innerHTML;
    const plain = editorArea.innerText;
    activeTextarea.value = plain;

    let display = activeTextarea.nextElementSibling;
    if (!display || !display.classList.contains('comments-display')) {
      display = document.createElement('div');
      display.className = 'comments-display';
      display.style.cssText = [
        'margin-top:4px',
        'font-size:12px',
        'line-height:1.55',
        'color:#334155',
        'font-family:Inter,system-ui,sans-serif',
        'background:#f8fafc',
        'border:1.5px solid #4f46e5',
        'border-radius:6px',
        'padding:6px 10px',
        'min-height:34px',
        'word-break:break-word'
      ].join(';');
      activeTextarea.parentNode.insertBefore(display, activeTextarea.nextSibling);
    }
    display.innerHTML = html;
    activeTextarea.style.display = 'none';
    display.style.display        = 'block';
    display.onclick = function () {
      activeTextarea.style.display = 'none';
      showTooltip(activeTextarea);
    };
    hideTooltip();
  }

  document.getElementById('ct-ok-btn').addEventListener('click',     applyValue);
  document.getElementById('ct-cancel-btn').addEventListener('click', hideTooltip);
  document.getElementById('ct-close-btn').addEventListener('click',  hideTooltip);

  overlay.addEventListener('click', hideTooltip);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && box.style.display !== 'none') hideTooltip();
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter' && document.activeElement === editorArea) {
      e.preventDefault(); applyValue();
    }
  });

  function attachCommentsListener (textarea) {
    if (!textarea || textarea.dataset.ctAttached) return;
    textarea.dataset.ctAttached = '1';
    textarea.readOnly = true;
    textarea.style.cursor = 'pointer';
    textarea.style.backgroundColor = '#f8fafc';
    textarea.setAttribute('placeholder', 'Click to add instructions...');
    textarea.addEventListener('click', function (e) {
      e.stopPropagation();
      showTooltip(textarea);
    });
    textarea.addEventListener('focus', function () { textarea.blur(); showTooltip(textarea); });
  }

  document.querySelectorAll('textarea[name="commands"]').forEach(attachCommentsListener);

  const obs = new MutationObserver(function (mutations) {
    mutations.forEach(function (m) {
      m.addedNodes.forEach(function (node) {
        if (node.nodeType !== 1) return;
        node.querySelectorAll && node.querySelectorAll('textarea[name="commands"]').forEach(attachCommentsListener);
      });
    });
  });
  const tbody = document.getElementById('mainTableBody');
  if (tbody) obs.observe(tbody, { childList: true, subtree: true });

  const clearBtn = document.getElementById('clearFormBtn');
  if (clearBtn) {
    clearBtn.addEventListener('click', function () {
      document.querySelectorAll('.comments-display').forEach(function (d) {
        d.style.display = 'none';
        d.innerHTML = '';
      });
      document.querySelectorAll('textarea[name="commands"]').forEach(function (ta) {
        ta.value = '';
        ta.style.display = '';
      });
    }, true);
  }

})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const BASE = '{{ url('/') }}';

    /* ── helpers ── */
    function spinner(color) {
        return '<div class="text-center py-5">'
             + '<div class="spinner-border" style="color:' + color + ';"></div>'
             + '<p class="mt-3 text-muted">Loading…</p>'
             + '</div>';
    }

    function getBootstrapModal(id) {
        return bootstrap.Modal.getOrCreateInstance(document.getElementById(id));
    }

    /* ══════════════════════════════════════════════
       EDIT MODAL
    ══════════════════════════════════════════════ */
    const editBody    = document.getElementById('editProductModalBody');
    const editSaveBtn = document.getElementById('editModalSaveBtn');

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.open-edit-modal');
        if (!btn) return;

        const productId = btn.dataset.productId;
        editBody.innerHTML = spinner('#5f6bfa');

        const title = document.querySelector('#editProductModal .modal-title');
        if (title) title.innerHTML = '<i class="bx bx-edit-alt me-2"></i>Edit Product #' + productId;

        getBootstrapModal('editProductModal').show();

        fetch(BASE + '/admin/commitment-notes-product/' + productId + '/edit-product', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.text(); })
        .then(function (html) {
            var parser = new DOMParser();
            var doc    = parser.parseFromString(html, 'text/html');

            var styles = '';
            doc.querySelectorAll('style').forEach(function (s) { styles += s.outerHTML; });

            var page = doc.querySelector('.en-page');
            if (page) {
    var actionBar = page.querySelector('.en-action-bar');
    if (actionBar) actionBar.remove();
    var bc = page.querySelector('.en-breadcrumb');
    if (bc) bc.remove();

    editBody.innerHTML = styles
        + '<div style="padding:20px;">' + page.outerHTML + '</div>';
} else {
    editBody.innerHTML = styles + '<div style="padding:20px;">' + html + '</div>';
}

editBody.querySelectorAll('script').forEach(function (old) {
    var s = document.createElement('script');
    s.textContent = old.textContent;
    document.body.appendChild(s);
});

setTimeout(function () {
    if (typeof window.initEditPage === 'function') {
        window.initEditPage(editBody);
    }
}, 100);
        })
        .catch(function () {
            editBody.innerHTML = '<div class="alert alert-danger m-4">Failed to load edit form. Please try again.</div>';
        });
    });

    editSaveBtn.addEventListener('click', function () {
        var form = editBody.querySelector('form');
        if (!form) {
            alert('Form not loaded yet. Please wait.');
            return;
        }

        editSaveBtn.disabled = true;
        editSaveBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Saving…';

        var data = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: data,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) {
            if (r.ok || r.redirected) {
                getBootstrapModal('editProductModal').hide();
                if (typeof showToastMessage === 'function') {
                    showToastMessage('success', 'Product updated successfully!');
                }
                setTimeout(function () { location.reload(); }, 900);
            } else {
                return r.text().then(function (t) {
                    var parser = new DOMParser();
                    var doc    = parser.parseFromString(t, 'text/html');
                    var errors = doc.querySelector('.en-alert-error, .alert-danger');
                    if (errors) {
                        var existing = editBody.querySelector('.modal-error-banner');
                        if (existing) existing.remove();
                        var banner = document.createElement('div');
                        banner.className = 'modal-error-banner';
                        banner.style.cssText = 'margin:12px 20px 0;';
                        banner.innerHTML = errors.outerHTML;
                        editBody.prepend(banner);
                    } else {
                        if (typeof showToastMessage === 'function') {
                            showToastMessage('error', 'Save failed. Please check your inputs.');
                        }
                    }
                });
            }
        })
        .catch(function () {
            if (typeof showToastMessage === 'function') {
                showToastMessage('error', 'Network error. Please try again.');
            }
        })
        .finally(function () {
            editSaveBtn.disabled = false;
            editSaveBtn.innerHTML = '<i class="bx bx-save me-1"></i>Save Changes';
        });
    });

    /* ══════════════════════════════════════════════
       SHOW (VIEW) MODAL
    ══════════════════════════════════════════════ */
    var showBody = document.getElementById('showProductModalBody');

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.open-show-modal');
        if (!btn) return;

        var productId = btn.dataset.productId;
        showBody.innerHTML = spinner('#38bdf8');

        var title = document.querySelector('#showProductModal .modal-title');
        if (title) title.innerHTML = '<i class="bx bx-file-find me-2"></i>View Product #' + productId;

        getBootstrapModal('showProductModal').show();

        fetch(BASE + '/admin/commitment-notes-product/' + productId + '/show-product', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.text(); })
        .then(function (html) {
            var parser = new DOMParser();
            var doc    = parser.parseFromString(html, 'text/html');

            var styles = '';
            doc.querySelectorAll('style').forEach(function (s) { styles += s.outerHTML; });

            var page = doc.querySelector('.vn-page');
            if (page) {
                var ab = page.querySelector('.vn-action-bar');
                if (ab) ab.remove();
                var bc = page.querySelector('.vn-breadcrumb');
                if (bc) bc.remove();

                showBody.innerHTML = styles
                    + '<div style="padding:20px;">' + page.outerHTML + '</div>';
            } else {
                showBody.innerHTML = styles + '<div style="padding:20px;">' + html + '</div>';
            }
        })
        .catch(function () {
            showBody.innerHTML = '<div class="alert alert-danger m-4">Failed to load product details. Please try again.</div>';
        });
    });

});
</script>

<script>
(function () {
  'use strict';

  /* ─── Wait for DOM ─────────────────────────────────────────────────── */
  document.addEventListener('DOMContentLoaded', function () {

    /* ─── Helpers ──────────────────────────────────────────────────────── */
    function $(sel, ctx) { return (ctx || document).querySelector(sel); }
    function $$(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }

    function focusField(el) {
      if (!el) return;
      el.focus();
      if (el.select) el.select();
    }

    /* ─── Field order builder ──────────────────────────────────────────── */
    /*
      Sequence:
        0  customer-name
        1  customer-phone
        2  delivery-date
        3  delivery-type-home    (isRadio)
        4  delivery-type-medical (isRadio)
        5  comments              (isComments)
        6+ medicine-0, qty-0, medicine-1, qty-1 …
        N  sales-person
        N+1 advance
    */
    function getFieldSequence() {
      const seq = [];

      const cusName = document.getElementById('customer-name');
      if (cusName) seq.push({ el: cusName, label: 'customer-name' });

      const phone = document.getElementById('customer-phone');
      if (phone) seq.push({ el: phone, label: 'customer-phone' });

      const delDate = document.getElementById('delivery-date-input');
      if (delDate) seq.push({ el: delDate, label: 'delivery-date' });

      // Both radios in the sequence so focused radio is always found
      const homeRadio    = document.getElementById('delivery-home');
      const medicalRadio = document.getElementById('delivery-medical');
      if (homeRadio)    seq.push({ el: homeRadio,    label: 'delivery-type-home',    isRadio: true });
      if (medicalRadio) seq.push({ el: medicalRadio, label: 'delivery-type-medical', isRadio: true });

      const comments = $('textarea[name="commands"]');
      if (comments) seq.push({ el: comments, label: 'comments', isComments: true });

      const rows = $$('.product-row', document.getElementById('mainTableBody'));
      rows.forEach(function (row, i) {
        const medInput = row.querySelector('.product-name');
        const qtyInput = row.querySelector('.product-order-qty');
        if (medInput) seq.push({ el: medInput, label: 'medicine-' + i, isMedicine: true, rowIndex: i });
        if (qtyInput) seq.push({ el: qtyInput, label: 'qty-' + i, isQty: true, rowIndex: i });
      });

      const salesPerson = $('input[name="sales_person_name"]');
      if (salesPerson) seq.push({ el: salesPerson, label: 'sales-person' });

      const advance = document.getElementById('advance_amount');
      if (advance) seq.push({ el: advance, label: 'advance' });

      return seq;
    }

    /* ─── Find current position in sequence ───────────────────────────── */
    function findCurrentIndex(seq) {
      const active = document.activeElement;
      for (let i = 0; i < seq.length; i++) {
        if (seq[i].el === active) return i;
      }
      return -1;
    }

    /* ─── Move forward ─────────────────────────────────────────────────── */
    function moveNext(seq, currentIndex) {
      const current = seq[currentIndex];
      let nextIndex = currentIndex + 1;

      // FIX #1 — When leaving delivery-date (index 2), land on the first radio (index 3)
      // i.e. do NOT skip radios when coming from delivery-date.
      // Only skip radios when the CURRENT field is already a radio.
      if (current && current.isRadio) {
        // Skip all remaining consecutive radios → jump to comments
        while (nextIndex < seq.length && seq[nextIndex].isRadio) {
          nextIndex++;
        }
      }

      const next = seq[nextIndex];
      if (next) {
        if (next.isComments) {
          openCommentsTooltip();
        } else if (next.isRadio) {
          // Focus the currently-checked radio in the group, or the first one
          const checkedRadio = document.querySelector('.delivery-radio:checked') || next.el;
          focusField(checkedRadio);
          showRadioHint();
        } else {
          focusField(next.el);
        }
      }
      // End of sequence — do nothing. Save is triggered by Shift key only.
    }

    /* ─── Move backward ────────────────────────────────────────────────── */
    function movePrev(seq, currentIndex) {
      if (currentIndex <= 0) return;
      let prevIndex = currentIndex - 1;

      // If going back from comments (isComments at currentIndex),
      // land on the last radio (delivery-type-medical) so user can change it
      // OR skip all radios and land on delivery-date when already at first radio
      const current = seq[currentIndex];
      if (current && current.isRadio) {
        // Skip backwards past all consecutive radios
        while (prevIndex > 0 && seq[prevIndex].isRadio) {
          prevIndex--;
        }
      }

      const prev = seq[prevIndex];
      if (!prev) return;

      if (prev.isComments) {
        openCommentsTooltip();
      } else if (prev.isRadio) {
        const checkedRadio = document.querySelector('.delivery-radio:checked') || prev.el;
        focusField(checkedRadio);
        showRadioHint();
      } else {
        focusField(prev.el);
      }
    }

    /* ─── Open the Comments tooltip (rich editor) ─────────────────────── */
    function openCommentsTooltip() {
      const ta = $('textarea[name="commands"]');
      if (!ta) return;
      ta.dispatchEvent(new MouseEvent('click', { bubbles: true }));
      waitForCommentsOk();
    }

    function waitForCommentsOk() {
      const okBtn = document.getElementById('ct-ok-btn');
      if (!okBtn) return;
      function onOk() {
        okBtn.removeEventListener('click', onOk);
        const seq = getFieldSequence();
        const commentsIndex = seq.findIndex(function (f) { return f.isComments; });
        if (commentsIndex >= 0) moveNext(seq, commentsIndex);
      }
      okBtn.removeEventListener('click', onOk);
      okBtn.addEventListener('click', onOk);
    }

    /* ─── Close comments popup and go back to delivery-type radio ──────── */
    function closeCommentsAndGoBack() {
      const ctBox = document.getElementById('comments-tooltip-box');
      if (ctBox && ctBox.style.display !== 'none') {
        // Trigger the cancel button to close without saving
        const cancelBtn = document.getElementById('ct-cancel-btn');
        if (cancelBtn) cancelBtn.click();
        // Focus the checked delivery-type radio
        setTimeout(function () {
          const checkedRadio = document.querySelector('.delivery-radio:checked')
                             || document.getElementById('delivery-home');
          if (checkedRadio) focusField(checkedRadio);
          showRadioHint();
        }, 60);
        return true; // handled
      }
      return false;
    }

    /* ─── Radio hint flash ────────────────────────────────────────────── */
    function showRadioHint() {
      const hint = document.getElementById('kbnav-radio-hint');
      if (hint) {
        hint.style.opacity = '1';
        setTimeout(function () { hint.style.opacity = '0'; }, 2000);
      }
    }

    /* ─── Inject a small radio hint label ────────────────────────────── */
    function injectRadioHint() {
      const homeRadio = document.getElementById('delivery-home');
      if (!homeRadio) return;
      const cell = homeRadio.closest('td');
      if (!cell || document.getElementById('kbnav-radio-hint')) return;
      const hint = document.createElement('div');
      hint.id = 'kbnav-radio-hint';
      hint.textContent = '↑↓ to choose, Enter to confirm';
      hint.style.cssText = [
        'font-size:10px',
        'color:#4f46e5',
        'opacity:0',
        'transition:opacity 0.4s',
        'margin-top:4px',
        'font-weight:600'
      ].join(';');
      cell.appendChild(hint);
    }

    /* ─── Radio keyboard handling ─────────────────────────────────────── */
    function setupRadioNavigation() {
      const radios = $$('.delivery-radio');
      radios.forEach(function (radio) {
        radio.removeEventListener('keydown', radioKeyHandler);
        radio.addEventListener('keydown', radioKeyHandler);
      });
    }

    function radioKeyHandler(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        e.stopPropagation();
        const seq = getFieldSequence();
        const idx = findCurrentIndex(seq);
        if (idx >= 0) moveNext(seq, idx);
      }
      // FIX #2: Insert key on radio → go back to delivery-date
      if (e.key === 'Insert') {
        e.preventDefault();
        e.stopPropagation();
        const delDate = document.getElementById('delivery-date-input');
        if (delDate) focusField(delDate);
      }
    }

    /* ─── Global keydown handler ──────────────────────────────────────── */
    document.addEventListener('keydown', function (e) {
      const active           = document.activeElement;
      const tagName          = active ? active.tagName.toLowerCase() : '';
      const isInput          = (tagName === 'input' || tagName === 'textarea' || tagName === 'select');
      const isCommentsEditor = (active && active.id === 'ct-editor-area');
      const ctBoxOpen        = document.getElementById('comments-tooltip-box')?.style.display !== 'none';

      // ── FIX #2: Insert key → go to previous field (replaces Backspace) ──
      if (e.key === 'Insert') {
        e.preventDefault();
        e.stopPropagation();
        // If comments popup is open → close it and go back to delivery-type
        if (ctBoxOpen) {
          closeCommentsAndGoBack();
          return;
        }
        // If inside comments editor, also close and go back
        if (isCommentsEditor) {
          closeCommentsAndGoBack();
          return;
        }
        // Normal prev-field navigation
        const seq = getFieldSequence();
        const idx = findCurrentIndex(seq);
        if (idx > 0) movePrev(seq, idx);
        return;
      }

      // ── + and - keys → Add/Remove row from ANYWHERE (not inside comments editor) ──
      if (!isCommentsEditor) {
        if (e.key === '+' || (e.key === '=' && e.shiftKey)) {
          e.preventDefault();
          const addBtn = document.getElementById('addRowBtn');
          if (addBtn && !addBtn.disabled) addBtn.click();
          return;
        }
        if (e.key === '-') {
          e.preventDefault();
          const removeBtn = document.getElementById('removeRowBtn');
          if (removeBtn && removeBtn.style.display !== 'none') removeBtn.click();
          return;
        }
      }

      // ── FIX #3: Ctrl alone → Clear form ──
      if (e.key === 'Control' && !e.shiftKey && !e.altKey && !e.metaKey) {
        e.preventDefault();
        const clearBtn = document.getElementById('clearFormBtn');
        if (clearBtn) clearBtn.click();
        return;
      }

      // ── FIX #4: Alt alone → Cancel ──
      if (e.key === 'Alt' && !e.ctrlKey && !e.shiftKey && !e.metaKey) {
        e.preventDefault();
        const cancelBtn = document.getElementById('cancelBtn');
        if (cancelBtn) cancelBtn.click();
        return;
      }

      // ── FIX #5: Shift alone → Save (submit form) ──
      if (e.key === 'Shift' && !e.ctrlKey && !e.altKey && !e.metaKey) {
        e.preventDefault();
        const form = document.getElementById('commitmentForm');
        if (form) {
          const saveBtn = form.querySelector('button[type="submit"]');
          if (saveBtn) saveBtn.click();
        }
        return;
      }

      // ── Skip remaining handlers if inside comments rich editor ──
      if (isCommentsEditor) return;

      if (!active) return;

      // ── Enter key → advance ──
      if (e.key === 'Enter') {
        if (active.type === 'submit') return;
        if (active.closest('.suggestions-box')) return;
        const openSuggestion = $('.suggestions-box[style*="block"]');
        if (openSuggestion) return;

        // Radio enter is handled by radioKeyHandler — skip here
        if (active.classList && active.classList.contains('delivery-radio')) return;

        // FIX: advance_amount is the LAST field — Enter stops here, does nothing
        if (active.id === 'advance_amount') {
          e.preventDefault();
          return; // just stay — use Shift to save
        }

        e.preventDefault();

        const seq = getFieldSequence();
        const idx = findCurrentIndex(seq);
        if (idx >= 0) moveNext(seq, idx);
      }

    }, true); // capture phase

    /* ─── Enter on medicine autocomplete: pick highlighted suggestion ───── */
    document.addEventListener('keydown', function (e) {
      if (e.key !== 'Enter') return;
      const active = document.activeElement;
      if (!active || !active.classList.contains('product-name')) return;

      const td = active.closest('td');
      if (!td) return;
      const box = td.querySelector('.suggestions-box');
      if (!box || box.style.display === 'none') return;

      const highlighted = box.querySelector('.suggestion-item.selected') ||
                          box.querySelector('.suggestion-item');
      if (highlighted) {
        e.preventDefault();
        e.stopPropagation();
        highlighted.click();
        const row = active.closest('tr');
        if (row) {
          const qty = row.querySelector('.product-order-qty');
          if (qty) setTimeout(function () { focusField(qty); }, 50);
        }
      }
    }, true);

    /* ─── ArrowDown/Up on medicine input: navigate suggestions ─────────── */
    document.addEventListener('keydown', function (e) {
      if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp') return;
      const active = document.activeElement;
      if (!active || !active.classList.contains('product-name')) return;
      const td = active.closest('td');
      if (!td) return;
      const box = td.querySelector('.suggestions-box');
      if (!box || box.style.display === 'none') return;

      e.preventDefault();
      const items = Array.from(box.querySelectorAll('.suggestion-item'));
      if (!items.length) return;
      const current = box.querySelector('.suggestion-item.selected');
      let nextIdx = 0;
      if (current) {
        const ci = items.indexOf(current);
        nextIdx = e.key === 'ArrowDown'
          ? Math.min(ci + 1, items.length - 1)
          : Math.max(ci - 1, 0);
        current.classList.remove('selected');
      }
      items[nextIdx].classList.add('selected');
      items[nextIdx].scrollIntoView({ block: 'nearest' });
    }, true);

    /* ─── Escape on medicine input: close suggestion box ───────────────── */
    document.addEventListener('keydown', function (e) {
      if (e.key !== 'Escape') return;
      const active = document.activeElement;
      if (!active || !active.classList.contains('product-name')) return;
      const td = active.closest('td');
      if (!td) return;
      const box = td.querySelector('.suggestions-box');
      if (box) box.style.display = 'none';
    }, true);

    /* ─── Visual focus ring enhancement ────────────────────────────────── */
    function injectFocusStyle() {
      if (document.getElementById('kbnav-style')) return;
      const style = document.createElement('style');
      style.id = 'kbnav-style';
      style.textContent = `
        #commitmentForm input:focus,
        #commitmentForm textarea:focus,
        #commitmentForm select:focus {
          outline: 2px solid #4f46e5 !important;
          outline-offset: 2px !important;
          box-shadow: 0 0 0 4px rgba(79,70,229,0.15) !important;
          border-color: #4f46e5 !important;
        }
        .delivery-radio:focus {
          outline: 2px solid #4f46e5 !important;
          outline-offset: 3px !important;
        }
      `;
      document.head.appendChild(style);
    }

    /* ─── Keyboard shortcut hint bar (inline in card header) ─────────── */
    function injectHintBar() {
      const container = document.getElementById('kbnav-hint-bar-inline');
      if (!container || container.dataset.built) return;
      container.dataset.built = '1';
      container.innerHTML = [
        '<span><kbd>Enter</kbd>Next</span>',
        '<span><kbd>Ins</kbd>Prev</span>',
        '<span><kbd>↑↓</kbd>Suggest</span>',
        '<span><kbd>Shift</kbd>Save</span>',
        '<span><kbd>Ctrl</kbd>Clear</span>',
        '<span><kbd>Alt</kbd>Cancel</span>',
        '<span><kbd>+</kbd>Add row</span>',
        '<span><kbd>−</kbd>Remove row</span>',
      ].join('');
      container.style.cssText = [
        'display:flex',
        'align-items:center',
        'gap:10px',
        'flex-wrap:wrap',
        'font-size:11px',
        'font-family:Inter,system-ui,sans-serif',
        'color:#64748b',
        'letter-spacing:0.01em',
      ].join(';');
      // Inject kbd styles once
      if (!document.getElementById('kbnav-kbd-style')) {
        const s = document.createElement('style');
        s.id = 'kbnav-kbd-style';
        s.textContent = `
          #kbnav-hint-bar-inline span { display:inline-flex; align-items:center; gap:3px; white-space:nowrap; }
          #kbnav-hint-bar-inline kbd {
            background:#eef2ff; border:1px solid #c7d2fe; border-radius:4px;
            padding:1px 6px; font-size:10.5px; font-family:'JetBrains Mono',monospace;
            color:#4f46e5; font-weight:600; line-height:1.6;
          }
        `;
        document.head.appendChild(s);
      }
    }

    /* ─── Boot ─────────────────────────────────────────────────────────── */
    injectFocusStyle();
    injectHintBar();
    injectRadioHint();
    setupRadioNavigation();

    const firstField = document.getElementById('customer-name');
    if (firstField) setTimeout(function () { focusField(firstField); }, 200);

    const tbody = document.getElementById('mainTableBody');
    if (tbody && typeof MutationObserver !== 'undefined') {
      new MutationObserver(function () {
        setupRadioNavigation();
      }).observe(tbody, { childList: true, subtree: false });
    }

  }); // end DOMContentLoaded

})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');

    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('commitmentForm');
    if (!form) return;

    // --- Error display helpers ---
    function showError(input, message) {
        input.style.borderColor = '#f87171';
        input.style.backgroundColor = '#fff5f5';

        let errEl = input.parentElement.querySelector('.field-error-msg');
        if (!errEl) {
            errEl = document.createElement('div');
            errEl.className = 'field-error-msg';
            errEl.style.cssText = 'color:#dc2626;font-size:11px;margin-top:3px;font-weight:600;';
            input.parentElement.appendChild(errEl);
        }
        errEl.textContent = message;
    }

    function clearError(input) {
        input.style.borderColor = '';
        input.style.backgroundColor = '';
        const errEl = input.parentElement.querySelector('.field-error-msg');
        if (errEl) errEl.remove();
    }

    // Clear error on input
    function attachClearOnInput(input) {
        input.addEventListener('input', function () { clearError(this); });
        input.addEventListener('change', function () { clearError(this); });
    }

    // --- Validate on submit ---
    form.addEventListener('submit', function (e) {
        let hasError = false;

        // 1. Customer Name
        const cusName = document.getElementById('customer-name');
        if (cusName) {
            clearError(cusName);
            if (!cusName.value.trim()) {
                showError(cusName, '⚠ Customer Name is required');
                hasError = true;
            }
            attachClearOnInput(cusName);
        }

        // 2. Phone
        const phone = document.getElementById('customer-phone');
        if (phone) {
            clearError(phone);
            const phoneVal = phone.value.trim();
            if (!phoneVal) {
                showError(phone, '⚠ Phone Number is required');
                hasError = true;
            } else if (!/^[0-9]{10}$/.test(phoneVal)) {
                showError(phone, '⚠ Phone Number must be exactly 10 digits');
                hasError = true;
            }
            attachClearOnInput(phone);
        }

        // 3. Medicine Name + Qty — at least ONE row must be filled
        const rows = document.querySelectorAll('#mainTableBody .product-row');
        let anyRowFilled = false;
        let medicineErrors = [];
        let qtyErrors = [];

        rows.forEach(function (row, i) {
            const medInput = row.querySelector('.product-name');
            const qtyInput = row.querySelector('.product-order-qty');
            const medVal   = medInput ? medInput.value.trim() : '';
            const qtyVal   = qtyInput ? qtyInput.value.trim() : '';

            // Clear previous errors
            if (medInput) clearError(medInput);
            if (qtyInput) clearError(qtyInput);

            if (medVal || qtyVal) {
                // Row is partially/fully filled — validate both fields
                if (!medVal && medInput) {
                    showError(medInput, '⚠ Medicine Name is required');
                    medicineErrors.push(i);
                    hasError = true;
                    attachClearOnInput(medInput);
                }
                if (!qtyVal && qtyInput) {
                    showError(qtyInput, '⚠ Qty is required');
                    qtyErrors.push(i);
                    hasError = true;
                    attachClearOnInput(qtyInput);
                }
                if (medVal && qtyVal) {
                    anyRowFilled = true;
                }
            }
        });

        
        // If no row has both medicine + qty filled — show error on first row only
        if (!anyRowFilled) {
            const firstRow = rows[0];
            if (firstRow) {
                const medInput = firstRow.querySelector('.product-name');
                const qtyInput = firstRow.querySelector('.product-order-qty');
                if (medInput && !medInput.value.trim()) {
                    showError(medInput, '⚠ Fill at least one medicine');
                    attachClearOnInput(medInput);
                }
                if (qtyInput && !qtyInput.value.trim()) {
                    showError(qtyInput, '⚠ Fill at least one qty');
                    attachClearOnInput(qtyInput);
                }
            }
            hasError = true;
        }

        // 4. Sales Person Name
        const salesPerson = form.querySelector('input[name="sales_person_name"]');
        if (salesPerson) {
            clearError(salesPerson);
            if (!salesPerson.value.trim()) {
                showError(salesPerson, '⚠ Sales Person Name is required');
                hasError = true;
                attachClearOnInput(salesPerson);
            }
        }

        if (hasError) {
            e.preventDefault();
            // Scroll to first error
            const firstErr = form.querySelector('.field-error-msg');
            if (firstErr) {
                firstErr.closest('td, div')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

});
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn   = document.getElementById('globalMoreToggle');
    const moreLabel   = document.getElementById('globalMoreLabel');
    const moreIcon    = document.getElementById('globalMoreIcon');
    const lessIcon    = document.getElementById('globalLessIcon');

    if (!toggleBtn) return;

    let expanded = false;

    toggleBtn.addEventListener('click', function () {
        expanded = !expanded;

        // Show/hide all extra-action-btns in the table
        document.querySelectorAll('#recentTable .extra-action-btns').forEach(function (div) {
            div.style.display = expanded ? 'flex' : 'none';
        });

        // Update toggle label and icons
        moreLabel.textContent      = expanded ? 'Less' : 'More';
        moreIcon.style.display     = expanded ? 'none' : 'inline-block';
        lessIcon.style.display     = expanded ? 'inline-block' : 'none';
    });
});
  </script>

  <script>
(function () {
    'use strict';

    // Column indices in #recentTable (0-based):
    // 0:CustomerName  1:SNo  2:Phone  3:SalesPerson  4:AdvanceAmount
    // 5:DeliveryDate  6:DeliveryType  7:Comments  8:Products
    // 9:CusQty  10:OrderQty  11:Supplier  12:Remarks  13:CurrentStage  14:Actions

    var ALL_COLS   = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];

    // Commitment Order: customer name(0), phone(2), sales person(3),
    //   advance amount(4), comments(7), products(8), total qty(9), order qty(10), supplier(11)
    var COMMITMENT = [0, 2, 3, 4, 7, 8, 9, 10, 11];

    // Follow Up: customer name(0), phone(2), products(8), actions(14)
   var FOLLOWUP   = [0, 2, 8, 14, 12];

    var currentView = 'all'; // 'all' | 'commitment' | 'followup'

    function applyColumns(showSet) {
        var table = document.getElementById('recentTable');
        if (!table) return;

        var ths  = Array.from(table.querySelectorAll('thead tr th'));
        var rows = Array.from(table.querySelectorAll('tbody tr'));

        ths.forEach(function (th, i) {
            th.style.display = showSet.has(i) ? '' : 'none';
        });

        rows.forEach(function (tr) {
            Array.from(tr.querySelectorAll('td')).forEach(function (td, i) {
                td.style.display = showSet.has(i) ? '' : 'none';
            });
        });

        // Re-sync top scrollbar width
        setTimeout(function () {
            var topInner  = document.getElementById('topScrollInner');
            var mainScroll = document.getElementById('mainScroll');
            if (topInner && mainScroll) {
                topInner.style.width = mainScroll.scrollWidth + 'px';
            }
        }, 60);
    }

    function setActiveBtn(activeId) {
        ['btnCommitmentOrder', 'btnFollowUp'].forEach(function (id) {
            var btn = document.getElementById(id);
            if (!btn) return;
            if (id === activeId) {
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-primary');
            } else {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            }
        });
    }

    window.switchTableView = function (view) {
        // If clicking the already-active view, toggle back to full table
        if (currentView === view) {
            currentView = 'all';
            applyColumns(new Set(ALL_COLS));
            // Remove active state from both buttons
            ['btnCommitmentOrder', 'btnFollowUp'].forEach(function (id) {
                var btn = document.getElementById(id);
                if (btn) {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                }
            });
            return;
        }

        currentView = view;

        if (view === 'commitment') {
            applyColumns(new Set(COMMITMENT));
            setActiveBtn('btnCommitmentOrder');
        } else if (view === 'followup') {
            applyColumns(new Set(FOLLOWUP));
            setActiveBtn('btnFollowUp');
        }
    };

    window.resetTableView = function () {
        currentView = 'all';
        applyColumns(new Set(ALL_COLS));
        // Remove active state from both view buttons
        ['btnCommitmentOrder', 'btnFollowUp'].forEach(function (id) {
            var btn = document.getElementById(id);
            if (btn) {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            }
        });
    };

})();
</script>
@endsection
