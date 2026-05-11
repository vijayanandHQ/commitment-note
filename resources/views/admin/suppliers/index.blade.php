@extends('layouts.sneat')

@section('title', 'Suppliers')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Supplier Management</h5>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">All Suppliers</h6>
                    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add New Supplier
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Contact Person</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->company_name }}</td>
                                <td>{{ $supplier->contact_person ?? 'N/A' }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->email ?? 'N/A' }}</td>
                                <td>{{ $supplier->city ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $supplier->is_active ? 'success' : 'secondary' }}">
                                        {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.suppliers.show', $supplier) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No suppliers found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection