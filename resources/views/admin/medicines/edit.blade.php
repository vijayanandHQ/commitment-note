@extends('layouts.sneat')

@section('title', 'Edit Medicine: ' . $medicine->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Edit Medicine</h5>
            <div class="card-body">
                <form action="{{ route('admin.medicines.update', $medicine) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Medicine Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $medicine->name) }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Generic Name</label>
                                <input type="text" name="generic_name" class="form-control" value="{{ old('generic_name', $medicine->generic_name) }}">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Manufacturer</label>
                                <input type="text" name="manufacturer" class="form-control" value="{{ old('manufacturer', $medicine->manufacturer) }}">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Price *</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $medicine->price) }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Stock Quantity *</label>
                                <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $medicine->stock_quantity) }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">Select Category</option>
                                    <option value="Tablet" {{ $medicine->category == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                    <option value="Capsule" {{ $medicine->category == 'Capsule' ? 'selected' : '' }}>Capsule</option>
                                    <option value="Syrup" {{ $medicine->category == 'Syrup' ? 'selected' : '' }}>Syrup</option>
                                    <option value="Injection" {{ $medicine->category == 'Injection' ? 'selected' : '' }}>Injection</option>
                                    <option value="Cream" {{ $medicine->category == 'Cream' ? 'selected' : '' }}>Cream</option>
                                    <option value="Other" {{ $medicine->category == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Unit</label>
                                <input type="text" name="unit" class="form-control" value="{{ old('unit', $medicine->unit) }}" placeholder="e.g., Strip, Bottle, Packet">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $medicine->expiry_date ? $medicine->expiry_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $medicine->description) }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $medicine->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label">Active</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update Medicine</button>
                        <a href="{{ route('admin.medicines.show', $medicine) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection