@extends('layouts.sneat')

@section('title', 'Add Medicine')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Add New Medicine</h5>
            <div class="card-body">
                <form action="{{ route('admin.medicines.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        {{-- Row 1 --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Medicine Name *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Product Code</label>
                                <input type="text" name="product_code" class="form-control @error('product_code') is-invalid @enderror"
                                    value="{{ old('product_code') }}" placeholder="Auto-generated if left blank">
                                @error('product_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Row 2 --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Generic Name</label>
                                <input type="text" name="generic_name" class="form-control"
                                    value="{{ old('generic_name') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Generic Name (Original)</label>
                                <input type="text" name="generic_name_original" class="form-control"
                                    value="{{ old('generic_name_original') }}">
                            </div>
                        </div>

                        {{-- Row 3 --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Manufacturer</label>
                                <input type="text" name="manufacturer" class="form-control"
                                    value="{{ old('manufacturer') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach(['Tablet','Capsule','Syrup','Injection','Cream','Drops','Inhaler','Surgical','Other'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 4 — Supplier --}}
<div class="col-md-6">
    <div class="mb-3">
        <label class="form-label">Supplier Name</label>
        <input type="text" name="supplier_name" class="form-control"
            value="{{ old('supplier_name') }}" placeholder="Enter supplier name">
    </div>
</div>

<div class="col-md-6">
    <div class="mb-3">
        <label class="form-label">Supplier Code</label>
        <input type="text" name="supplier_code" class="form-control"
            value="{{ old('supplier_code') }}" placeholder="Enter supplier code">
    </div>
</div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Supplier Code</label>
                                <input type="text" name="supplier_code" id="supplier_code" class="form-control"
                                    value="{{ old('supplier_code') }}" placeholder="Auto-filled from supplier">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Supplier Name</label>
                                <input type="text" name="supplier_name" id="supplier_name" class="form-control"
                                    value="{{ old('supplier_name') }}" placeholder="Auto-filled from supplier">
                            </div>
                        </div>

                        {{-- Row 5 — Alt supplier codes --}}
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Alternative Supplier Codes
                                    <small class="text-muted">(comma-separated)</small>
                                </label>
                                <input type="text" name="alt_supplier_codes" class="form-control"
                                    value="{{ old('alt_supplier_codes') }}" placeholder="e.g. 79,17,80,55">
                            </div>
                        </div>

                        {{-- Row 6 — Pricing --}}
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">MRP / Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price') }}" required>
                                </div>
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Purchase Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="purchase_price" class="form-control"
                                        value="{{ old('purchase_price') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Stock Quantity *</label>
                                <input type="number" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror"
                                    value="{{ old('stock_quantity', 0) }}" required min="0">
                                @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Row 7 — Units --}}
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Purchase Unit
                                    <small class="text-muted">(qty per purchase pack)</small>
                                </label>
                                <input type="number" name="purchase_unit" class="form-control"
                                    value="{{ old('purchase_unit') }}" min="1">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Sale Unit
                                    <small class="text-muted">(qty per sale pack)</small>
                                </label>
                                <input type="number" name="sale_unit" class="form-control"
                                    value="{{ old('sale_unit') }}" min="1">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Unit</label>
                                <input type="text" name="unit" class="form-control"
                                    value="{{ old('unit') }}" placeholder="e.g., Strip, Bottle, Packet">
                            </div>
                        </div>

                        {{-- Row 8 --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control"
                                    value="{{ old('expiry_date') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3 pt-4 mt-2">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" value="1"
                                        class="form-check-input" id="is_active" checked>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Save Medicine</button>
                        <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-fill supplier code and name when supplier is selected
    document.getElementById('supplier_select').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        document.getElementById('supplier_code').value = selected.dataset.code || '';
        document.getElementById('supplier_name').value = selected.dataset.name || '';
    });
</script>
@endpush
@endsection