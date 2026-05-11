@extends('layouts.sneat')  <!-- Changed from layouts.app -->

@section('title', 'Complete Task: ' . $staffTask->task->title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Complete Task: {{ $staffTask->task->title }}</h5>
            <div class="card-body">
                <form action="{{ route('staff.complete-task', $staffTask) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Task Title</label>
                                <input type="text" class="form-control" value="{{ $staffTask->task->title }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Amount to Earn</label>
                                <input type="text" class="form-control" value="₹{{ number_format($staffTask->task->amount, 2) }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Task Description</label>
                        <textarea class="form-control" rows="3" readonly>{{ $staffTask->task->description }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Proof Description</label>
                        <textarea name="proof_description" class="form-control" rows="3" placeholder="Describe the work done...">{{ old('proof_description') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload Proof Photos *</label>
                        <input type="file" name="proof_photos[]" class="form-control" multiple accept="image/*" required>
                        <small class="form-text text-muted">Upload at least one proof photo. Max size: 2MB each, Formats: JPEG, PNG, JPG, GIF</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6>Important Information:</h6>
                        <p class="mb-0">Upon completing this task, ₹{{ number_format($staffTask->task->amount, 2) }} will be added to your wallet balance. Make sure to upload clear proof photos.</p>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Complete Task & Add to Wallet</button>
                        <a href="{{ route('staff.my-tasks') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection