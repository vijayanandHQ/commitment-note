@extends('layouts.sneat')

@section('title', 'Edit Supplier: ' . $supplier->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Edit Supplier</h5>
            <div class="card-body">
                <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Supplier Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name) }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Company Name *</label>
                                <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $supplier->company_name) }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Contact Person</label>
                                <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $supplier->contact_person) }}">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">GST Number</label>
                                <input type="text" name="gst_number" class="form-control" value="{{ old('gst_number', $supplier->gst_number) }}">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $supplier->city) }}">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" value="{{ old('state', $supplier->state) }}">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ old('country', $supplier->country) }}">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2">{{ old('address', $supplier->address) }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $supplier->notes) }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label">Active</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update Supplier</button>
                        <a href="{{ route('admin.suppliers.show', $supplier) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection