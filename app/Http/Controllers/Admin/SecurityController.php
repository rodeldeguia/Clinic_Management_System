<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller
{
    public function roles()
    {
        $roles = ['Admin', 'Doctor', 'Receptionist', 'Patient', 'Medical_Store'];
        $permissions = [
            'Admin' => ['full_access'],
            'Doctor' => ['view_appointments', 'manage_patient_care', 'view_schedule'],
            'Receptionist' => ['register_patients', 'schedule_appointments', 'manage_billing'],
            'Patient' => ['view_records', 'book_appointments', 'give_feedback'],
            'Medical_Store' => ['manage_stock', 'dispense_prescriptions', 'generate_bills'],
        ];
        
        return view('admin.security.roles', compact('roles', 'permissions'));
    }

    public function updatePermissions(Request $request, $role)
    {
        // Store permissions in database or config
        // You might want to create a permissions table
        
        return back()->with('success', 'Permissions updated for ' . $role);
    }

    public function loginActivity()
    {
        $logs = ActivityLog::with('user')->orderBy('timestamps', 'desc')->paginate(50);
        return view('admin.security.login-activity', compact('logs'));
    }

    public function auditLogs(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->user_id, function($q) use ($request) {
                $q->where('user_id', $request->user_id);
            })
            ->when($request->action, function($q) use ($request) {
                $q->where('action', 'like', '%' . $request->action . '%');
            })
            ->latest('timestamp')
            ->paginate(50);
            
        return view('admin.security.audit-logs', compact('logs'));
    }

    public function resetUserPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->password_hash = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password reset successfully.');
    }

    public function unlockAccount($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = true;
        $user->save();

        return back()->with('success', 'Account unlocked successfully.');
    }
}