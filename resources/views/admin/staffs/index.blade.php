@extends('layouts.sneat')

@section('title', 'Staff Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Staff Management</h5>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">All Staff Members</h6>
                    <a href="{{ route('admin.staffs.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add New Staff
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Position</th>
                                <th>Balance</th>
                                <th>Tasks</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffs as $staff)
                            <tr>
                                <td>
                                    @if($staff->photo)
                                        <img src="{{ asset('storage/' . $staff->photo) }}" alt="Photo" style="width: 30px; height: 30px; object-fit: cover;" class="me-2">
                                    @endif
                                    {{ $staff->name }}
                                </td>
                                <td>{{ $staff->email }}</td>
                                <td>{{ $staff->position ?: 'N/A' }}</td>
                                <td>₹{{ number_format($staff->balance, 2) }}</td>
                                <td>
                                    <span class="badge bg-info">Total: {{ $staff->staffTasks->count() }}</span>
                                    <span class="badge bg-success">Completed: {{ $staff->completed_tasks_count }}</span>
                                    <span class="badge bg-warning">In Progress: {{ $staff->in_progress_tasks_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $staff->is_active ? 'success' : 'secondary' }}">
                                        {{ $staff->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.staffs.show', $staff) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.staffs.edit', $staff) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.staffs.destroy', $staff) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No staff members found.</td>
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