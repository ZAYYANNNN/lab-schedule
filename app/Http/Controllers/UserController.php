<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        $prodis = Prodi::all();

        return view('superadmin.users.index', compact('admins', 'prodis'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'prodi_id' => 'required|exists:prodis,id', // foreign key
            'password' => 'required|min:5',
        ]);

        User::create([
            'name' => $r->name,
            'email' => $r->email,
            'prodi_id' => $r->prodi_id, // foreign key
            'role' => 'admin',
            'password' => bcrypt($r->password),
        ]);

        return back()->with('success', 'Admin berhasil dibuat');
    }
}
