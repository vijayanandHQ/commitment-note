@extends('layouts.sneat')

@section('title', 'Work Lists')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Work Lists</h5>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">All Work Lists</h6>
                    <a href="{{ route('admin.work-lists.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add New Work List
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Staff Name</th>
                                <th>Photo</th>
                                <th>Tasks</th>
                                <th>Upload Date</th>
                                <th>PPT</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($workLists as $workList)
                            <tr>
                                <td>{{ $workList->staff_name }}</td>
                                <td>
                                    @if($workList->photo)
                                        <img src="{{ asset('storage/' . $workList->photo) }}" alt="Photo" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <span>N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($workList->task_status)
                                        {{ count($workList->task_status) }} tasks
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $workList->upload_date ? $workList->upload_date->format('d-m-Y') : 'N/A' }}</td>
                                <td>
                                    @if($workList->ppt_file)
                                        <a href="{{ asset('storage/' . $workList->ppt_file) }}" target="_blank">Download PPT</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($workList->task_status)
                                        {{ $workList->completed_tasks_count }}/{{ $workList->total_tasks_count }}
                                    @else
                                        0/0
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.work-lists.show', $workList) }}" class="btn btn-sm btn-info">View</a>
                                    @if($workList->is_editable)
                                        <a href="{{ route('admin.work-lists.edit', $workList) }}" class="btn btn-sm btn-warning">Edit</a>
                                    @endif
                                    <form action="{{ route('admin.work-lists.destroy', $workList) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this work list?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No work lists found.</td>
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