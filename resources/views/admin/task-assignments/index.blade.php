@extends('layouts.sneat')

@section('title', 'Assign Tasks to Staff')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <h5 class="card-header">Available Tasks</h5>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="selectAllTasks" onchange="toggleAllTasks()">
                    <label class="form-check-label fw-bold" for="selectAllTasks">
                        Select All Tasks ({{ $tasks->count() }} available)
                    </label>
                </div>
                
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Title</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                            <tr>
                                <td>
                                    <input class="form-check-input task-checkbox" 
                                           type="checkbox" 
                                           name="task_ids[]" 
                                           value="{{ $task->id }}" 
                                           id="task_{{ $task->id }}">
                                </td>
                                <td>
                                    <label for="task_{{ $task->id }}" class="form-check-label">
                                        {{ $task->title }}
                                    </label>
                                </td>
                                <td>
                                    <span class="{{ $task->amount < 0 ? 'text-danger' : 'text-success' }}">
                                        ₹{{ number_format(abs($task->amount), 2) }}
                                        @if($task->amount < 0) <small>(Penalty)</small> @endif
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-3">
                                    <i class="bx bx-task bx-lg text-muted"></i>
                                    <p class="mt-2 mb-0">No tasks available</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <h5 class="card-header">Available Staff</h5>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="selectAllStaff" onchange="toggleAllStaff()">
                    <label class="form-check-label fw-bold" for="selectAllStaff">
                        Select All Staff ({{ $staffs->count() }} available)
                    </label>
                </div>
                
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffs as $staff)
                            <tr>
                                <td>
                                    <input class="form-check-input staff-checkbox" 
                                           type="checkbox" 
                                           name="staff_ids[]" 
                                           value="{{ $staff->id }}" 
                                           id="staff_{{ $staff->id }}">
                                </td>
                                <td>
                                    <label for="staff_{{ $staff->id }}" class="form-check-label">
                                        {{ $staff->name }}
                                    </label>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: 
                                        @if($staff->position == 'Field Executive') #007BFF
                                        @elseif($staff->position == 'Sales Manager') #28A745
                                        @elseif($staff->position == 'Field Worker') #FFC107
                                        @elseif($staff->position == 'Admin') #6F42C1
                                        @else #6c757d
                                        @endif; color: white; font-size: 0.75rem;">
                                        {{ $staff->position ?? 'Staff' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" 
                                            onclick="viewStaffTasks({{ $staff->id }})"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#staffTasksModal">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">
                                    <i class="bx bx-user bx-lg text-muted"></i>
                                    <p class="mt-2 mb-0">No staff available</p>
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

<!-- Assign Button Section -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <button type="button" class="btn btn-primary btn-lg" onclick="assignTasks()" id="assignButton" disabled>
                    <i class="bx bx-task"></i> Assign Selected Tasks to Selected Staff
                </button>
                <div class="mt-2">
                    <small class="text-muted">Select tasks from left and staff from right to enable assignment</small>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Staff Tasks Modal -->
<div class="modal fade" id="staffTasksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staffTasksModalLabel">Staff Tasks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="staff-tasks-content">
                    <!-- Loading spinner will appear here initially -->
                    <div class="text-center p-4">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading staff tasks...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Assignment Modal -->
<div class="modal fade" id="editAssignmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAssignmentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="assigned">Assigned</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="assignmentForm" action="{{ route('admin.task-assignments.store') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="task_ids[]" id="taskIdsInput">
    <input type="hidden" name="staff_ids[]" id="staffIdsInput">
</form>

<script>
let selectedTasks = [];
let selectedStaff = [];
let currentSortColumn = null;
let currentSortDirection = 'asc'; // asc or desc

// Task selection functions
function toggleAllTasks() {
    const selectAllCheckbox = document.getElementById('selectAllTasks');
    const checkboxes = document.querySelectorAll('.task-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
        updateSelection(checkbox, 'task');
    });
    
    updateAssignButton();
}

function toggleAllStaff() {
    const selectAllCheckbox = document.getElementById('selectAllStaff');
    const checkboxes = document.querySelectorAll('.staff-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
        updateSelection(checkbox, 'staff');
    });
    
    updateAssignButton();
}

function updateSelection(checkbox, type) {
    const value = parseInt(checkbox.value);
    
    if (checkbox.checked) {
        if (type === 'task' && !selectedTasks.includes(value)) {
            selectedTasks.push(value);
        } else if (type === 'staff' && !selectedStaff.includes(value)) {
            selectedStaff.push(value);
        }
    } else {
        if (type === 'task') {
            selectedTasks = selectedTasks.filter(id => id !== value);
        } else if (type === 'staff') {
            selectedStaff = selectedStaff.filter(id => id !== value);
        }
    }
}

// Sorting functions
function initializeSorting() {
    const sortHeaders = document.querySelectorAll('#staff-tasks-content thead th');
    sortHeaders.forEach((header, index) => {
        if (header.querySelector('i.bx-sort')) {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                const columnName = header.textContent.trim().toLowerCase().replace(/\s/g, '_');
                sortTable(columnName, index);
            });
        }
    });
}

function sortTable(columnName, columnIndex) {
    // Toggle sort direction if clicking same column, otherwise reset to ascending
    if (currentSortColumn === columnIndex) {
        currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortDirection = 'asc';
        currentSortColumn = columnIndex;
    }

    // Get the table body and rows
    const table = document.querySelector('#staff-tasks-content table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Remove any existing sort indicators
    document.querySelectorAll('#staff-tasks-content thead th i.bx-sort').forEach(icon => {
        icon.className = 'bx bx-sort';
    });

    // Add sort indicator to current column
    const currentHeader = document.querySelectorAll('#staff-tasks-content thead th')[columnIndex];
    if (currentHeader) {
        const sortIcon = currentHeader.querySelector('i.bx-sort');
        if (sortIcon) {
            sortIcon.className = currentSortDirection === 'asc' ? 'bx bx-sort-up' : 'bx bx-sort-down';
        }
    }

    // Sort the rows
    rows.sort((a, b) => {
        let aValue = '';
        let bValue = '';
        
        const aCells = a.querySelectorAll('td');
        const bCells = b.querySelectorAll('td');
        
        if (aCells[columnIndex] && bCells[columnIndex]) {
            aValue = aCells[columnIndex].textContent.trim();
            bValue = bCells[columnIndex].textContent.trim();
            
            // Handle special cases
            if (columnName.includes('amount')) {
                // Extract numeric value from amount (remove ₹ and other characters)
                aValue = parseFloat(aValue.replace(/[^\d.-]/g, '')) || 0;
                bValue = parseFloat(bValue.replace(/[^\d.-]/g, '')) || 0;
            } else if (columnName.includes('date')) {
                // Convert to date for comparison
                aValue = new Date(aValue);
                bValue = new Date(bValue);
            } else if (columnName.includes('status')) {
                // Use status priority for sorting
                aValue = getStatusPriority(aValue.toLowerCase());
                bValue = getStatusPriority(bValue.toLowerCase());
            } else if (columnName.includes('task_name')) {
                // For task name, get the actual task title from the inner strong element
                const aTitle = aCells[columnIndex].querySelector('strong');
                const bTitle = bCells[columnIndex].querySelector('strong');
                aValue = aTitle ? aTitle.textContent.trim() : aValue;
                bValue = bTitle ? bTitle.textContent.trim() : bValue;
            }
        }
        
        // Compare values
        let comparison = 0;
        if (typeof aValue === 'number' && typeof bValue === 'number') {
            comparison = aValue - bValue;
        } else if (aValue instanceof Date && bValue instanceof Date) {
            comparison = aValue - bValue;
        } else {
            comparison = aValue.toString().localeCompare(bValue.toString());
        }
        
        return currentSortDirection === 'asc' ? comparison : -comparison;
    });

    // Re-append sorted rows to tbody
    rows.forEach(row => tbody.appendChild(row));
}

function getStatusPriority(status) {
    const priorityMap = {
        'completed': 1,
        'in_progress': 2,
        'assigned': 3,
        'rejected': 4,
        'pending': 5,
        'overdue': 6,
        'cancelled': 7
    };
    return priorityMap[status.toLowerCase()] || 8; // Default priority for unknown statuses
}

// Assign button functions
function updateAssignButton() {
    const assignButton = document.getElementById('assignButton');
    const selectAllTasks = document.getElementById('selectAllTasks');
    const selectAllStaff = document.getElementById('selectAllStaff');
    
    // Update select all checkboxes state
    const taskCheckboxes = document.querySelectorAll('.task-checkbox');
    const staffCheckboxes = document.querySelectorAll('.staff-checkbox');
    
    const checkedTasks = Array.from(taskCheckboxes).filter(cb => cb.checked).length;
    const checkedStaff = Array.from(staffCheckboxes).filter(cb => cb.checked).length;
    
    selectAllTasks.checked = checkedTasks === taskCheckboxes.length && taskCheckboxes.length > 0;
    selectAllStaff.checked = checkedStaff === staffCheckboxes.length && staffCheckboxes.length > 0;
    
    // Enable/disable assign button
    assignButton.disabled = selectedTasks.length === 0 || selectedStaff.length === 0;
    
    if (!assignButton.disabled) {
        assignButton.innerHTML = `<i class="bx bx-task"></i> Assign ${selectedTasks.length} Task(s) to ${selectedStaff.length} Staff Member(s)`;
    } else {
        assignButton.innerHTML = '<i class="bx bx-task"></i> Assign Selected Tasks to Selected Staff';
    }
}

function assignTasks() {
    if (selectedTasks.length === 0 || selectedStaff.length === 0) {
        alert('Please select at least one task and one staff member.');
        return;
    }
    
    if (confirm(`Are you sure you want to assign ${selectedTasks.length} task(s) to ${selectedStaff.length} staff member(s)?`)) {
        // Clear existing hidden inputs
        const existingTaskInputs = document.querySelectorAll('#assignmentForm input[name="task_ids[]"]');
        const existingStaffInputs = document.querySelectorAll('#assignmentForm input[name="staff_ids[]"]');
        
        existingTaskInputs.forEach(input => input.remove());
        existingStaffInputs.forEach(input => input.remove());
        
        // Create new inputs for each selected ID
        const assignmentForm = document.getElementById('assignmentForm');
        
        // Add task IDs as separate inputs
        selectedTasks.forEach(taskId => {
            const taskInput = document.createElement('input');
            taskInput.type = 'hidden';
            taskInput.name = 'task_ids[]';
            taskInput.value = taskId;
            assignmentForm.appendChild(taskInput);
        });
        
        // Add staff IDs as separate inputs
        selectedStaff.forEach(staffId => {
            const staffInput = document.createElement('input');
            staffInput.type = 'hidden';
            staffInput.name = 'staff_ids[]';
            staffInput.value = staffId;
            assignmentForm.appendChild(staffInput);
        });
        
        assignmentForm.submit();
    }
}

// Helper functions
function escapeHtml(text) {
    if (typeof text !== 'string') {
        text = String(text);
    }
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function getStatusClass(status) {
    const statusMap = {
        'pending': 'warning',
        'completed': 'success',
        'in_progress': 'info',
        'cancelled': 'secondary',
        'overdue': 'danger',
        'rejected': 'danger',
        'assigned': 'primary'
    };
    return statusMap[status] || 'secondary';
}

// Main function to view staff tasks
async function viewStaffTasks(staffId) {
    try {
        const response = await fetch(`/admin/staff/${staffId}/tasks`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        // Safely handle balance - convert to number and provide default
        const balance = parseFloat(data.balance) || 0;
        
        let content = `
            <!-- Staff Profile Section -->
            <div class="staff-profile-card">
                <div class="profile-header">
                    <h4 class="mb-3">${escapeHtml(data.name)}</h4>
                </div>
                <div class="profile-details">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Position:</label>
                            <p class="mb-1">${escapeHtml(data.position)}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Email:</label>
                            <p class="mb-1">${escapeHtml(data.email)}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Phone:</label>
                            <p class="mb-1">${data.phone || 'N/A'}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Balance:</label>
                            <p class="mb-1 text-success fw-bold">₹${balance.toFixed(2)}</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Joined:</label>
                            <p class="mb-1">${new Date(data.created_at).toLocaleDateString('en-US', { 
                                year: 'numeric', 
                                month: 'short', 
                                day: 'numeric' 
                            })}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collapsible Task Section -->
            <div class="task-section mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <button class="btn btn-link p-0 text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#tasksCollapse" aria-expanded="true">
                            <i class="bx bx-chevron-down me-2"></i>
                            Assigned Tasks
                        </button>
                    </h5>
                    <span class="badge bg-primary">${data.tasks ? data.tasks.length : 0} tasks</span>
                </div>
                
                <div class="collapse show" id="tasksCollapse">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%;">Task Name <i class="bx bx-sort"></i></th>
                                    <th style="width: 15%;">Amount <i class="bx bx-sort"></i></th>
                                    <th style="width: 15%;">Status <i class="bx bx-sort"></i></th>
                                    <th style="width: 20%;">Assigned Date <i class="bx bx-sort"></i></th>
                                    <th style="width: 10%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
        `;
        
        if (data.tasks && data.tasks.length > 0) {
            data.tasks.forEach(task => {
                const amount = parseFloat(task.amount) || 0;
                const assignedAt = task.pivot.assigned_at ? new Date(task.pivot.assigned_at) : new Date();
                
                content += `
                    <tr>
                        <td>
                            <div class="task-info">
                                <strong>${escapeHtml(task.title)}</strong>
                                ${task.pivot.notes ? `<small class="text-muted d-block">${escapeHtml(task.pivot.notes)}</small>` : ''}
                            </div>
                        </td>
                        <td>
                            <span class="${amount < 0 ? 'text-danger' : 'text-success'} fw-bold">
                                ${amount < 0 ? '-' : '+'}₹${Math.abs(amount).toFixed(2)}
                                ${amount < 0 ? ' (Penalty)' : ''}
                            </span>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-label-${getStatusClass(task.pivot.status)}">
                                ${escapeHtml(task.pivot.status.replace('_', ' ').toUpperCase())}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">
                                ${assignedAt.toLocaleDateString('en-US', { 
                                    month: 'short', 
                                    day: 'numeric', 
                                    year: 'numeric' 
                                })}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" 
                                    title="Edit Assignment"
                                    onclick="openEditAssignment(${parseInt(task.pivot.id || 0)}, '${escapeHtml(task.pivot.status)}', '${escapeHtml(task.pivot.notes || '')}')">
                                <i class="bx bx-edit"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        } else {
            content += `
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <i class="bx bx-task bx-lg text-muted mb-2"></i>
                        <p class="text-muted mb-0">No tasks assigned yet</p>
                    </td>
                </tr>
            `;
        }
        
        content += `
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('staff-tasks-content').innerHTML = content;
        
        // Initialize sorting after content is loaded
        setTimeout(initializeSorting, 100);
    } catch (error) {
        console.error('Error loading staff tasks:', error);
        document.getElementById('staff-tasks-content').innerHTML = 
            '<div class="alert alert-danger">Error loading staff tasks. Please check browser console for details.</div>';
    }
}

// Function to open edit assignment modal
function openEditAssignment(assignmentId, currentStatus, currentNotes) {
    // Set form action to correct route
    document.getElementById('editAssignmentForm').action = `/admin/task-assignments/${assignmentId}`;
    
    // Set current values
    document.querySelector('#editAssignmentForm select[name="status"]').value = currentStatus;
    document.querySelector('#editAssignmentForm textarea[name="notes"]').value = currentNotes || '';
    
    // Hide the main modal and show the edit modal
    const staffModal = bootstrap.Modal.getInstance(document.getElementById('staffTasksModal'));
    if (staffModal) {
        staffModal.hide();
    }
    
    setTimeout(() => {
        const editModal = new bootstrap.Modal(document.getElementById('editAssignmentModal'));
        editModal.show();
    }, 100);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to task checkboxes
    const taskCheckboxes = document.querySelectorAll('.task-checkbox');
    const staffCheckboxes = document.querySelectorAll('.staff-checkbox');
    
    taskCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelection(this, 'task');
            updateAssignButton();
        });
    });
    
    staffCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelection(this, 'staff');
            updateAssignButton();
        });
    });
    
    // Edit assignment form submission
    const editAssignmentForm = document.getElementById('editAssignmentForm');
    if (editAssignmentForm) {
        editAssignmentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const action = this.action;
            
            try {
                const response = await fetch(action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-HTTP-Method-Override': 'PUT'
                    }
                });
                
                if (response.ok) {
                    const result = await response.json();
                    alert(result.message || 'Assignment updated successfully!');
                    
                    // Close edit modal
                    bootstrap.Modal.getInstance(document.getElementById('editAssignmentModal')).hide();
                    
                    // Refresh the staff tasks content
                    const staffId = document.querySelector('.btn-outline-info[data-bs-toggle="modal"]')?.closest('tr')?.querySelector('input[type="checkbox"]')?.value;
                    if (staffId) {
                        viewStaffTasks(staffId);
                    }
                } else {
                    const error = await response.json();
                    alert(error.message || 'Error updating assignment');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating the assignment');
            }
        });
    }
    
    // Reset modal content when closed
    const staffTasksModal = document.getElementById('staffTasksModal');
    if (staffTasksModal) {
        staffTasksModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('staff-tasks-content').innerHTML = `
                <div class="text-center p-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading staff tasks...</p>
                </div>
            `;
            // Reset sorting variables
            currentSortColumn = null;
            currentSortDirection = 'asc';
        });
    }
});
</script>
@endsection