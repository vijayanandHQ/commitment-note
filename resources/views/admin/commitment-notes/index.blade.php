@extends('layouts.sneat')

@section('title', 'Commitment Notes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">
                <i class="bx bx-list-ul"></i> Commitment Notes - Workflow Tracking
            </h5>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('admin.commitment-notes.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add New Commitment Note
                    </a>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-striped table-hover" style="min-width: 1800px; width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 350px; min-width: 350px; position: sticky; left: 0; background-color: #f8f9fa; z-index: 10;">
                                    <i class="bx bx-git-merge"></i> WORKFLOW STAGES <small class="text-muted">(Click to Update)</small>
                                </th>
                                <th style="width: 60px;">S.NO</th>
                                <th style="width: 90px;">DATE</th>
                                <th style="width: 60px;">QTY</th>
                                <th style="width: 150px;">PRODUCT NAME</th>
                                <th style="width: 80px;">MRP</th>
                                <th style="width: 90px;">ORDER QTY</th>
                                <th style="width: 120px;">SUPPLIER</th>
                                <th style="width: 130px;">CUSTOMER PHONE</th>
                                <th style="width: 120px;">CUSTOMER NAME</th>
                                <th style="width: 110px;">DELIVERY DATE</th>
                                <th style="width: 110px;">DELIVERY TYPE</th>
                                <th style="width: 90px;">STATUS</th>
                                <th style="width: 120px;">COMMENTS</th>
                                <th style="width: 100px;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notes as $note)
                            <tr>
                                <!-- Workflow Stages Column - Sticky Left -->
                                <td style="position: sticky; left: 0; background-color: white; z-index: 5; box-shadow: 2px 0 5px -2px rgba(0,0,0,0.1);">
                                    @php
                                        $currentStage = $note->workflow_stage ?? 'pending_supplier';
                                        $stages = [
                                            'pending_supplier' => [
                                                'name' => 'Ask Supplier',
                                                'icon' => 'bx-message-square-dots',
                                                'color' => 'warning',
                                                'bgColor' => '#ffc107',
                                                'order' => 1
                                            ],
                                            'received_from_supplier' => [
                                                'name' => 'Received',
                                                'icon' => 'bx-package',
                                                'color' => 'info',
                                                'bgColor' => '#17a2b8',
                                                'order' => 2
                                            ],
                                            'customer_contacted' => [
                                                'name' => 'Contacted',
                                                'icon' => 'bx-phone-call',
                                                'color' => 'primary',
                                                'bgColor' => '#0d6efd',
                                                'order' => 3
                                            ],
                                            'delivered' => [
                                                'name' => 'Delivered',
                                                'icon' => 'bx-check-circle',
                                                'color' => 'success',
                                                'bgColor' => '#198754',
                                                'order' => 4
                                            ]
                                        ];
                                        
                                        $currentStageOrder = $stages[$currentStage]['order'] ?? 1;
                                        
                                        // Determine which stages are completed (have timestamps)
                                        $completedStages = [];
                                        $stageTimestamps = [];
                                        
                                        if ($note->supplier_asked_at) {
                                            $completedStages[] = 'pending_supplier';
                                            $stageTimestamps['pending_supplier'] = \Carbon\Carbon::parse($note->supplier_asked_at)->format('d/m H:i');
                                        }
                                        if ($note->received_at) {
                                            $completedStages[] = 'received_from_supplier';
                                            $stageTimestamps['received_from_supplier'] = \Carbon\Carbon::parse($note->received_at)->format('d/m H:i');
                                        }
                                        if ($note->customer_contacted_at) {
                                            $completedStages[] = 'customer_contacted';
                                            $stageTimestamps['customer_contacted'] = \Carbon\Carbon::parse($note->customer_contacted_at)->format('d/m H:i');
                                        }
                                        if ($note->delivered_at) {
                                            $completedStages[] = 'delivered';
                                            $stageTimestamps['delivered'] = \Carbon\Carbon::parse($note->delivered_at)->format('d/m H:i');
                                        }
                                        
                                        // Determine next clickable stage
                                        $nextStage = null;
                                        foreach ($stages as $stageKey => $stageData) {
                                            if (!in_array($stageKey, $completedStages)) {
                                                $nextStage = $stageKey;
                                                break;
                                            }
                                        }
                                    @endphp
                                    
                                    <div class="workflow-stages-wrapper">
                                        <div class="workflow-stages">
                                            @foreach($stages as $stageKey => $stageData)
                                                @php
                                                    $isCompleted = in_array($stageKey, $completedStages);
                                                    $isCurrent = $stageKey == $currentStage && !$isCompleted;
                                                    $isClickable = ($stageKey == $nextStage) && !$isCompleted;
                                                    $timestampTime = $stageTimestamps[$stageKey] ?? null;
                                                @endphp
                                                
                                                <div class="workflow-stage-item 
                                                            {{ $stageData['color'] }} 
                                                            {{ $isCompleted ? 'completed' : '' }} 
                                                            {{ $isCurrent ? 'current' : '' }}
                                                            {{ !$isClickable && !$isCompleted && !$isCurrent ? 'locked' : '' }}
                                                            {{ $isClickable ? 'clickable' : '' }}"
                                                     data-stage="{{ $stageKey }}"
                                                     data-note-id="{{ $note->id }}"
                                                     data-bg-color="{{ $stageData['bgColor'] }}"
                                                     onclick="{{ $isClickable ? 'updateStage(' . $note->id . ', \'' . $stageKey . '\')' : '' }}"
                                                     title="{{ $stageData['name'] }} - 
                                                            @if($isCompleted)
                                                                Completed: {{ $timestampTime ?? '' }}
                                                            @elseif($isCurrent)
                                                                Current Stage
                                                            @elseif($isClickable)
                                                                Click to update
                                                            @else
                                                                Locked - Complete previous stage first
                                                            @endif">
                                                    
                                                    <i class="bx {{ $stageData['icon'] }}"></i>
                                                    
                                                    @if($isCompleted)
                                                        <span class="stage-check">✓</span>
                                                        @if($timestampTime)
                                                            <span class="stage-time">{{ $timestampTime }}</span>
                                                        @endif
                                                    @elseif($isCurrent)
                                                        <span class="stage-pulse"></span>
                                                    @endif
                                                </div>
                                                
                                                @if(!$loop->last)
                                                <div class="stage-connector">
                                                    <div class="stage-connector-fill {{ $isCompleted ? 'completed' : '' }}">
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        
                                        <!-- Stage Labels -->
                                        <div class="stage-labels">
                                            @foreach($stages as $stageKey => $stageData)
                                                @php
                                                    $timestamp = $stageTimestamps[$stageKey] ?? null;
                                                @endphp
                                                <div class="stage-label {{ in_array($stageKey, $completedStages) ? 'completed' : '' }} 
                                                            {{ $stageKey == $currentStage && !in_array($stageKey, $completedStages) ? 'current' : '' }}">
                                                    <span class="stage-label-text">{{ $stageData['name'] }}</span>
                                                    @if($timestamp)
                                                        <span class="stage-label-time">{{ substr($timestamp, 0, 5) }}</span>
                                                    @endif
                                                </div>
                                                @if(!$loop->last)
                                                    <div class="stage-label-spacer"></div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Regular Data Columns -->
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $note->date ? \Carbon\Carbon::parse($note->date)->format('d-m-Y') : '-' }}</td>
                                <td>{{ $note->qty ?? 0 }}</td>
                                <td>
                                    <span class="product-name" title="{{ $note->product_name }}">
                                        {{ Str::limit($note->product_name, 20) }}
                                    </span>
                                </td>
                                <td>₹{{ number_format($note->mrp ?? 0, 2) }}</td>
                                <td>{{ $note->order_qty ?? 0 }}</td>
                                <td>
                                    <span class="supplier-name" title="{{ $note->supplier }}">
                                        {{ Str::limit($note->supplier, 15) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="customer-phone">
                                        <i class="bx bx-phone"></i> {{ $note->customer_phone }}
                                    </span>
                                </td>
                                <td>{{ $note->cus_name ?? '-' }}</td>
                                <td>{{ $note->delivery_date ? \Carbon\Carbon::parse($note->delivery_date)->format('d-m-Y') : '-' }}</td>
                                <td>
                                    @if($note->delivery_type)
                                        <span class="badge bg-{{ $note->delivery_type == 'home' ? 'info' : 'secondary' }}">
                                            <i class="bx bx-{{ $note->delivery_type == 'home' ? 'home' : 'building' }}"></i>
                                            {{ ucfirst($note->delivery_type) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Not Set</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $note->status == 'Completed' ? 'success' : ($note->status == 'In Progress' ? 'warning' : ($note->status == 'Cancelled' ? 'danger' : 'secondary')) }}">
                                        {{ $note->status ?? 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    @if($note->comments)
                                        <span class="comments-preview" title="{{ $note->comments }}">
                                            {{ Str::limit($note->comments, 15) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.commitment-notes.show', $note->id) }}" class="btn btn-info" title="View">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('admin.commitment-notes.edit', $note->id) }}" class="btn btn-warning" title="Edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.commitment-notes.destroy', $note->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="15" class="text-center text-muted py-4">
                                    <i class="bx bx-inbox display-4"></i>
                                    <p class="mt-2">No commitment notes found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delivery Type Modal -->
<div class="modal fade" id="deliveryTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-truck"></i> Select Delivery Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Delivery Type</label>
                    <select id="deliveryTypeSelect" class="form-select">
                        <option value="home">🏠 Home Delivery</option>
                        <option value="medical">🏥 Medical Store Pickup</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmStageUpdate()">Confirm</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-js')
<script>
let pendingNoteId = null;
let pendingStage = null;
let isUpdating = false;

function updateStage(noteId, stage) {
    if (isUpdating) return;
    
    // Check if stage is already completed or locked
    const stageElement = event.currentTarget;
    if (stageElement.classList.contains('completed') || stageElement.classList.contains('locked')) {
        showNotification('This stage cannot be updated', 'warning');
        return;
    }
    
    if (stage === 'delivered') {
        pendingNoteId = noteId;
        pendingStage = stage;
        const modal = new bootstrap.Modal(document.getElementById('deliveryTypeModal'));
        modal.show();
        return;
    }
    sendStageUpdate(noteId, stage);
}

function confirmStageUpdate() {
    if (pendingNoteId && pendingStage) {
        const deliveryType = document.getElementById('deliveryTypeSelect').value;
        sendStageUpdate(pendingNoteId, pendingStage, deliveryType);
        bootstrap.Modal.getInstance(document.getElementById('deliveryTypeModal')).hide();
    }
}

function sendStageUpdate(noteId, stage, deliveryType = null) {
    if (isUpdating) return;
    
    isUpdating = true;
    const url = `/admin/commitment-notes/${noteId}/workflow-stage`;
    const clickedElement = event?.currentTarget;
    
    // Show loading state
    if (clickedElement) {
        const originalHtml = clickedElement.innerHTML;
        clickedElement.innerHTML = '<div class="spinner-border spinner-border-sm text-white" role="status"></div>';
        clickedElement.style.pointerEvents = 'none';
    }
    
    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            stage: stage,
            delivery_type: deliveryType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Stage updated successfully!', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Error updating stage', 'error');
            // Reset clicked element
            if (clickedElement) {
                clickedElement.innerHTML = '<i class="bx ' + getIconForStage(stage) + '"></i>';
                clickedElement.style.pointerEvents = 'auto';
            }
            isUpdating = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating stage. Please try again.', 'error');
        // Reset clicked element
        if (clickedElement) {
            clickedElement.innerHTML = '<i class="bx ' + getIconForStage(stage) + '"></i>';
            clickedElement.style.pointerEvents = 'auto';
        }
        isUpdating = false;
    });
}

function getIconForStage(stage) {
    const icons = {
        'pending_supplier': 'bx-message-square-dots',
        'received_from_supplier': 'bx-package',
        'customer_contacted': 'bx-phone-call',
        'delivered': 'bx-check-circle'
    };
    return icons[stage] || 'bx-circle';
}

function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>
@endsection

@section('page-css')
<style>
/* Workflow Stages Wrapper */
.workflow-stages-wrapper {
    background: #ffffff;
    border-radius: 12px;
    padding: 12px 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    min-width: 320px;
}

/* Workflow Stages Container */
.workflow-stages {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 4px;
    position: relative;
    margin-bottom: 8px;
}

/* Stage Items */
.workflow-stage-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    position: relative;
    z-index: 2;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Stage Colors - Vibrant and Distinct */
.workflow-stage-item.warning {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    border: 2px solid #ffb300;
}

.workflow-stage-item.info {
    background: linear-gradient(135deg, #17a2b8, #138496);
    border: 2px solid #138496;
}

.workflow-stage-item.primary {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    border: 2px solid #0b5ed7;
}

.workflow-stage-item.success {
    background: linear-gradient(135deg, #198754, #157347);
    border: 2px solid #157347;
}

/* Completed Stage - Brighter */
.workflow-stage-item.completed {
    filter: brightness(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Current Stage - Pulsing */
.workflow-stage-item.current {
    transform: scale(1.15);
    box-shadow: 0 0 0 4px rgba(255,255,255,0.8), 0 8px 16px rgba(0,0,0,0.2);
    animation: currentPulse 2s infinite;
}

/* Locked Stage - Grayed Out */
.workflow-stage-item.locked {
    filter: grayscale(80%) opacity(0.5);
    cursor: not-allowed;
    box-shadow: none;
}

/* Clickable Stage - Interactive */
.workflow-stage-item.clickable {
    cursor: pointer;
    animation: clickablePulse 1.5s infinite;
}

.workflow-stage-item.clickable:hover {
    transform: scale(1.2);
    filter: brightness(1.2);
    box-shadow: 0 6px 16px rgba(0,0,0,0.25);
}

/* Stage Icons */
.workflow-stage-item i {
    font-size: 22px;
    color: white;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Completed Check Mark */
.workflow-stage-item .stage-check {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    font-weight: bold;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    z-index: 3;
}

/* Stage Time */
.workflow-stage-item .stage-time {
    position: absolute;
    bottom: -18px;
    font-size: 9px;
    font-weight: bold;
    color: #495057;
    background: white;
    padding: 2px 6px;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    white-space: nowrap;
    border: 1px solid #dee2e6;
    z-index: 3;
}

/* Stage Pulse Animation */
.workflow-stage-item .stage-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: inherit;
    opacity: 0.6;
    animation: pulse-ring 1.5s infinite;
}

/* Stage Connectors */
.stage-connector {
    flex: 1;
    height: 3px;
    background: #e9ecef;
    position: relative;
    margin: 0 2px;
    border-radius: 4px;
    overflow: hidden;
}

.stage-connector-fill {
    height: 100%;
    width: 0;
    background: linear-gradient(90deg, #28a745, #20c997);
    transition: width 0.5s ease;
}

.stage-connector-fill.completed {
    width: 100%;
}

/* Stage Labels */
.stage-labels {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-top: 12px;
    padding: 0 2px;
}

.stage-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    font-size: 10px;
    font-weight: 600;
    color: #6c757d;
    transition: all 0.3s ease;
    flex: 1;
    max-width: 70px;
}

.stage-label.completed {
    color: #28a745;
}

.stage-label.current {
    color: #0d6efd;
    font-weight: 700;
}

.stage-label-text {
    display: block;
    line-height: 1.2;
    margin-bottom: 2px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.stage-label-time {
    font-size: 8px;
    color: #adb5bd;
    background: #f8f9fa;
    padding: 2px 4px;
    border-radius: 10px;
    white-space: nowrap;
}

.stage-label-spacer {
    flex: 1;
    max-width: 10px;
}

/* Table Column Styles */
.product-name, .supplier-name, .customer-phone {
    display: block;
    max-width: 150px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.customer-phone i {
    font-size: 12px;
    margin-right: 4px;
    color: #6c757d;
}

.comments-preview {
    display: block;
    max-width: 120px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #6c757d;
    font-style: italic;
}

/* Sticky Header */
.table thead th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 20;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Ensure table doesn't break */
.table {
    border-collapse: separate;
    border-spacing: 0;
}

.table td, .table th {
    white-space: nowrap;
    vertical-align: middle;
}

/* Animations */
@keyframes currentPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255,255,255,0.8), 0 0 0 4px rgba(13,110,253,0.3);
    }
    70% {
        box-shadow: 0 0 0 8px rgba(255,255,255,0), 0 0 0 6px rgba(13,110,253,0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255,255,255,0), 0 0 0 4px rgba(13,110,253,0);
    }
}

@keyframes clickablePulse {
    0% {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    50% {
        box-shadow: 0 6px 16px rgba(0,0,0,0.25), 0 0 0 3px rgba(255,255,255,0.5);
    }
    100% {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
}

@keyframes pulse-ring {
    0% {
        transform: scale(1);
        opacity: 0.6;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

/* Notifications */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 16px 24px;
    border-radius: 12px;
    background: white;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    transform: translateX(400px);
    transition: transform 0.3s ease;
    z-index: 9999;
    border-left: 4px solid;
    max-width: 350px;
}

.notification.show {
    transform: translateX(0);
}

.notification-success {
    border-left-color: #28a745;
}

.notification-success i {
    color: #28a745;
}

.notification-warning {
    border-left-color: #ffc107;
}

.notification-warning i {
    color: #ffc107;
}

.notification-error {
    border-left-color: #dc3545;
}

.notification-error i {
    color: #dc3545;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.notification-content i {
    font-size: 24px;
}

.notification-content span {
    font-size: 14px;
    font-weight: 500;
    color: #212529;
}

/* Responsive */
@media (max-width: 1400px) {
    .workflow-stage-item {
        width: 42px;
        height: 42px;
    }
    
    .workflow-stage-item i {
        font-size: 18px;
    }
    
    .stage-label-text {
        font-size: 9px;
    }
}

@media (max-width: 1200px) {
    .workflow-stages-wrapper {
        min-width: 280px;
    }
    
    .stage-time {
        display: none;
    }
}
</style>
@endsection