<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        // Example: save user preferences, theme, etc.
        // For now, just redirect back
        return back()->with('success', 'Settings saved.');
    }
}