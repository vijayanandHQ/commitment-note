<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkList;
use Illuminate\Http\Request;

class WorkListController extends Controller
{
    public function index()
    {
        $workLists = WorkList::orderBy('created_at', 'desc')->get();
        return view('admin.work-lists.index', compact('workLists'));
    }

    public function create()
    {
        return view('admin.work-lists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tasks' => 'nullable|string',
            'upload_date' => 'nullable|date',
            'ppt_file' => 'nullable|mimes:ppt,pptx|max:10000',
            'is_editable' => 'boolean',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('worklist_photos', 'public');
            $validated['photo'] = $photoPath;
        }

        // Handle PPT upload
        if ($request->hasFile('ppt_file')) {
            $pptPath = $request->file('ppt_file')->store('worklist_ppts', 'public');
            $validated['ppt_file'] = $pptPath;
        }

        // Parse tasks into array
        $tasks = explode("\n", $validated['tasks']);
        $taskStatus = [];
        foreach ($tasks as $task) {
            if (!empty(trim($task))) {
                $taskStatus[trim($task)] = false; // Default to not completed
            }
        }

        $validated['task_status'] = json_encode($taskStatus);

        WorkList::create($validated);

        return redirect()->route('admin.work-lists.index')
                         ->with('success', 'Work list created successfully!');
    }

    public function show(WorkList $workList)
    {
        return view('admin.work-lists.show', compact('workList'));
    }

    public function edit(WorkList $workList)
    {
        return view('admin.work-lists.edit', compact('workList'));
    }

    public function update(Request $request, WorkList $workList)
    {
        $validated = $request->validate([
            'staff_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tasks' => 'nullable|string',
            'upload_date' => 'nullable|date',
            'ppt_file' => 'nullable|mimes:ppt,pptx|max:10000',
            'is_editable' => 'boolean',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($workList->photo) {
                \Storage::disk('public')->delete($workList->photo);
            }
            $photoPath = $request->file('photo')->store('worklist_photos', 'public');
            $validated['photo'] = $photoPath;
        }

        // Handle PPT upload
        if ($request->hasFile('ppt_file')) {
            // Delete old PPT if exists
            if ($workList->ppt_file) {
                \Storage::disk('public')->delete($workList->ppt_file);
            }
            $pptPath = $request->file('ppt_file')->store('worklist_ppts', 'public');
            $validated['ppt_file'] = $pptPath;
        }

        // Parse tasks into array
        $tasks = explode("\n", $validated['tasks']);
        $taskStatus = [];
        foreach ($tasks as $task) {
            if (!empty(trim($task))) {
                $taskStatus[trim($task)] = false; // Default to not completed
            }
        }

        $validated['task_status'] = json_encode($taskStatus);

        $workList->update($validated);

        return redirect()->route('admin.work-lists.index')
                         ->with('success', 'Work list updated successfully!');
    }

    public function destroy(WorkList $workList)
    {
        // Delete associated files
        if ($workList->photo) {
            \Storage::disk('public')->delete($workList->photo);
        }
        if ($workList->ppt_file) {
            \Storage::disk('public')->delete($workList->ppt_file);
        }

        $workList->delete();

        return redirect()->route('admin.work-lists.index')
                         ->with('success', 'Work list deleted successfully!');
    }
    public function markTaskAsComplete(Request $request, WorkList $workList)
{
    $task = $request->task;
    
    if (!$workList->task_status) {
        $workList->task_status = [];
    }
    
    $workList->task_status[$task] = true;
    $workList->save();
    
    return response()->json(['success' => true]);
}

public function markTaskAsIncomplete(Request $request, WorkList $workList)
{
    $task = $request->task;
    
    if (!$workList->task_status) {
        $workList->task_status = [];
    }
    
    $workList->task_status[$task] = false;
    $workList->save();
    
    return response()->json(['success' => true]);
}
}