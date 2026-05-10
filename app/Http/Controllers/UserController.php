<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses Ditolak: Anda bukan Superadmin.');
        }

        // Mengambil semua user dengan role admin
        $users = User::where('role', 'admin')->get();
        return view('dashboard.user_index', compact('users'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403);
        }
        return view('dashboard.user_create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Pastikan role ditulis 'admin' (kecil semua) agar sinkron dengan index
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('users.index')->with('success', 'Admin Gudang berhasil ditambahkan.');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403);
        }

        // Proteksi agar Superadmin tidak menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}