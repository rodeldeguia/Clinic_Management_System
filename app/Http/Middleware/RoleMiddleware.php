<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is deactivated. Please contact admin.');
        }

        // If no specific roles required, just allow authenticated user
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user has one of the allowed roles
        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        // User does not have permission - redirect based on their role
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Redirect user to their appropriate dashboard
     */
    private function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'Admin':
                return redirect()->route('admin.dashboard')->with('error', 'You do not have access to that page.');
            case 'Doctor':
                return redirect()->route('doctor.dashboard')->with('error', 'You do not have access to that page.');
            case 'Receptionist':
                return redirect()->route('receptionist.dashboard')->with('error', 'You do not have access to that page.');
            case 'Patient':
                return redirect()->route('patient.dashboard')->with('error', 'You do not have access to that page.');
            case 'Medical_Store':
                return redirect()->route('medical-store.dashboard')->with('error', 'You do not have access to that page.');
            default:
                return redirect('/')->with('error', 'Unauthorized access.');
        }
    }
}