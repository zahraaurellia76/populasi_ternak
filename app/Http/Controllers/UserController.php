<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('kecamatan')->latest()->paginate(10);
        $kecamatans = Kecamatan::all();
        return view('admin.kabupaten.user.index', compact('users', 'kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|max:255',
            'username'     => 'required|string|unique:users,username',
            'password'     => 'required|string|min:6',
            'role'         => 'required|in:admin_kabupaten,petugas_kecamatan',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
        ]);

        User::create([
            'nama'         => $request->nama,
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
            'kecamatan_id' => $request->role === 'petugas_kecamatan' ? $request->kecamatan_id : null,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama'         => 'required|string|max:255',
            'username'     => 'required|string|unique:users,username,' . $id,
            'password'     => 'nullable|string|min:6',
            'role'         => 'required|in:admin_kabupaten,petugas_kecamatan',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
        ]);

        $data = [
            'nama'         => $request->nama,
            'username'     => $request->username,
            'role'         => $request->role,
            'kecamatan_id' => $request->role === 'petugas_kecamatan' ? $request->kecamatan_id : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }
}