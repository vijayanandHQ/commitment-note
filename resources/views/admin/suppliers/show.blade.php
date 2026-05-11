@extends('layouts.sneat')

@section('title', 'Supplier Details: ' . $supplier->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Supplier Details</h5>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">{{ $supplier->name }}</h6>
                    <div>
                        <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-warning me-2">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to List
                        </a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Supplier Name</label>
                            <p class="form-control-plaintext">{{ $supplier->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Company Name</label>
                            <p class="form-control-plaintext">{{ $supplier->company_name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Contact Person</label>
                            <p class="form-control-plaintext">{{ $supplier->contact_person ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <p class="form-control-plaintext">{{ $supplier->phone }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <p class="form-control-plaintext">{{ $supplier->email ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">GST Number</label>
                            <p class="form-control-plaintext">{{ $supplier->gst_number ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">City</label>
                            <p class="form-control-plaintext">{{ $supplier->city ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <p class="form-control-plaintext">{{ $supplier->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <p class="form-control-plaintext">{{ $supplier->notes ?? 'No notes available.' }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <span class="badge bg-{{ $supplier->is_active ? 'success' : 'secondary' }}">
                        {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection