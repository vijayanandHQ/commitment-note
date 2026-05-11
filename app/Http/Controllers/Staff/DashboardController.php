<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff as StaffModel;
use App\Models\StaffTask;
use App\Models\Task;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $staff = StaffModel::where('email', $user->email)->first();
        
        if (!$staff) {
            // If staff doesn't exist, create it
            $staff = StaffModel::create([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => null,
                'position' => 'Staff Member',
                'balance' => 0.00,
                'photo' => null,
                'bio' => null,
                'is_active' => true,
            ]);
        }
        
        // Get staff statistics
        $assignedTasks = $staff->staffTasks()->where('status', 'assigned')->count();
        $inProgressTasks = $staff->staffTasks()->where('status', 'in_progress')->count();
        $completedTasks = $staff->staffTasks()->where('status', 'completed')->count();
        $recentTasks = $staff->staffTasks()->with('task')->latest()->take(5)->get();
        
        return view('staff.dashboard.index', compact(
            'staff',
            'assignedTasks',
            'inProgressTasks',
            'completedTasks',
            'recentTasks'
        ));
    }
}