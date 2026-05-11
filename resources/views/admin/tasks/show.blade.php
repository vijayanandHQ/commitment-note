@extends('layouts.sneat')

@section('title', 'Task Details: ' . $task->title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Task Details</h5>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">{{ $task->title }}</h6>
                    <div>
                        <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-warning me-2">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to List
                        </a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Title:</strong> {{ $task->title }}
                        </div>
                        <div class="mb-3">
                            <strong>Description:</strong> {{ $task->description ?: 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Amount:</strong> ₹{{ number_format($task->amount, 2) }}
                        </div>
                        <div class="mb-3">
                            <strong>Due Date:</strong> {{ $task->due_date ? $task->due_date->format('d-m-Y') : 'N/A' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Priority:</strong> 
                            <span class="badge bg-{{ $task->priority == 'high' || $task->priority == 'urgent' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'info') }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Requires Proof:</strong> 
                            <span class="badge bg-{{ $task->requires_proof ? 'primary' : 'secondary' }}">
                                {{ $task->requires_proof ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong> 
                            <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'info') }}">
                                {{ ucfirst($task->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <h6 class="mt-4">Assigned Staff</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Staff Name</th>
                                <th>Status</th>
                                <th>Assigned At</th>
                                <th>Completed At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($task->staffTasks as $staffTask)
                            <tr>
                                <td>{{ $staffTask->staff->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $staffTask->status == 'completed' ? 'success' : ($staffTask->status == 'in_progress' ? 'warning' : ($staffTask->status == 'rejected' ? 'danger' : 'info')) }}">
                                        {{ ucfirst($staffTask->status) }}
                                    </span>
                                </td>
                                <td>{{ $staffTask->assigned_at ? $staffTask->assigned_at->format('d-m-Y H:i') : 'N/A' }}</td>
                                <td>{{ $staffTask->completed_at ? $staffTask->completed_at->format('d-m-Y H:i') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.staff-tasks.show', $staffTask) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No staff assigned to this task.</td>
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