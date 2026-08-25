<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    // Show all staff
    public function index()
    {
        $staff = User::with(['role', 'department'])->get();
        $roles = Role::all();
        $departments = Department::all();
        
        return view('staff.index', compact('staff', 'roles', 'departments'));
    }

    // Create new staff
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully!');
    }

    // Update staff
    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $staff->id,
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        $staff->update($request->only(['name', 'username', 'email', 'role_id', 'department_id']));

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully!');
    }

    // Delete staff
    public function destroy(User $staff)
    {
        // Prevent deleting yourself
        if ($staff->id === auth()->id()) {
            return redirect()->route('staff.index')->with('error', 'You cannot delete your own account!');
        }

        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully!');
    }
}