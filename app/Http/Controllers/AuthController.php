<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Staff as StaffModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Try normal authentication first
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user has a staff record
            $staff = StaffModel::where('email', $user->email)->first();
            
            if ($staff) {
                // If staff record exists, redirect to staff dashboard
                return redirect()->intended('/staff/dashboard');
            } else {
                // If no staff record, redirect based on role
                if ($user->role === 'admin') {
                    return redirect()->intended('/admin/dashboard');
                } elseif ($user->role === 'staff') {
                    // Create staff record if doesn't exist
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
                    
                    return redirect()->intended('/staff/dashboard');
                } else {
                    return redirect()->intended('/user/dashboard');
                }
            }
        }

        // If normal authentication fails, try staff login with password from staff table
        $staff = StaffModel::where('email', $request->email)->first();
        
        if ($staff && $staff->password && Hash::check($request->password, $staff->password)) {
            // Find or create user in main users table
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $staff->name,
                    'password' => $staff->password,
                    'role' => 'staff'
                ]
            );
            
            Auth::login($user);
            return redirect()->intended('/staff/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}