<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommitmentNote;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $notes = CommitmentNote::with('user')->latest()->get();
        return view('admin.dashboard', compact('notes'));
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }
}