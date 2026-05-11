<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffTask;
use App\Models\Staff as StaffModel;
use Illuminate\Http\Request;

class StaffTaskController extends Controller
{
    public function myTasks(Request $request)
    {
        $user = auth()->user();
        $staff = StaffModel::where('email', $user->email)->first();
        
        if (!$staff) {
            return redirect()->route('login')->with('error', 'Staff account not found.');
        }
        
        $statusFilter = $request->query('status', 'all');
        
        $query = $staff->staffTasks()->with('task');
        
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        
        $staffTasks = $query->orderBy('created_at', 'desc')->get();
        
        return view('staff.tasks.index', compact('staffTasks', 'statusFilter'));
    }

    public function completeTaskForm(StaffTask $staffTask)
    {
        $user = auth()->user();
        $staff = StaffModel::where('email', $user->email)->first();
        
        if (!$staff || $staffTask->staff_id !== $staff->id) {
            return redirect()->route('staff.my-tasks')->with('error', 'Unauthorized access.');
        }
        
        return view('staff.tasks.complete-form', compact('staffTask'));
    }

    public function completeTask(Request $request, StaffTask $staffTask)
    {
        $user = auth()->user();
        $staff = StaffModel::where('email', $user->email)->first();
        
        if (!$staff || $staffTask->staff_id !== $staff->id) {
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
        $staff->increment('balance', $staffTask->task->amount);
        
        return redirect()->route('staff.my-tasks')
                         ->with('success', 'Task completed successfully! ₹' . number_format($staffTask->task->amount, 2) . ' added to your wallet.');
    }
}