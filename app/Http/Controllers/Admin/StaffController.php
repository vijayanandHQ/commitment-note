<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffTask;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::orderBy('name')->get();
        return view('admin.staffs.index', compact('staffs'));
    }

    public function create()
    {
        return view('admin.staffs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staffs,email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('staff_photos', 'public');
        }

        Staff::create($validated);

        return redirect()->route('admin.staffs.index')
                         ->with('success', 'Staff created successfully!');
    }

    public function show(Staff $staff)
    {
        $staff->load('staffTasks.task');
        return view('admin.staffs.show', compact('staff'));
    }

    public function edit(Staff $staff)
    {
        return view('admin.staffs.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staffs,email,' . $staff->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($staff->photo) {
                \Storage::disk('public')->delete($staff->photo);
            }
            $validated['photo'] = $request->file('photo')->store('staff_photos', 'public');
        }

        $staff->update($validated);

        return redirect()->route('admin.staffs.index')
                         ->with('success', 'Staff updated successfully!');
    }

    public function destroy(Staff $staff)
    {
        if ($staff->photo) {
            \Storage::disk('public')->delete($staff->photo);
        }

        $staff->delete();

        return redirect()->route('admin.staffs.index')
                         ->with('success', 'Staff deleted successfully!');
    }

    public function getStaffDetails($id)
    {
        $staff = Staff::with(['staffTasks.task'])->findOrFail($id);
        
        $data = [
            'id' => $staff->id,
            'name' => $staff->name,
            'position' => $staff->position,
            'email' => $staff->email,
            'phone' => $staff->phone,
            'balance' => $staff->balance,
            'created_at' => $staff->created_at->format('M d, Y'),
            'tasks' => $staff->staffTasks->map(function($assignment) {
                return [
                    'id' => $assignment->task->id,
                    'title' => $assignment->task->title,
                    'amount' => $assignment->task->amount,
                    'pivot' => [
                        'status' => $assignment->status,
                        'assigned_at' => $assignment->assigned_at
                    ]
                ];
            })
        ];
        
        return response()->json($data);
    }

    public function getStaffTasks($id)
    {
        $staff = Staff::with(['staffTasks.task'])->findOrFail($id);
        
        $data = [
            'id' => $staff->id,
            'name' => $staff->name,
            'position' => $staff->position,
            'email' => $staff->email,
            'phone' => $staff->phone,
            'balance' => $staff->balance,
            'created_at' => $staff->created_at->format('M d, Y'),
            'tasks' => $staff->staffTasks->map(function($assignment) {
                return [
                    'id' => $assignment->task->id,
                    'title' => $assignment->task->title,
                    'amount' => $assignment->task->amount,
                    'pivot' => [
                        'id' => $assignment->id,
                        'status' => $assignment->status,
                        'assigned_at' => $assignment->assigned_at,
                        'notes' => $assignment->notes
                    ]
                ];
            })
        ];
        
        return response()->json($data);
    }
}