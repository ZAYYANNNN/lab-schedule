<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();

        return view('superadmin.users.index', compact('admins'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'prodi' => 'required|string',
            'password' => 'required|min:5',
        ]);

        User::create([
            'name' => $r->name,
            'email' => $r->email,
            'prodi' => $r->prodi,
            'role' => 'admin',
            'password' => bcrypt($r->password),
        ]);

        return back()->with('success', 'Admin berhasil dibuat');
    }
}
