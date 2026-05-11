@extends('layouts.sneat')

@section('title', 'Staff Details: ' . $staff->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Staff Details</h5>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">{{ $staff->name }}</h6>
                    <div>
                        <a href="{{ route('admin.staffs.edit', $staff) }}" class="btn btn-warning me-2">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to List
                        </a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Name:</strong> {{ $staff->name }}
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong> {{ $staff->email }}
                        </div>
                        <div class="mb-3">
                            <strong>Phone:</strong> {{ $staff->phone ?: 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Position:</strong> {{ $staff->position ?: 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Balance:</strong> ₹{{ number_format($staff->balance, 2) }}
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong> 
                            <span class="badge bg-{{ $staff->is_active ? 'success' : 'secondary' }}">
                                {{ $staff->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Photo:</strong>
                            @if($staff->photo)
                                <br>
                                <img src="{{ asset('storage/' . $staff->photo) }}" alt="Photo" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="mb-3">
                            <strong>Bio:</strong> {{ $staff->bio ?: 'N/A' }}
                        </div>
                    </div>
                </div>
                
                <h6 class="mt-4">Task Summary</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="card-title text-info">{{ $staff->assigned_tasks_count }}</h4>
                                <p class="card-text">Assigned Tasks</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="card-title text-warning">{{ $staff->in_progress_tasks_count }}</h4>
                                <p class="card-text">In Progress</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="card-title text-success">{{ $staff->completed_tasks_count }}</h4>
                                <p class="card-text">Completed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection