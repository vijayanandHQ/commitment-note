@extends('layouts.sneat')  <!-- Changed from layouts.app -->

@section('title', 'Staff Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Welcome, {{ Auth::user()->name }}!</h5>
                    <p class="card-text">This is your staff dashboard.</p>
                    
                    <!-- Wallet Balance Card -->
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="card-title mb-0">
                                            <h5 class="mb-1">Wallet Balance</h5>
                                            <small class="text-muted">Available Funds</small>
                                        </div>
                                        <div class="avatar flex-shrink-0">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                <i class='bx bx-wallet'></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end flex-column mt-3">
                                        <span class="text-primary fs-3 fw-semibold">₹{{ number_format(Auth::user()->balance ?? 0, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add other cards here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection