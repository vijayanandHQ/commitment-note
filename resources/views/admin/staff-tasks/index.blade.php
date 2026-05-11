@extends('layouts.sneat')

@section('title', 'Staff Task Assignments')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Staff Task Assignments</h5>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">All Staff Task Assignments</h6>
                    <a href="{{ route('admin.tasks.index') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Assign New Task
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Staff Name</th>
                                <th>Task Title</th>
                                <th>Amount</th>
                                <th>Assignment Date</th>
                                <th>Status</th>
                                <th>Started At</th>
                                <th>Completed At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffTasks as $staffTask)
                            <tr>
                                <td>{{ $staffTask->staff->name }}</td>
                                <td>{{ $staffTask->task->title }}</td>
                                <td>₹{{ number_format($staffTask->task->amount, 2) }}</td>
                                <td>{{ $staffTask->assigned_at ? $staffTask->assigned_at->format('d-m-Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $staffTask->status == 'completed' ? 'success' : ($staffTask->status == 'in_progress' ? 'warning' : ($staffTask->status == 'rejected' ? 'danger' : 'info')) }}">
                                        {{ ucfirst($staffTask->status) }}
                                    </span>
                                </td>
                                <td>{{ $staffTask->started_at ? $staffTask->started_at->format('d-m-Y H:i') : 'N/A' }}</td>
                                <td>{{ $staffTask->completed_at ? $staffTask->completed_at->format('d-m-Y H:i') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.staff-tasks.show', $staffTask) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.staff-tasks.edit', $staffTask) }}" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No staff task assignments found.</td>
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