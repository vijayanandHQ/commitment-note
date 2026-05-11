@extends('layouts.sneat')

@section('title', 'Work List Details: ' . $workList->staff_name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Work List Details</h5>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">{{ $workList->staff_name }}</h6>
                    <div>
                        @if($workList->is_editable)
                            <a href="{{ route('admin.work-lists.edit', $workList) }}" class="btn btn-warning me-2">
                                <i class="bx bx-edit"></i> Edit
                            </a>
                        @endif
                        <a href="{{ route('admin.work-lists.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to List
                        </a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Staff Name:</strong> {{ $workList->staff_name }}
                        </div>
                        <div class="mb-3">
                            <strong>Upload Date:</strong> {{ $workList->upload_date ? $workList->upload_date->format('d-m-Y') : 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Editable:</strong> {{ $workList->is_editable ? 'Yes' : 'No' }}
                        </div>
                        <div class="mb-3">
                            <strong>Tasks Completed:</strong> {{ $workList->completed_tasks_count }}/{{ $workList->total_tasks_count }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Photo:</strong>
                            @if($workList->photo)
                                <br>
                                <img src="{{ asset('storage/' . $workList->photo) }}" alt="Photo" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="mb-3">
                            <strong>PPT File:</strong>
                            @if($workList->ppt_file)
                                <br>
                                <a href="{{ asset('storage/' . $workList->ppt_file) }}" target="_blank" class="btn btn-sm btn-info">Download PPT</a>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                </div>
                
                <h6 class="mt-4">Tasks</h6>
                <div class="mb-3">
                    @if($workList->task_status)
                        <ul class="list-group">
                            @foreach($workList->task_status as $task => $status)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($status)
                                            <i class="bx bx-check-circle text-success me-2"></i>
                                        @else
                                            <i class="bx bx-checkbox-square text-secondary me-2"></i>
                                        @endif
                                        {{ $task }}
                                    </div>
                                    @if($workList->is_editable)
                                        <div>
                                            @if(!$status)
                                                <a href="#" class="btn btn-sm btn-success mark-complete" data-task="{{ $task }}">Mark Complete</a>
                                            @else
                                                <a href="#" class="btn btn-sm btn-warning mark-incomplete" data-task="{{ $task }}">Mark Incomplete</a>
                                            @endif
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No tasks added.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for marking task as complete -->
<div class="modal fade" id="markCompleteModal" tabindex="-1" aria-labelledby="markCompleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markCompleteModalLabel">Mark Task as Complete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to mark this task as complete?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmMarkComplete">Mark Complete</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for marking task as incomplete -->
<div class="modal fade" id="markIncompleteModal" tabindex="-1" aria-labelledby="markIncompleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markIncompleteModalLabel">Mark Task as Incomplete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to mark this task as incomplete?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmMarkIncomplete">Mark Incomplete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark task as complete
    document.querySelectorAll('.mark-complete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const task = this.getAttribute('data-task');
            document.getElementById('confirmMarkComplete').dataset.task = task;
            const modal = new bootstrap.Modal(document.getElementById('markCompleteModal'));
            modal.show();
        });
    });

    // Confirm mark task as complete
    document.getElementById('confirmMarkComplete').addEventListener('click', function() {
        const task = this.dataset.task;
        fetch('{{ route('admin.work-lists.mark-complete', $workList->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ task: task })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });

    // Mark task as incomplete
    document.querySelectorAll('.mark-incomplete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const task = this.getAttribute('data-task');
            document.getElementById('confirmMarkIncomplete').dataset.task = task;
            const modal = new bootstrap.Modal(document.getElementById('markIncompleteModal'));
            modal.show();
        });
    });

    // Confirm mark task as incomplete
    document.getElementById('confirmMarkIncomplete').addEventListener('click', function() {
        const task = this.dataset.task;
        fetch('{{ route('admin.work-lists.mark-incomplete', $workList->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ task: task })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });
});
</script>
@endsection