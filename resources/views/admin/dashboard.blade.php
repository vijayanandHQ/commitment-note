@extends('layouts.sneat')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-8 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Welcome {{ Auth::user()->name }}! 🎉</h5>
                        <p class="mb-4">
                            You have successfully logged in as an <strong>{{ Auth::user()->role }}</strong>.
                        </p>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img
                            src="{{ asset('sneat/assets/img/illustrations/man-with-laptop-light.png') }}"
                            height="140"
                            alt="View Badge User"
                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img
                                    src="{{ asset('sneat/assets/img/icons/unicons/chart-success.png') }}"
                                    alt="chart success"
                                    class="rounded"
                                />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Records</span>
                        <h3 class="card-title mb-2">{{ $notes->count() }}</h3>
                        <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +0%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Column Toggle Form -->
<form id="columnToggleForm" class="mb-3">
    <label><input type="checkbox" name="show_qty" checked> Qty</label>
    <label class="ms-3"><input type="checkbox" name="show_mrp" checked> MRP</label>
    <label class="ms-3"><input type="checkbox" name="show_supplier" checked> Supplier</label>
    <label class="ms-3"><input type="checkbox" name="show_comments" checked> Comments</label>
    <button type="submit" class="btn btn-sm btn-info ms-3">Apply</button>
</form>

<div class="card">
    <h5 class="card-header">Commitment Notes</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-striped" id="commitmentTable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Date</th>
                    <th class="col-qty">Qty</th>
                    <th>Product Name</th>
                    <th class="col-mrp">MRP</th>
                    <th>Order Qty</th>
                    <th class="col-supplier">Supplier</th>
                    <th>Customer Phone</th>
                    <th class="col-comments">Comments</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notes as $note)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $note->date }}</td>
                    <td class="col-qty">{{ $note->qty }}</td>
                    <td>{{ $note->product_name }}</td>
                    <td class="col-mrp">{{ $note->mrp }}</td>
                    <td>{{ $note->order_qty }}</td>
                    <td class="col-supplier">{{ $note->supplier }}</td>
                    <td>{{ $note->customer_phone }}</td>
                    <td class="col-comments">{{ $note->comments ?? 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">No records found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('page-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('columnToggleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const checkboxes = this.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => {
            const colName = cb.name.replace('show_', '');
            const shouldShow = cb.checked;
            
            // Hide/show corresponding column cells
            document.querySelectorAll(`.col-${colName}`).forEach(cell => {
                cell.style.display = shouldShow ? '' : 'none';
            });
        });
    });
});
</script>
@endsection