@extends('layouts.sneat')  <!-- Changed from layouts.app -->

@section('title', 'My Tasks')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">My Tasks</h5>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">All My Tasks</h6>
                    <div>
                        <a href="{{ route('staff.my-tasks', ['status' => 'all']) }}" class="btn btn-sm {{ $statusFilter == 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
                        <a href="{{ route('staff.my-tasks', ['status' => 'assigned']) }}" class="btn btn-sm {{ $statusFilter == 'assigned' ? 'btn-primary' : 'btn-outline-secondary' }}">Assigned</a>
                        <a href="{{ route('staff.my-tasks', ['status' => 'in_progress']) }}" class="btn btn-sm {{ $statusFilter == 'in_progress' ? 'btn-primary' : 'btn-outline-secondary' }}">In Progress</a>
                        <a href="{{ route('staff.my-tasks', ['status' => 'completed']) }}" class="btn btn-sm {{ $statusFilter == 'completed' ? 'btn-primary' : 'btn-outline-secondary' }}">Completed</a>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Task Title</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Assigned At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffTasks as $staffTask)
                            <tr>
                                <td>{{ $staffTask->task->title }}</td>
                                <td>₹{{ number_format($staffTask->task->amount, 2) }}</td>
                                <td>{{ Str::limit($staffTask->task->description, 50) }}</td>
                                <td>{{ $staffTask->task->due_date ? $staffTask->task->due_date->format('d-m-Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $staffTask->status == 'completed' ? 'success' : ($staffTask->status == 'in_progress' ? 'warning' : ($staffTask->status == 'rejected' ? 'danger' : 'info')) }}">
                                        {{ ucfirst($staffTask->status) }}
                                    </span>
                                </td>
                                <td>{{ $staffTask->assigned_at ? $staffTask->assigned_at->format('d-m-Y') : 'N/A' }}</td>
                                <td>
                                    @if($staffTask->status == 'assigned')
                                        <a href="{{ route('staff.complete-task.form', $staffTask) }}" class="btn btn-sm btn-primary">Start Task</a>
                                    @elseif($staffTask->status == 'in_progress')
                                        <a href="{{ route('staff.complete-task.form', $staffTask) }}" class="btn btn-sm btn-success">Complete Task</a>
                                    @elseif($staffTask->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No tasks found.</td>
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