@extends('layouts.sneat')

@section('title', 'Medicine Details: ' . $medicine->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Medicine Details</h5>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">{{ $medicine->name }}</h6>
                    <div>
                        <a href="{{ route('admin.medicines.edit', $medicine) }}" class="btn btn-warning me-2">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to List
                        </a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Medicine Name</label>
                            <p class="form-control-plaintext">{{ $medicine->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Generic Name</label>
                            <p class="form-control-plaintext">{{ $medicine->generic_name ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Manufacturer</label>
                            <p class="form-control-plaintext">{{ $medicine->manufacturer ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <p class="form-control-plaintext">₹{{ number_format($medicine->price, 2) }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Stock Quantity</label>
                            <p class="form-control-plaintext">{{ $medicine->stock_quantity }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <p class="form-control-plaintext">{{ $medicine->category ?? 'General' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Unit</label>
                            <p class="form-control-plaintext">{{ $medicine->unit ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Expiry Date</label>
                            <p class="form-control-plaintext">{{ $medicine->expiry_date ? $medicine->expiry_date->format('d-m-Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <p class="form-control-plaintext">{{ $medicine->description ?? 'No description available.' }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <span class="badge bg-{{ $medicine->is_active ? 'success' : 'secondary' }}">
                        {{ $medicine->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection