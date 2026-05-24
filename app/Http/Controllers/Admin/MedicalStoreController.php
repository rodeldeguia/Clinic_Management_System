<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MedicalStoreController extends Controller
{
    public function index()
    {
        $medicalStaff = User::where('role', 'medical_store')->paginate(10);
        return view('admin.medical-store.index', compact('medicalStaff'));
    }

    public function create()
    {
        return view('admin.medical-store.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'contact_number' => 'required',
            'email_address' => 'required|email|unique:users,email_address',
            'store_role' => 'required',
        ]);

        try {
            $userId = DB::table('users')->insertGetId([
                'username' => $request->username,
                'password_hashed' => Hash::make($request->password),
                'role' => 'medical_store',
                'is_active' => 1,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'contact_number' => $request->contact_number,
                'email_address' => $request->email_address,
                'store_role' => $request->store_role,
                'address' => $request->address,
                'created_at' => now(),
            ]);

            return redirect()->route('admin.medical-store.index')
                ->with('success', 'Medical Store staff added successfully! Username: ' . $request->username . ', Password: ' . $request->password);
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $staff = User::findOrFail($id);
        return view('admin.medical-store.edit', compact('staff'));
    }

    public function update(Request $request, $id)
    {
        $staff = User::findOrFail($id);

        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'contact_number' => 'required',
            'email_address' => 'required|email|unique:users,email_address,' . $id . ',user_id',
            'store_role' => 'required',
            'is_active' => 'boolean',
        ]);

        $updateData = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'contact_number' => $request->contact_number,
            'email_address' => $request->email_address,
            'store_role' => $request->store_role,
            'address' => $request->address,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->filled('password')) {
            $updateData['password_hashed'] = Hash::make($request->password);
        }

        $staff->update($updateData);

        return redirect()->route('admin.medical-store.index')
            ->with('success', 'Medical Store staff updated successfully.');
    }

    public function destroy($id)
    {
        $staff = User::findOrFail($id);
        $staff->delete();
        return redirect()->route('admin.medical-store.index')
            ->with('success', 'Medical Store staff deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $staff = User::findOrFail($id);
        $staff->is_active = !$staff->is_active;
        $staff->save();

        $status = $staff->is_active ? 'activated' : 'deactivated';
        return back()->with('success', 'Staff ' . $status . ' successfully.');
    }
}