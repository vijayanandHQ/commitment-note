@extends('layouts.sneat')

@section('title', 'Edit Profile')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">

        <h4 class="fw-bold py-3 mb-4">Edit Profile</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Update profile info --}}
        <div class="card mb-4">
            <h5 class="card-header">Profile Information</h5>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update password --}}
        <div class="card mb-4">
            <h5 class="card-header">Update Password</h5>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Delete account --}}
        <div class="card mb-4 border-danger">
            <h5 class="card-header text-danger">Delete Account</h5>
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection
