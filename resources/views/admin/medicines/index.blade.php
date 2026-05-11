@extends('layouts.sneat')

@section('title', 'Medicines')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Medicine Management</h5>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">All Medicines</h6>
                    <div>
                        <a href="{{ route('admin.medicines.import.form') }}" class="btn btn-success me-2">
                            <i class="bx bx-import"></i> Import Excel
                        </a>
                        <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Add New Medicine
                        </a>
                    </div>
                </div>

                {{-- Search and Filter --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="{{ route('admin.medicines.index') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Search by name, generic name, manufacturer..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                            @if(request()->has('search') || request()->has('category') || request()->has('status'))
                                <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary ms-2">Clear</a>
                            @endif
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            <select name="category" class="form-select w-auto me-2" onchange="window.location.href = this.value ? '{{ route('admin.medicines.index') }}?category=' + this.value : '{{ route('admin.medicines.index') }}'">
                                <option value="">All Categories</option>
                                @foreach($categories ?? [] as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            <select name="status" class="form-select w-auto" onchange="window.location.href = this.value ? '{{ route('admin.medicines.index') }}?status=' + this.value : '{{ route('admin.medicines.index') }}'">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Code</th>
                                <th>Name</th>
                                <th>Generic Name</th>
                                <th>Manufacturer</th>
                                <th>MRP (₹)</th>
                                <th>Purchase Price</th>
                                <th>Stock</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medicines as $medicine)
                            <tr>
                                <td>{{ $medicine->id }}</td>
                                <td>{{ $medicine->product_code ?? 'N/A' }}</td>
                                <td>{{ $medicine->name }}</td>
                                <td>{{ $medicine->generic_name ?? 'N/A' }}</td>
                                <td>{{ $medicine->manufacturer ?? 'N/A' }}</td>
                                <td>₹{{ number_format($medicine->price, 2) }}</td>
                                <td>₹{{ number_format($medicine->purchase_price ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $medicine->stock_quantity > 10 ? 'success' : ($medicine->stock_quantity > 0 ? 'warning' : 'danger') }}">
                                        {{ $medicine->stock_quantity }}
                                    </span>
                                </td>
                                <td>{{ $medicine->category ?? 'General' }}</td>
                                <td>
                                    <span class="badge bg-{{ $medicine->is_active ? 'success' : 'secondary' }}">
                                        {{ $medicine->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.medicines.show', $medicine) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.medicines.edit', $medicine) }}" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">No medicines found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(method_exists($medicines, 'links'))
                    <div class="mt-3">
                        {{ $medicines->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection