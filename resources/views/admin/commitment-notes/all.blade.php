@extends('layouts.sneat')

@section('title', 'All Commitment Notes')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center no-print">
        <h5 class="card-title mb-0 text-primary">
            <i class="bx bx-list-ul me-2"></i>All Commitment Notes 
        </h5>
        <div class="d-flex gap-2">
            <button class="btn btn-success btn-sm" id="exportExcel">
                <i class="bx bx-file me-1"></i>Excel
            </button>
            <button class="btn btn-danger btn-sm" id="exportPdf">
                <i class="bx bx-file-blank me-1"></i>PDF
            </button>
            <button class="btn btn-info btn-sm" id="exportWord">
                <i class="bx bx-file me-1"></i>Word
            </button>
            <button class="btn btn-outline-success btn-sm" onclick="window.print()">
                <i class="bx bx-printer me-1"></i>Print
            </button>
            <a href="{{ route('admin.commitment-notes.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i>Add New
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive text-nowrap p-3">
            <table id="commitmentTable" class="table table-hover table-bordered table-sm mb-0 admin-excel-table">
                <thead class="table-light">
                    <tr>
                        <th class="bg-light">#</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Products</th>
                        <th>Total Qty</th>
                        <th>Amount (₹)</th>
                        <th>Delivery Date</th>
                        <th>Type</th>
                        <th>Stage</th>
                        <th>Supplier</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notes as $index => $note)
                    <tr>
                        <td class="text-center bg-light fw-bold">{{ $index + 1 }}</td>
                        <td>{{ $note->created_at->format('d/m/Y') }}</td>
                        <td class="fw-bold">{{ $note->cus_name ?? 'N/A' }}</td>
                        <td>{{ $note->customer_phone }}</td>
                        <td title="{{ $note->product_name }}">{{ Str::limit($note->product_name, 40) }}</td>
                        <td class="text-center">{{ $note->order_qty }}</td>
                        <td class="text-end fw-bold text-success">₹{{ number_format($note->mrp, 2) }}</td>
                        <td>{{ $note->delivery_date ? $note->delivery_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="badge badge-dot bg-{{ $note->delivery_type == 'home' ? 'info' : 'secondary' }} me-1"></span>
                            {{ ucfirst($note->delivery_type) }}
                        </td>
                        <td>
                            @php
                                $stages = \App\Models\CommitmentNote::getWorkflowStages();
                                $stage = $stages[$note->workflow_stage] ?? null;
                                $color = $stage['color'] ?? 'secondary';
                            @endphp
                            <span class="badge bg-label-{{ $color }} rounded-pill">
                                {{ $stage['name'] ?? $note->workflow_stage }}
                            </span>
                        </td>
                        <td>{{ Str::limit($note->supplier, 20) }}</td>
                        <td><small class="text-muted">{{ Str::limit($note->comments, 30) }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Import Modern Font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --primary-brand: #4e73df;
        --success-brand: #1cc88a;
        --info-brand: #36b9cc;
        --bg-light: #f8f9fc;
        --border-color: #e3e6f0;
    }

    /* General Polish */
    body { font-family: 'Inter', sans-serif; background-color: #f8f9fc; }
    
    .card { 
        border: none; 
        border-radius: 12px; 
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1) !important; 
    }
    
    .card-header { 
        background-color: #ffffff !important; 
        border-bottom: 1px solid var(--border-color) !important;
        border-radius: 12px 12px 0 0 !important;
    }

    .card-title { 
        font-weight: 700; 
        color: var(--primary-brand) !important; 
        letter-spacing: -0.2px; 
    }

    /* Button Styling */
    .btn { 
        border-radius: 6px; 
        font-weight: 600; 
        transition: all 0.2s ease; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .btn-sm { padding: 0.4rem 0.8rem; }
    .btn-success { background-color: var(--success-brand); border-color: var(--success-brand); }
    .btn-primary { background-color: var(--primary-brand); border-color: var(--primary-brand); }
    .btn-dark { background-color: #5a5c69; border: none; }

    /* Table Sophistication */
    .admin-excel-table { font-size: 0.85rem; }
    
    .admin-excel-table thead th { 
        background-color: var(--bg-light) !important; 
        color: var(--primary-brand) !important; 
        text-transform: none !important; 
        font-size: 0.75rem;
        font-weight: 700 !important;
        border-bottom: 2px solid var(--border-color) !important;
        padding: 0.75rem !important;
    }

    .admin-excel-table td { 
        padding: 0.6rem 0.8rem !important; 
        border-color: #ebedf2 !important; 
        vertical-align: middle !important;
        color: #5a5c69;
    }

    .admin-excel-table tbody tr:hover { 
        background-color: #f1f4ff !important; 
        transition: background-color 0.2s ease; 
    }

    /* Badge Overrides */
    .badge { font-weight: 600; padding: 0.5em 0.8em; }

    /* DataTables Layout Elements */
    .dataTables_length { display: inline-block !important; }
    .goback-wrapper { 
        display: inline-block !important; 
        vertical-align: middle; 
        margin-right: 70px !important; 
    }
    .dataTables_filter { display: inline-block !important; margin-bottom: 0 !important; float: none !important; }
    .dataTables_filter input { 
        border-radius: 8px; 
        border: 1px solid var(--border-color); 
        padding: 0.4rem 0.8rem; 
        background-color: #fff;
    }

    /* Custom Scrollbar */
    .table-responsive::-webkit-scrollbar { height: 8px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }

    /* Print Specific Styles */
    @media print {
        .layout-navbar, 
        .layout-menu, 
        .content-footer, 
        .no-print, 
        .dataTables_filter, 
        .dataTables_length, 
        .dataTables_paginate, 
        .dataTables_info,
        .goback-wrapper {
            display: none !important;
        }
        
        .layout-wrapper, 
        .layout-container, 
        .layout-page, 
        .content-wrapper, 
        .container-xxl {
            padding: 0 !important;
            margin: 0 !important;
            display: block !important;
        }

        .card {
            box-shadow: none !important;
            border: none !important;
        }

        .table-responsive {
            overflow: visible !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .admin-excel-table td, .admin-excel-table th {
            color: #000 !important;
            border: 1px solid #ddd !important;
        }
    }
</style>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#commitmentTable')) {
            $('#commitmentTable').DataTable().destroy();
        }
        
        var table = $('#commitmentTable').DataTable({
            "paging": true,
            "pageLength": 10,
            "ordering": true,
            "info": true,
            "searching": true,
            "order": [[1, "desc"]],
            "dom": "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center'l><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end gap-2'<'goback-wrapper'>f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "language": {
                "search": "Quick Filter:"
            },
            "buttons": [
                {
                    extend: 'excelHtml5',
                    title: 'Commitment_Notes_Export',
                    exportOptions: { columns: ':visible' }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Commitment Notes Report',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: { columns: ':visible' },
                    customize: function (doc) {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    }
                }
            ]
        });

        // Insert Go Back button into the wrapper
        $("div.goback-wrapper").html('<a href="{{ route("admin.commitment-notes.create") }}" class="btn btn-dark btn-sm"><i class="bx bx-undo me-1"></i>Go Back</a>');

        $('#exportExcel').on('click', function() { table.button('.buttons-excel').trigger(); });
        $('#exportPdf').on('click', function() { table.button('.buttons-pdf').trigger(); });

        $('#exportWord').on('click', function() {
            var content = document.getElementById('commitmentTable').outerHTML;
            var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML to Word</title><style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid black; padding: 5px; text-align: left; }</style></head><body>";
            var footer = "</body></html>";
            var sourceHTML = header + content + footer;
            
            var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
            var fileDownload = document.createElement("a");
            document.body.appendChild(fileDownload);
            fileDownload.href = source;
            fileDownload.download = 'Commitment_Notes.doc';
            fileDownload.click();
            document.body.removeChild(fileDownload);
        });
    });
</script>
@endpush