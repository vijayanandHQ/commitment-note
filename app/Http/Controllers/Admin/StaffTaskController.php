<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffTask;
use Illuminate\Http\Request;

class StaffTaskController extends Controller
{
    public function index()
    {
        $staffTasks = StaffTask::with(['staff', 'task'])->orderBy('created_at', 'desc')->get();
        return view('admin.staff-tasks.index', compact('staffTasks'));
    }

    public function show(StaffTask $staffTask)
    {
        return view('admin.staff-tasks.show', compact('staffTask'));
    }

    public function edit(StaffTask $staffTask)
    {
        return view('admin.staff-tasks.edit', compact('staffTask'));
    }

    public function update(Request $request, StaffTask $staffTask)
    {
        $validated = $request->validate([
            'status' => 'required|in:assigned,in_progress,completed,rejected',
            'proof_description' => 'nullable|string',
            'proof_photos' => 'nullable|array',
            'proof_photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('proof_photos')) {
            $photoPaths = [];
            foreach ($request->file('proof_photos') as $photo) {
                $photoPaths[] = $photo->store('task_proofs', 'public');
            }
            $validated['proof_photos'] = $photoPaths;
        }

        $oldStatus = $staffTask->status;
        $staffTask->update($validated);

        // If task is completed and was not previously completed, add amount to staff balance
        if ($staffTask->status === 'completed' && $oldStatus !== 'completed') {
            $staffTask->staff->increment('balance', $staffTask->task->amount);
        }

        return redirect()->route('admin.staff-tasks.index')
                         ->with('success', 'Staff task updated successfully!');
    }

    public function completeWithProof(Request $request, StaffTask $staffTask)
    {
        $request->validate([
            'proof_description' => 'nullable|string',
            'proof_photos' => 'required|array|min:1',
            'proof_photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPaths = [];
        foreach ($request->file('proof_photos') as $photo) {
            $photoPaths[] = $photo->store('task_proofs', 'public');
        }

        $staffTask->completeWithProof($photoPaths, $request->input('proof_description'));

        return redirect()->route('admin.staff-tasks.index')
                         ->with('success', 'Task completed and amount added to staff balance!');
    }

    public function myTasks(Request $request)
{
    $staff = auth()->user();
    $staffModel = \App\Models\Staff::where('email', $staff->email)->first();
    
    if (!$staffModel) {
        return redirect()->route('admin.dashboard');
    }
    
    $statusFilter = $request->query('status', 'all'); // all, assigned, in_progress, completed
    
    $query = $staffModel->staffTasks()->with('task');
    
    if ($statusFilter !== 'all') {
        $query->where('status', $statusFilter);
    }
    
    $staffTasks = $query->orderBy('created_at', 'desc')->get();
    
    return view('staff.tasks.index', compact('staffTasks', 'statusFilter'));
}

public function completeTask(Request $request, StaffTask $staffTask)
{
    $staff = auth()->user();
    $staffModel = \App\Models\Staff::where('email', $staff->email)->first();
    
    if (!$staffModel || $staffTask->staff_id !== $staffModel->id) {
        return redirect()->route('staff.my-tasks')->with('error', 'Unauthorized access.');
    }
    
    $request->validate([
        'proof_description' => 'nullable|string',
        'proof_photos' => 'required|array|min:1',
        'proof_photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    $photoPaths = [];
    foreach ($request->file('proof_photos') as $photo) {
        $photoPaths[] = $photo->store('task_proofs', 'public');
    }
    
    $staffTask->update([
        'status' => 'completed',
        'completed_at' => now(),
        'proof_photos' => $photoPaths,
        'proof_description' => $request->input('proof_description'),
    ]);
    
    // Add amount to staff balance
    $staffModel->increment('balance', $staffTask->task->amount);
    
    return redirect()->route('staff.my-tasks')
                     ->with('success', 'Task completed successfully! ₹' . number_format($staffTask->task->amount, 2) . ' added to your wallet.');
}
public function completeTaskForm(StaffTask $staffTask)
{
    $staff = auth()->user();
    $staffModel = \App\Models\Staff::where('email', $staff->email)->first();
    
    if (!$staffModel || $staffTask->staff_id !== $staffModel->id) {
        return redirect()->route('staff.my-tasks')->with('error', 'Unauthorized access.');
    }
    
    return view('staff.tasks.complete-form', compact('staffTask'));
}
}