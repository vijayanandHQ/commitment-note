@extends('layouts.sneat')

@section('title', 'Product Master - Imported Data')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-white">Product master last 2 year check - Imported Data</h4>
                <span class="badge bg-light text-dark">Compatibility Mode</span>
            </div>
            
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Import Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="bx bx-import"></i> Import New Data
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.medicines.import') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                    @csrf
                                    
                                    <div class="col-md-6">
                                        <label for="import_file" class="form-label">Select Excel File <span class="text-danger">*</span></label>
                                        <input type="file" 
                                               class="form-control @error('import_file') is-invalid @enderror" 
                                               id="import_file" 
                                               name="import_file" 
                                               accept=".xlsx,.xls,.csv"
                                               required>
                                        @error('import_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Supported formats: .xlsx, .xls, .csv</div>
                                    </div>

                                    <div class="col-md-6 d-flex align-items-end">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="skip_duplicates" name="skip_duplicates" checked>
                                            <label class="form-check-label" for="skip_duplicates">
                                                Skip duplicate products
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-upload"></i> Import
                                        </button>
                                        <a href="{{ route('admin.medicines.import.template') }}" class="btn btn-outline-primary">
                                            <i class="bx bx-download"></i> Download Template
                                        </a>
                                        <a href="{{ route('admin.medicines.create') }}" class="btn btn-success">
                                            <i class="bx bx-plus"></i> Add New
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter - Live Search Version -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="d-flex">
                            <input type="text" 
                                   id="search-input" 
                                   class="form-control me-2" 
                                   placeholder="Search by name, code, supplier, category..." 
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            <a href="{{ route('admin.medicines.import.form') }}" class="btn btn-secondary">Clear</a>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="bx bx-search"></i> Type to search automatically
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="category" id="category-select" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories ?? [] as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Excel-like Data Table with Sortable Headers -->
                <div class="card border">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="bx bx-table"></i> Sheet1
                            </h5>
                        </div>
                        <div>
                            <span class="badge bg-info me-2">Total: {{ $medicines->total() }} records</span>
                            <a href="{{ route('admin.medicines.import.form') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bx bx-refresh"></i> Refresh
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($medicines->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover mb-0" style="font-size: 0.85rem;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th rowspan="2" class="align-middle">
                                                <a href="javascript:void(0);" onclick="sortTable('product_code')" class="text-white text-decoration-none">
                                                    ProductCode 
                                                    @if($sortField == 'product_code')
                                                        <i class="bx bx-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th rowspan="2" class="align-middle">
                                                <a href="javascript:void(0);" onclick="sortTable('name')" class="text-white text-decoration-none">
                                                    ProductName
                                                    @if($sortField == 'name')
                                                        <i class="bx bx-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th rowspan="2" class="align-middle">
                                                <a href="javascript:void(0);" onclick="sortTable('category')" class="text-white text-decoration-none">
                                                    CATEGOREY
                                                    @if($sortField == 'category')
                                                        <i class="bx bx-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th rowspan="2" class="align-middle">
                                                <a href="javascript:void(0);" onclick="sortTable('supplier_name')" class="text-white text-decoration-none">
                                                    Supplier Name
                                                    @if($sortField == 'supplier_name')
                                                        <i class="bx bx-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th colspan="2" class="text-center">Units</th>
                                            <th rowspan="2" class="align-middle">
                                                <a href="javascript:void(0);" onclick="sortTable('supplier_code')" class="text-white text-decoration-none">
                                                    Supplier Code
                                                    @if($sortField == 'supplier_code')
                                                        <i class="bx bx-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th rowspan="2" class="align-middle">
                                                <a href="javascript:void(0);" onclick="sortTable('price')" class="text-white text-decoration-none">
                                                    MRP
                                                    @if($sortField == 'price')
                                                        <i class="bx bx-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th rowspan="2" class="align-middle">
                                                <a href="javascript:void(0);" onclick="sortTable('purchase_price')" class="text-white text-decoration-none">
                                                    Purchase Price
                                                    @if($sortField == 'purchase_price')
                                                        <i class="bx bx-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th rowspan="2" class="align-middle">Alt Supplier Code</th>
                                            <th rowspan="2" class="align-middle">GENERIC Name</th>
                                            <th rowspan="2" class="align-middle">Actions</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">
                                                <a href="javascript:void(0);" onclick="sortTable('purchase_unit')" class="text-white text-decoration-none">
                                                    Pur
                                                    @if($sortField == 'purchase_unit')
                                                        <i class="bx bx-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th class="text-center">
                                                <a href="javascript:void(0);" onclick="sortTable('sale_unit')" class="text-white text-decoration-none">
                                                    Sale
                                                    @if($sortField == 'sale_unit')
                                                        <i class="bx bx-sort-{{ $sortDirection == 'asc' ? 'up' : 'down' }}"></i>
                                                    @endif
                                                </a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($medicines as $medicine)
                                        <tr>
                                            <td><strong>{{ $medicine->product_code ?? 'N/A' }}</strong></td>
                                            <td>{{ $medicine->name }}</td>
                                            <td>{{ $medicine->category ?? 'General' }}</td>
                                            <td>{{ $medicine->supplier_name ?? $medicine->manufacturer ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $medicine->purchase_unit ?? '1' }}</td>
                                            <td class="text-center">{{ $medicine->sale_unit ?? '1' }}</td>
                                            <td>{{ $medicine->supplier_code ?? 'N/A' }}</td>
                                            <td class="text-end">₹{{ number_format($medicine->price, 2) }}</td>
                                            <td class="text-end">₹{{ number_format($medicine->purchase_price ?? 0, 2) }}</td>
                                            
                                            <!-- Alt Supplier Code with Tooltip -->
                                            <td>
                                                @if($medicine->alt_supplier_codes)
                                                    <div class="tooltip-container">
                                                        <span class="truncated-text">{{ Str::limit($medicine->alt_supplier_codes, 20) }}</span>
                                                        <div class="tooltip-content">
                                                            <strong>Alt Supplier Code:</strong><br>
                                                            {{ $medicine->alt_supplier_codes }}
                                                        </div>
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            
                                            <!-- GENERIC Name with Tooltip -->
                                            <td>
                                                @if($medicine->generic_name)
                                                    <div class="tooltip-container">
                                                        <span class="truncated-text">{{ Str::limit($medicine->generic_name, 25) }}</span>
                                                        <div class="tooltip-content">
                                                            <strong>Generic Name:</strong><br>
                                                            {{ $medicine->generic_name }}
                                                        </div>
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.medicines.show', $medicine->id) }}" class="btn btn-info" title="View">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="{{ route('admin.medicines.edit', $medicine->id) }}" class="btn btn-warning" title="Edit">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.medicines.destroy', $medicine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" title="Delete">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination & Status Bar -->
                            <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Showing {{ $medicines->firstItem() }} to {{ $medicines->lastItem() }} of {{ $medicines->total() }} entries
                                </div>
                                <div>
                                    {{ $medicines->appends(request()->query())->links() }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076478.png" alt="No data" style="width: 100px; opacity: 0.5;">
                                <h5 class="mt-3">No Data Available</h5>
                                <p class="text-muted">Import an Excel file to view data in this table</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sheet Tabs -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="btn-group" role="group">
                        <button class="btn btn-primary btn-sm" disabled>
                            <i class="bx bx-spreadsheet"></i> Sheet1
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" disabled>
                            <i class="bx bx-spreadsheet"></i> Sheet2
                        </button>
                    </div>
                    <div class="text-muted">
                        <small>Ready</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for showing full text (Alternative to tooltips) -->
<div class="modal fade" id="textModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="modalTitle">Full Text</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 bg-light rounded">
                    <p id="modalText" style="word-wrap: break-word; white-space: pre-wrap; font-size: 1rem;"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let searchTimeout;
    const searchInput = document.getElementById('search-input');
    const categorySelect = document.getElementById('category-select');
    
    function performSearch() {
        const search = searchInput.value;
        const category = categorySelect.value;
        
        let url = '{{ route("admin.medicines.import.form") }}?';
        
        if (search) {
            url += 'search=' + encodeURIComponent(search) + '&';
        }
        
        if (category) {
            url += 'category=' + encodeURIComponent(category) + '&';
        }
        
        // Preserve sort if exists
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('sort')) {
            url += 'sort=' + urlParams.get('sort') + '&';
        }
        if (urlParams.has('direction')) {
            url += 'direction=' + urlParams.get('direction') + '&';
        }
        
        // Remove trailing & or ?
        url = url.replace(/[&?]$/, '');
        
        if (url === '{{ route("admin.medicines.import.form") }}?') {
            url = '{{ route("admin.medicines.import.form") }}';
        }
        
        window.location.href = url;
    }
    
    function sortTable(field) {
        const urlParams = new URLSearchParams(window.location.search);
        const currentSort = urlParams.get('sort');
        const currentDirection = urlParams.get('direction');
        
        let direction = 'asc';
        if (currentSort === field && currentDirection === 'asc') {
            direction = 'desc';
        }
        
        let url = '{{ route("admin.medicines.import.form") }}?';
        url += 'sort=' + field + '&direction=' + direction + '&';
        
        if (urlParams.has('search')) {
            url += 'search=' + urlParams.get('search') + '&';
        }
        if (urlParams.has('category')) {
            url += 'category=' + urlParams.get('category') + '&';
        }
        
        url = url.replace(/[&?]$/, '');
        window.location.href = url;
    }
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500);
    });
    
    categorySelect.addEventListener('change', function() {
        performSearch();
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            performSearch();
        }
    });
</script>
@endpush

<style>
    .table th {
        background-color: #2d2d2d !important;
        color: white !important;
        font-weight: 500;
        white-space: nowrap;
    }
    .table th a {
        color: white !important;
        text-decoration: none;
        cursor: pointer;
    }
    .table th a:hover {
        text-decoration: underline !important;
        color: #ffd700 !important;
    }
    .table-dark {
        background-color: #2d2d2d !important;
        color: white !important;
    }
    .table td {
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,0.05);
    }
    .btn-group-sm .btn {
        padding: 0.2rem 0.4rem;
    }
    .card-header.bg-light {
        border-bottom: 1px solid #dee2e6;
    }
    /* Fix for white text visibility */
    .bg-primary, .bg-info, .bg-secondary, .bg-success, .bg-warning, .bg-danger {
        color: white !important;
    }
    
    /* Tooltip Styles */
    .tooltip-container {
        position: relative;
        display: inline-block;
        cursor: help;
        max-width: 150px;
    }
    
    .truncated-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        width: 100%;
        color: #2c3e50;
        border-bottom: 1px dashed #007bff;
        padding-bottom: 2px;
    }
    
    .tooltip-content {
        visibility: hidden;
        width: 300px;
        background-color: #fd8080;
        color: #fff;
        text-align: left;
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        line-height: 1.5;
        
        /* Position */
        position: absolute;
        z-index: 1000;
        bottom: 150%;
        left: 50%;
        transform: translateX(-50%);
        
        /* Fade in */
        opacity: 0;
        transition: opacity 0.3s, visibility 0.3s;
        white-space: normal;
        word-wrap: break-word;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        border: 1px solid #444;
    }
    
    .tooltip-content::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }
    
    .tooltip-container:hover .tooltip-content {
        visibility: visible;
        opacity: 1;
    }
    
    /* For long text, allow scrolling if needed */
    .tooltip-content {
        max-height: 200px;
        overflow-y: auto;
    }
    
    /* Make columns wider */
    .table th:nth-child(10),
    .table th:nth-child(11) {
        min-width: 150px;
    }
    
    /* Responsive tooltip */
    @media (max-width: 768px) {
        .tooltip-content {
            width: 250px;
            left: 0;
            transform: none;
        }
    }
</style>