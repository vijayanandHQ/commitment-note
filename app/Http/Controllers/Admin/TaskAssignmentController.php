<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Staff;
use App\Models\StaffTask;
use Illuminate\Http\Request;

class TaskAssignmentController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('title')->get();
        $staffs = Staff::orderBy('name')->get();
        
        // Get all assignments with relationships
        $assignments = StaffTask::with(['staff', 'task'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.task-assignments.index', compact('tasks', 'staffs', 'assignments'));
    }

    public function store(Request $request)
{
    $request->validate([
        'task_ids' => 'required|array|min:1',
        'task_ids.*' => 'exists:tasks,id',
        'staff_ids' => 'required|array|min:1',
        'staff_ids.*' => 'exists:staffs,id' // Using your table name 'staffs'
    ]);

    // Handle both array format and JSON string format
    $taskIds = $request->input('task_ids');
    $staffIds = $request->input('staff_ids');

    // If they come as JSON strings, decode them
    if (is_string($taskIds)) {
        $taskIds = json_decode($taskIds, true) ?: [];
    }
    if (is_string($staffIds)) {
        $staffIds = json_decode($staffIds, true) ?: [];
    }

    // If they come as single values in arrays, extract them
    if (is_array($taskIds) && isset($taskIds[0]) && is_string($taskIds[0])) {
        $decodedTaskIds = json_decode($taskIds[0], true);
        if (is_array($decodedTaskIds)) {
            $taskIds = $decodedTaskIds;
        }
    }
    if (is_array($staffIds) && isset($staffIds[0]) && is_string($staffIds[0])) {
        $decodedStaffIds = json_decode($staffIds[0], true);
        if (is_array($decodedStaffIds)) {
            $staffIds = $decodedStaffIds;
        }
    }

    // Ensure they are arrays before filtering
    if (!is_array($taskIds)) {
        $taskIds = [];
    }
    if (!is_array($staffIds)) {
        $staffIds = [];
    }

    // Convert to integers and remove duplicates
    $taskIds = array_map('intval', array_filter($taskIds));
    $staffIds = array_map('intval', array_filter($staffIds));

    // Validate that we have valid IDs
    if (empty($taskIds) || empty($staffIds)) {
        return redirect()->back()->withErrors(['error' => 'Invalid task or staff IDs selected.']);
    }

    $assignedCount = 0;
    foreach ($staffIds as $staffId) {
        foreach ($taskIds as $taskId) {
            // Avoid duplicate assignments
            $assignment = StaffTask::firstOrCreate([
                'staff_id' => $staffId,
                'task_id' => $taskId
            ], [
                'status' => 'assigned',
                'assigned_at' => now()
            ]);

            if ($assignment->wasRecentlyCreated) {
                $assignedCount++;
            }
        }
    }

    return redirect()->route('admin.task-assignments.index')
        ->with('success', 'Successfully assigned ' . $assignedCount . ' task(s)! ' . count($taskIds) . ' tasks to ' . count($staffIds) . ' staff members.');
}

    public function show($id)
    {
        $assignment = StaffTask::with(['staff', 'task'])->findOrFail($id);
        return view('admin.task-assignments.show', compact('assignment'));
    }

    public function edit($id)
    {
        $assignment = StaffTask::with(['staff', 'task'])->findOrFail($id);
        $statuses = ['assigned', 'in_progress', 'completed', 'rejected'];
        
        return view('admin.task-assignments.edit', compact('assignment', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $assignment = StaffTask::findOrFail($id);

        $request->validate([
            'status' => 'required|in:assigned,in_progress,completed,rejected',
            'notes' => 'nullable|string'
        ]);

        $oldStatus = $assignment->status;
        $assignment->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'completed_at' => $request->status === 'completed' ? now() : $assignment->completed_at,
            'started_at' => $request->status === 'in_progress' && !$assignment->started_at ? now() : $assignment->started_at
        ]);

        // Update staff balance when task is completed
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            $assignment->staff->increment('balance', $assignment->task->amount);
            
            // Show appropriate message based on whether it's a bonus or penalty
            $amount = abs(number_format($assignment->task->amount, 2));
            if ($assignment->task->amount > 0) {
                $message = "Task completed! ₹{$amount} added to {$assignment->staff->name}'s balance.";
            } else {
                $message = "Task completed! ₹{$amount} deducted from {$assignment->staff->name}'s balance as penalty.";
            }
            
            return redirect()->route('admin.task-assignments.index')
                ->with('success', $message);
        }

        return redirect()->route('admin.task-assignments.index')
            ->with('success', 'Assignment status updated successfully!');
    }

    public function destroy($id)
    {
        $assignment = StaffTask::with(['staff', 'task'])->findOrFail($id);
        
        // If task was completed, reverse the balance change
        if ($assignment->status === 'completed') {
            $assignment->staff->decrement('balance', $assignment->task->amount);
        }
        
        $assignment->delete();
        
        return redirect()->route('admin.task-assignments.index')
            ->with('success', 'Assignment removed successfully!');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'assignment_ids' => 'required|array|min:1',
            'assignment_ids.*' => 'exists:staff_tasks,id',
            'status' => 'required|in:assigned,in_progress,completed,rejected',
            'notes' => 'nullable|string'
        ]);

        $assignments = StaffTask::whereIn('id', $request->assignment_ids)->get();

        foreach ($assignments as $assignment) {
            $oldStatus = $assignment->status;
            $assignment->update([
                'status' => $request->status,
                'notes' => $request->notes,
                'completed_at' => $request->status === 'completed' ? now() : $assignment->completed_at,
                'started_at' => $request->status === 'in_progress' && !$assignment->started_at ? now() : $assignment->started_at
            ]);

            // Update balance if task is completed
            if ($request->status === 'completed' && $oldStatus !== 'completed') {
                $assignment->staff->increment('balance', $assignment->task->amount);
            }
        }

        return redirect()->route('admin.task-assignments.index')
            ->with('success', count($assignments) . ' assignments updated successfully!');
    }


    public function getStaffTasks($id)
{
    $staff = Staff::with(['tasks' => function($query) {
        $query->withTrashed(); // Include soft-deleted tasks if needed
    }])->findOrFail($id);

    return response()->json([
        'name' => $staff->name,
        'position' => $staff->position,
        'email' => $staff->email,
        'phone' => $staff->phone ?? 'N/A',
        'balance' => (float) $staff->balance,
        'created_at' => $staff->created_at->format('d-m-Y'),
        'tasks' => $staff->tasks->map(function($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'amount' => (float) $task->amount,
                'pivot' => [
                    'id' => $task->pivot->id,
                    'status' => $task->pivot->status,
                    'assigned_at' => $task->pivot->assigned_at ? $task->pivot->assigned_at->toDateTimeString() : now()->toDateTimeString(),
                    'started_at' => $task->pivot->started_at ? $task->pivot->started_at->toDateTimeString() : null,
                    'completed_at' => $task->pivot->completed_at ? $task->pivot->completed_at->toDateTimeString() : null,
                    'notes' => $task->pivot->notes ?? '',
                    'proof_description' => $task->pivot->proof_description ?? '',
                    'proof_photos' => $task->pivot->proof_photos ?? []
                ]
            ];
        })
    ]);
}
}