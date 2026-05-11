@extends('layouts.sneat')

@section('title', 'Assignment Details')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <h5 class="card-header">Assignment Details</h5>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Staff:</strong>
                        <p class="mb-0">{{ $assignment->staff->name }}</p>
                        <small class="text-muted">{{ $assignment->staff->position ?? 'Staff' }}</small>
                    </div>
                    <div class="col-md-6">
                        <strong>Assigned At:</strong>
                        <p class="mb-0">{{ $assignment->created_at->format('d-m-Y H:i') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Task:</strong>
                        <p class="mb-0">{{ $assignment->task->title }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Amount:</strong>
                        <p class="mb-0">
                            <span class="{{ ($assignment->task->amount < 0) ? 'text-danger' : 'text-success' }}">
                                ₹{{ number_format(abs($assignment->task->amount), 2) }}
                                @if($assignment->task->amount < 0) <small>(Penalty)</small> @endif
                            </span>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <p class="mb-0">
                            <span class="badge bg-label-{{ 
                                ($assignment->status === 'completed') ? 'success' : 
                                (($assignment->status === 'in_progress') ? 'warning' : 
                                (($assignment->status === 'rejected') ? 'danger' : 'info')) 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Requires Proof:</strong>
                        <p class="mb-0">{{ $assignment->task->requires_proof ? 'Yes' : 'No' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Started At:</strong>
                        <p class="mb-0">{{ $assignment->started_at ? $assignment->started_at->format('d-m-Y H:i') : 'Not started' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Completed At:</strong>
                        <p class="mb-0">{{ $assignment->completed_at ? $assignment->completed_at->format('d-m-Y H:i') : 'Not completed' }}</p>
                    </div>
                </div>

                @if($assignment->notes)
                <div class="mb-3">
                    <strong>Notes:</strong>
                    <p class="mb-0">{{ $assignment->notes }}</p>
                </div>
                @endif

                @if($assignment->proof_photos)
                <div class="mb-3">
                    <strong>Proof Photos:</strong>
                    <div class="row">
                        @foreach($assignment->proof_photos as $photo)
                            <div class="col-md-3 mb-2">
                                <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Proof" class="img-thumbnail" style="width: 100%; height: auto;">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.task-assignments.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Assignments
                    </a>
                    <div class="btn-group">
                        <a href="{{ route('admin.task-assignments.edit', $assignment->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit Status
                        </a>
                        <form action="{{ route('admin.task-assignments.destroy', $assignment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this assignment?')">
                                <i class="bx bx-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection