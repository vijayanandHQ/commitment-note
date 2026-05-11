@extends('layouts.sneat')

@section('title', 'Task Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Task List</h5>
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                    <div>
                        <small>Total: {{ $tasks->total() }} task(s)</small>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Add New Task
                        </a>
                        <a href="{{ route('admin.task-assignments.index') }}" class="btn btn-outline-primary">
                            <i class="bx bx-task"></i> Bulk Assign
                        </a>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>
                                    Title
                                    <a href="?sort=title&order=asc" class="text-muted ms-1" title="Sort A-Z">
                                        <i class="bx bx-up-arrow-alt small"></i>
                                    </a>
                                    <a href="?sort=title&order=desc" class="text-muted ms-1" title="Sort Z-A">
                                        <i class="bx bx-down-arrow-alt small"></i>
                                    </a>
                                </th>
                                <th>
                                    Amount
                                    <a href="?sort=amount&order=asc" class="text-muted ms-1" title="Sort Low to High">
                                        <i class="bx bx-up-arrow-alt small"></i>
                                    </a>
                                    <a href="?sort=amount&order=desc" class="text-muted ms-1" title="Sort High to Low">
                                        <i class="bx bx-down-arrow-alt small"></i>
                                    </a>
                                </th>
                                <th>Due Date</th>
                                <th>Priority</th>
                                <th>Proof Required</th>
                                <th>Assigned Staff</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $task->title }}</td>
                                <td>
                                    @if($task->amount < 0)
                                        <span class="text-danger">₹{{ number_format(abs($task->amount), 2) }} <small>(Penalty)</small></span>
                                    @else
                                        <span class="text-success">₹{{ number_format($task->amount, 2) }}</span>
                                    @endif
                                </td>
                                <td>{{ $task->due_date ? $task->due_date->format('d-m-Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-warning" style="font-size: 0.75rem;">{{ ucfirst($task->priority) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary" style="font-size: 0.75rem;">{{ $task->requires_proof ? 'YES' : 'NO' }}</span>
                                </td>
                                <td>
                                    @if($task->staffs->count() > 0)
                                        @foreach($task->staffs->take(2) as $staff)
                                            <span class="badge" style="background-color: 
                                                @if($staff->position == 'Field Executive') #007BFF
                                                @elseif($staff->position == 'Sales Manager') #28A745
                                                @elseif($staff->position == 'Field Worker') #FFC107
                                                @elseif($staff->position == 'Admin') #6F42C1
                                                @else #6c757d
                                                @endif; color: white; font-size: 0.75rem;">
                                                {{ Str::limit($staff->name, 8) }}
                                            </span>
                                        @endforeach
                                        @if($task->staffs->count() > 2)
                                            <span class="badge bg-secondary" style="font-size: 0.75rem;">+{{ $task->staffs->count() - 2 }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.tasks.show', $task) }}" 
                                           class="btn btn-outline-info" 
                                           data-bs-toggle="tooltip" 
                                           title="View Details">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('admin.tasks.edit', $task) }}" 
                                           class="btn btn-outline-success" 
                                           data-bs-toggle="tooltip" 
                                           title="Edit Task">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.tasks.destroy', $task) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Delete Task"
                                                    onclick="return confirm('Are you sure you want to delete this task?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bx bx-task bx-lg text-muted"></i>
                                    <p class="mt-2 mb-0">No tasks found</p>
                                    <small class="text-muted">Start by creating your first task</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Custom Pagination Like Your Screenshot -->
                <div class="d-flex justify-content-center mt-3">
                    <nav>
                        <ul class="pagination pagination-sm">
                            @if ($tasks->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="bx bx-left-arrow-alt"></i></span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $tasks->previousPageUrl() }}" aria-label="Previous">
                                        <i class="bx bx-left-arrow-alt"></i>
                                    </a>
                                </li>
                            @endif

                            @foreach ($tasks->getUrlRange(1, $tasks->lastPage()) as $page => $url)
                                @if ($page == $tasks->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            @if ($tasks->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $tasks->nextPageUrl() }}" aria-label="Next">
                                        <i class="bx bx-right-arrow-alt"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="bx bx-right-arrow-alt"></i></span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Showing {{ $tasks->firstItem() ?? 0 }} to {{ $tasks->lastItem() ?? 0 }} 
                        of {{ $tasks->total() }} tasks
                    </small>
                    <small class="text-muted">
                        Page {{ $tasks->currentPage() }} of {{ $tasks->lastPage() }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection