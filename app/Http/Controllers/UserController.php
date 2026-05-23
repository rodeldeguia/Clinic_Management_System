<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        User::create([
            'username' => $request->username,
            'password_hashed' => bcrypt($request->password),
            'role' => $request->role,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'contact_number' => $request->contact_number,
            'email_address' => $request->email_address,
            'is_active' => 1,
        ]);

        return redirect()->route('users.index')->with('success', 'User added successfully!');
    }
}
