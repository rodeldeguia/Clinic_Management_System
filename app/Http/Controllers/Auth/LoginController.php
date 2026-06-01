<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ActivityLog;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by username (case insensitive for safety)
        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password_hashed)) {
            Auth::login($user);
            
            // Update last login
            User::where('user_id', $user->user_id)->update([
                'last_login' => now()
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => $user->user_id,
                'action' => 'Logged in',
                'table_affected' => 'users',
                'record_id_affected' => $user->user_id,
                'ip_address' => $request->ip(),
            ]);

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'username' => 'Invalid credentials.',
        ]);
    }

    private function redirectBasedOnRole($user)
    {
        // Using lowercase role values from your database
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'doctor':
                return redirect()->route('doctor.dashboard');
            case 'receptionist':
                return redirect()->route('receptionist.dashboard');
            case 'patient':
                return redirect()->route('patient.dashboard');
            case 'medical_store':
                return redirect()->route('medical-store.dashboard');
            default:
                return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            ActivityLog::create([
                'user_id' => $user->user_id,
                'action' => 'Logged out',
                'table_affected' => 'users',
                'record_id_affected' => $user->user_id,
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        return back()->with('status', 'Password reset link sent.');
        
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        return redirect()->route('login');
    }
}