<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffTask;
use App\Models\Task;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $staff = auth()->user(); // Assuming you have a way to identify staff
        $staffModel = \App\Models\Staff::where('email', $staff->email)->first();
        
        if (!$staffModel) {
            // If the logged-in user is not in staff table, redirect
            return redirect()->route('admin.dashboard');
        }
        
        // Get staff tasks
        $assignedTasks = $staffModel->staffTasks()->where('status', 'assigned')->count();
        $inProgressTasks = $staffModel->staffTasks()->where('status', 'in_progress')->count();
        $completedTasks = $staffModel->staffTasks()->where('status', 'completed')->count();
        
        // Get recent tasks
        $recentTasks = $staffModel->staffTasks()->with('task')->latest()->take(5)->get();
        
        return view('staff.dashboard.index', compact(
            'staffModel', 
            'assignedTasks', 
            'inProgressTasks', 
            'completedTasks', 
            'recentTasks'
        ));
    }

    
}