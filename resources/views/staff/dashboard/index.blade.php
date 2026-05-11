@extends('layouts.sneat')

@section('title', 'Staff Dashboard')

@section('content')
<div class="row">
    <!-- Wallet Balance Card -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-1">Wallet Balance</h5>
                        <small class="text-muted">Available Funds</small>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class='bx bx-wallet'></i>
                        </span>
                    </div>
                </div>
                <div class="d-flex align-items-end flex-column mt-3">
                    <span class="text-primary fs-3 fw-semibold">₹{{ number_format($staffModel->balance, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Tasks -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-1">Assigned</h5>
                        <small class="text-muted">Pending Tasks</small>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class='bx bx-task'></i>
                        </span>
                    </div>
                </div>
                <div class="d-flex align-items-end flex-column mt-3">
                    <span class="text-info fs-3 fw-semibold">{{ $assignedTasks }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- In Progress -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-1">In Progress</h5>
                        <small class="text-muted">Active Tasks</small>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class='bx bx-loader-circle'></i>
                        </span>
                    </div>
                </div>
                <div class="d-flex align-items-end flex-column mt-3">
                    <span class="text-warning fs-3 fw-semibold">{{ $inProgressTasks }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-1">Completed</h5>
                        <small class="text-muted">Finished Tasks</small>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class='bx bx-check-circle'></i>
                        </span>
                    </div>
                </div>
                <div class="d-flex align-items-end flex-column mt-3">
                    <span class="text-success fs-3 fw-semibold">{{ $completedTasks }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Recent Tasks</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Task Title</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Assigned Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTasks as $task)
                        <tr>
                            <td>{{ $task->task->title }}</td>
                            <td>₹{{ number_format($task->task->amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : ($task->status == 'rejected' ? 'danger' : 'info')) }}">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </td>
                            <td>{{ $task->assigned_at ? $task->assigned_at->format('d-m-Y') : 'N/A' }}</td>
                            <td>
                                @if($task->status == 'assigned')
                                    <a href="{{ route('staff.complete-task.form', $task) }}" class="btn btn-sm btn-primary">Start Task</a>
                                @elseif($task->status == 'in_progress')
                                    <a href="{{ route('staff.complete-task.form', $task) }}" class="btn btn-sm btn-success">Complete Task</a>
                                @else
                                    <span class="text-muted">Completed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No recent tasks found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection