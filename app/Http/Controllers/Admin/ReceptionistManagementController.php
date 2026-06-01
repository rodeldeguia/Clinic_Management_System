<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class ReceptionistManagementController extends Controller
{
    public function index()
    {
        $receptionists = User::where('role', 'Receptionist')->paginate(10);
        return view('admin.receptionists.index', compact('receptionists'));
    }

    public function create()
    {
        $doctors = User::where('role', 'Doctor')->where('is_active', true)->get();
        return view('admin.receptionists.create', compact('doctors'));
    }

   public function store(Request $request)
{
    // Validate
    $validator = validator($request->all(), [
        'username' => 'required|unique:users,username',
        'password' => 'required|min:6',
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'contact_number' => 'required',
        'email_address' => 'required|email|unique:users,email_address',
        'shift_timing' => 'required',
        'assigned_section' => 'nullable',
        'gender' => 'nullable',
        'date_of_birth' => 'nullable|date',
        'address' => 'nullable',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Please fix the errors below.');
    }

    try {
        $userId = DB::table('users')->insertGetId([
            'username' => $request->username,
            'password_hashed' => Hash::make($request->password),
            'role' => 'receptionist',
            'is_active' => 1,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'contact_number' => $request->contact_number,
            'email_address' => $request->email_address,
            'shift_timing' => $request->shift_timing,
            'assigned_section' => $request->assigned_section,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'created_at' => now(),
        ]);

        if ($userId) {
            return redirect()->route('admin.receptionists.index')
                ->with('success', 'Receptionist added successfully! Username: ' . $request->username);
        } else {
            return back()->with('error', 'Failed to save receptionist.')->withInput();
        }
    } catch (\Exception $e) {
        return back()->with('error', 'Database error: ' . $e->getMessage())->withInput();
    }
}

    public function show($id)
        {
            $receptionist = User::findOrFail($id);
            
            // Change this line - use 'timestamps' instead of 'created_at'
            $activityLogs = ActivityLog::where('user_id', $id)
                ->orderBy('timestamps', 'desc')  // ← Change 'created_at' to 'timestamps'
                ->take(20)
                ->get();
            
            return view('admin.receptionists.show', compact('receptionist', 'activityLogs'));
        }

    public function edit($id)
    {
        $receptionist = User::findOrFail($id);
        $doctors = User::where('role', 'Doctor')->where('is_active', true)->get();
        return view('admin.receptionists.edit', compact('receptionist', 'doctors'));
    }

    public function update(Request $request, $id)
{
    $receptionist = User::findOrFail($id);

    $request->validate([
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'username' => 'required|unique:users,username,' . $id . ',user_id',
        'contact_number' => 'required',
        'email_address' => 'required|email|unique:users,email_address,' . $id . ',user_id',
        'shift_timing' => 'required',
        'assigned_section' => 'nullable',
    ]);

    $updateData = [
        'firstname' => $request->firstname,
        'lastname' => $request->lastname,
        'username' => $request->username,
        'contact_number' => $request->contact_number,
        'email_address' => $request->email_address,
        'address' => $request->address,
        'date_of_birth' => $request->date_of_birth,
        'gender' => $request->gender ?? $receptionist->gender,
        'shift_timing' => $request->shift_timing,
        'assigned_section' => $request->assigned_section,
        'is_active' => $request->has('is_active') ? 1 : 0,
    ];

    // Update password only if provided
    if ($request->filled('password')) {
        $updateData['password_hashed'] = Hash::make($request->password);
    }

    $receptionist->update($updateData);

    return redirect()->route('admin.receptionists.index')->with('success', 'Receptionist updated successfully.');
}

    public function destroy($id)
    {
        $receptionist = User::findOrFail($id);
        $receptionist->delete();
        return redirect()->route('admin.receptionists.index')->with('success', 'Receptionist deleted successfully.');
    }

    public function suspend($id)
    {
        $receptionist = User::findOrFail($id);
        $receptionist->is_active = false;
        $receptionist->save();

        return back()->with('success', 'Receptionist suspended successfully.');
    }

    public function activityLogs($id)
    {
        // Change this line as well
    $logs = ActivityLog::where('user_id', $id)
        ->orderBy('timestamps', 'desc')  // ← Change 'created_at' to 'timestamps'
        ->paginate(20);
    
    return view('admin.receptionists.activity-logs', compact('logs'));
    }

    public function assignToDoctor(Request $request, $id)
    {
        $receptionist = User::findOrFail($id);
        $receptionist->assigned_section = $request->assigned_section;
        $receptionist->save();

        return back()->with('success', 'Receptionist assigned to doctor/section successfully.');
    }
}