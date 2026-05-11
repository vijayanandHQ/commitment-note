<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Staff;
use App\Models\StaffTask;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['staffTasks.staff', 'staffs']);
        
        // Sorting logic
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        
        // Define allowed sort columns
        $allowedSorts = ['title', 'amount', 'due_date', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Paginate the results
        $tasks = $query->paginate(15); // Show 15 tasks per page
        
        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $staffs = Staff::where('is_active', true)->get();
        return view('admin.tasks.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'requires_proof' => 'boolean',
            'assigned_staff' => 'nullable|array',
            'assigned_staff.*' => 'exists:staff,id',
        ]);

        $requiresProof = $request->has('requires_proof');
        $assignedStaff = $request->input('assigned_staff', []);

        $task = Task::create(array_merge($validated, ['requires_proof' => $requiresProof]));

        // Assign task to selected staff
        foreach ($assignedStaff as $staffId) {
            StaffTask::firstOrCreate([
                'staff_id' => $staffId,
                'task_id' => $task->id,
            ], [
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);
        }

        return redirect()->route('admin.tasks.index')
                         ->with('success', 'Task created and assigned successfully!');
    }

    public function show(Task $task)
    {
        $task->load('staffTasks.staff');
        return view('admin.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $staffs = Staff::where('is_active', true)->get();
        $assignedStaffIds = $task->staffs->pluck('id')->toArray();
        return view('admin.tasks.edit', compact('task', 'staffs', 'assignedStaffIds'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'requires_proof' => 'boolean',
            'assigned_staff' => 'nullable|array',
            'assigned_staff.*' => 'exists:staff,id',
        ]);

        $requiresProof = $request->has('requires_proof');
        $assignedStaff = $request->input('assigned_staff', []);

        $task->update(array_merge($validated, ['requires_proof' => $requiresProof]));

        // Clear existing assignments
        $task->staffTasks()->delete();

        // Create new assignments
        foreach ($assignedStaff as $staffId) {
            StaffTask::create([
                'staff_id' => $staffId,
                'task_id' => $task->id,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);
        }

        return redirect()->route('admin.tasks.index')
                         ->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->staffTasks()->delete();
        $task->delete();

        return redirect()->route('admin.tasks.index')
                         ->with('success', 'Task deleted successfully!');
    }
}