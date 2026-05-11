@extends('layouts.sneat')

@section('title', 'Add Work List')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Add New Work List</h5>
            <div class="card-body">
                <form action="{{ route('admin.work-lists.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Staff Name -->
                    <div class="mb-3">
                        <label class="form-label">Staff Name *</label>
                        <input type="text" name="staff_name" class="form-control" value="{{ old('staff_name') }}" required>
                    </div>
                    
                    <!-- Photo -->
                    <div class="mb-3">
                        <label class="form-label">Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Max size: 2MB, Formats: JPEG, PNG, GIF, SVG</small>
                    </div>
                    
                    <!-- Tasks -->
                    <div class="mb-3">
                        <label class="form-label">Tasks (one per line)</label>
                        <textarea name="tasks" class="form-control" rows="10" placeholder="Enter tasks, one per line">{{ old('tasks') }}</textarea>
                        <small class="form-text text-muted">Example: 
1. Check TPS reports Daily A to Z
2. Average Roll Count
3. ...</small>
                    </div>
                    
                    <!-- Upload Date -->
                    <div class="mb-3">
                        <label class="form-label">Upload Date</label>
                        <input type="date" name="upload_date" class="form-control" value="{{ old('upload_date') }}">
                    </div>
                    
                    <!-- PPT File -->
                    <div class="mb-3">
                        <label class="form-label">PPT File</label>
                        <input type="file" name="ppt_file" class="form-control" accept=".ppt,.pptx">
                        <small class="form-text text-muted">Max size: 10MB, Formats: PPT, PPTX</small>
                    </div>
                    
                    <!-- Editable -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_editable" id="is_editable" class="form-check-input" {{ old('is_editable', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_editable">Editable</label>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Save Work List</button>
                        <a href="{{ route('admin.work-lists.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection