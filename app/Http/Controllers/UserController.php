<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prodi;
use App\Models\Lab;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $admins = User::with(['lab'])->where('role', 'admin')->get();
        $labs = Lab::orderBy('name')->get();

        return view('users.index', compact('admins', 'labs'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'lab_id' => 'nullable|exists:labs,id', // direct assignment
            'password' => 'required|min:5',
        ]);

        User::create([
            'name' => $r->name,
            'email' => $r->email,
            'lab_id' => $r->lab_id, // direct assignment
            'role' => 'admin',
            'password' => bcrypt($r->password),
        ]);

        return back()->with('success', 'Admin berhasil dibuat');
    }
    public function edit(User $user)
    {
        // Usually handled via modal in index, but if separate page needed:
        // return view('superadmin.users.edit', compact('user'));
        return response()->json($user);
    }

    public function update(Request $r, User $user)
    {
        $r->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'lab_id' => 'nullable|exists:labs,id', // direct assignment
            'password' => 'nullable|min:5',
        ]);

        $data = [
            'name' => $r->name,
            'email' => $r->email,
            'lab_id' => $r->lab_id, // direct assignment
        ];

        if ($r->filled('password')) {
            $data['password'] = bcrypt($r->password);
        }

        $user->update($data);

        return back()->with('success', 'Admin berhasil diupdate');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'superadmin') {
            return back()->with('error', 'Cannot delete superadmin');
        }

        $user->delete();
        return back()->with('success', 'Admin berhasil dihapus');
    }
}
