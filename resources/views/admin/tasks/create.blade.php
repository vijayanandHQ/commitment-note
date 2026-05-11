@extends('layouts.sneat')

@section('title', 'Add New Task')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Add New Task</h5>
            <div class="card-body">
                <form action="{{ route('admin.tasks.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Amount *</label>
                                <input type="number" step="0.01" name="amount" class="form-control" 
                                       value="{{ old('amount', 0) }}" required>
                                <small class="form-text text-muted">Use negative values for penalties (e.g., -200)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Due Date</label>
                                <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Priority *</label>
                                <select name="priority" class="form-select" required>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="requires_proof" id="requires_proof" class="form-check-input" {{ old('requires_proof') ? 'checked' : '' }}>
                        <label class="form-check-label" for="requires_proof">Requires Photo Proof</label>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Assign to Staff</label>
                        <select name="assigned_staff[]" class="form-select" multiple>
                            @foreach($staffs as $staff)
                                <option value="{{ $staff->id }}" {{ in_array($staff->id, old('assigned_staff', [])) ? 'selected' : '' }}>
                                    {{ $staff->name }} ({{ $staff->position ?? 'Staff' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple staff members</small>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Save Task</button>
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection