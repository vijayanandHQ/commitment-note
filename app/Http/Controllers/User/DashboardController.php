<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CommitmentNote;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $notes = CommitmentNote::where('created_by', auth()->id())->get();
        return view('user.dashboard', compact('notes'));
    }
}