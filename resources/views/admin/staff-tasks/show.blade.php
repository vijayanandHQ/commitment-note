@extends('layouts.sneat')

@section('title', 'Task Assignment Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Task Assignment Details</h5>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Task: {{ $staffTask->task->title }}</h6>
                    <div>
                        <a href="{{ route('admin.staff-tasks.edit', $staffTask) }}" class="btn btn-warning me-2">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.staff-tasks.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to List
                        </a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Staff Name:</strong> {{ $staffTask->staff->name }}
                        </div>
                        <div class="mb-3">
                            <strong>Task Title:</strong> {{ $staffTask->task->title }}
                        </div>
                        <div class="mb-3">
                            <strong>Task Description:</strong> {{ $staffTask->task->description ?: 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Amount:</strong> ₹{{ number_format($staffTask->task->amount, 2) }}
                        </div>
                        <div class="mb-3">
                            <strong>Requires Proof:</strong> 
                            <span class="badge bg-{{ $staffTask->task->requires_proof ? 'primary' : 'secondary' }}">
                                {{ $staffTask->task->requires_proof ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Assignment Date:</strong> {{ $staffTask->assigned_at ? $staffTask->assigned_at->format('d-m-Y H:i') : 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Started At:</strong> {{ $staffTask->started_at ? $staffTask->started_at->format('d-m-Y H:i') : 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Completed At:</strong> {{ $staffTask->completed_at ? $staffTask->completed_at->format('d-m-Y H:i') : 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong> 
                            <span class="badge bg-{{ $staffTask->status == 'completed' ? 'success' : ($staffTask->status == 'in_progress' ? 'warning' : ($staffTask->status == 'rejected' ? 'danger' : 'info')) }}">
                                {{ ucfirst($staffTask->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <h6 class="mt-4">Proof Information</h6>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <strong>Proof Description:</strong> {{ $staffTask->proof_description ?: 'N/A' }}
                        </div>
                        @if($staffTask->proof_photos)
                            <div class="mb-3">
                                <strong>Proof Photos:</strong>
                                <div class="row">
                                    @foreach(json_decode($staffTask->proof_photos) as $photo)
                                        <div class="col-md-3 mb-2">
                                            <img src="{{ asset('storage/' . $photo) }}" alt="Proof" class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <strong>Additional Notes:</strong> {{ $staffTask->notes ?: 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection