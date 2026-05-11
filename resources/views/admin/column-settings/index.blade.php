@extends('layouts.sneat')

@section('title', 'Column Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Column Settings</h5>
            <div class="card-body">
                <p>Configure which columns to display in your commitment notes table:</p>
                
                <!-- Add Custom Column Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6>Add Custom Column</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.column-settings.update') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Column Name</label>
                                        <input type="text" name="new_column_name" class="form-control" placeholder="Enter custom column name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-success w-100">Add Column</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Existing Columns -->
                <form action="{{ route('admin.column-settings.update') }}" method="POST">
                    @csrf
                    
                    <!-- Hidden input to track all columns -->
                    @foreach($settings as $setting)
                        <input type="hidden" name="all_columns[]" value="{{ $setting->column_name }}">
                    @endforeach
                    
                    <div class="row">
                        @foreach($settings as $setting)
                            <div class="col-md-3 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           name="columns[{{ $setting->column_name }}]" 
                                           id="column_{{ $setting->column_name }}"
                                           class="form-check-input"
                                           {{ $setting->is_visible ? 'checked' : '' }}>
                                    <label class="form-check-label" for="column_{{ $setting->column_name }}">
                                        {{ $setting->display_name ?: $setting->column_name }}
                                    </label>
                                    @if($setting->is_custom)
                                        <a href="{{ route('admin.column-settings.delete', $setting->id) }}" 
                                           class="btn btn-sm btn-danger ms-2"
                                           onclick="return confirm('Are you sure you want to delete this custom column?')">
                                            <i class="bx bx-trash"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                        <a href="{{ route('admin.commitment-notes.index') }}" class="btn btn-secondary">Back to Notes</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection