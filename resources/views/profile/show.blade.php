@extends('layouts.sneat')

@section('title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <h5 class="card-header">My Profile</h5>

            <div class="card-body text-center">
                <img
                    src="{{ Auth::user()->avatar_url }}"
                    alt="avatar"
                    class="rounded-circle img-fluid mb-3"
                    style="width: 150px; height: 150px; object-fit: cover;"
                >

                <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted mb-1">{{ ucfirst(Auth::user()->role) }}</p>
                <p class="text-muted mb-3">{{ Auth::user()->email }}</p>

                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    Edit Profile
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
