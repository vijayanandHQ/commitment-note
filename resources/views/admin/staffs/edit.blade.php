@extends('layouts.sneat')

@section('title', 'Edit Staff: ' . $staff->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Edit Staff</h5>
            <div class="card-body">
                <form action="{{ route('admin.staffs.update', $staff) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $staff->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $staff->email) }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $staff->phone) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Position</label>
                                <input type="text" name="position" class="form-control" value="{{ old('position', $staff->position) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="3">{{ old('bio', $staff->bio) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        @if($staff->photo)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $staff->photo) }}" alt="Current Photo" style="width: 100px; height: 100px; object-fit: cover;">
                                <small class="form-text text-muted">Current photo</small>
                            </div>
                        @endif
                        <small class="form-text text-muted">Max size: 2MB, Formats: JPEG, PNG, JPG, GIF</small>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ old('is_active', $staff->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update Staff</button>
                        <a href="{{ route('admin.staffs.show', $staff) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection