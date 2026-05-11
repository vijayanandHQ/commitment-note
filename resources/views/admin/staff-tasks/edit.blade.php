@extends('layouts.sneat')

@section('title', 'Edit Task Assignment')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Edit Task Assignment</h5>
            <div class="card-body">
                <form action="{{ route('admin.staff-tasks.update', $staffTask) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Staff Name</label>
                                <input type="text" class="form-control" value="{{ $staffTask->staff->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Task Title</label>
                                <input type="text" class="form-control" value="{{ $staffTask->task->title }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    <option value="assigned" {{ old('status', $staffTask->status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                    <option value="in_progress" {{ old('status', $staffTask->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status', $staffTask->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="rejected" {{ old('status', $staffTask->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input type="text" class="form-control" value="₹{{ number_format($staffTask->task->amount, 2) }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Proof Description</label>
                        <textarea name="proof_description" class="form-control" rows="3">{{ old('proof_description', $staffTask->proof_description) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Additional Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $staffTask->notes) }}</textarea>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update Assignment</button>
                        <a href="{{ route('admin.staff-tasks.show', $staffTask) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection