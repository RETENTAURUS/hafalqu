<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminGuruController extends Controller
{
    // Menampilkan daftar guru + daftar kelas untuk dropdown
    public function index()
    {
        $gurus = User::where('role', 'guru')->with('kelas')->get();
        $kelas = Kelas::all();

        return view('admin.guru.index', compact('gurus', 'kelas'));
    }

    // Menyimpan akun guru baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username'    => 'required|username|unique:users,username',
            'password' => 'required|min:6',
            'kelas_id' => 'required|exists:kelas,id', // wajib pilih kelas
        ]);

        User::create([
            'name'     => $request->name,
            'username'    => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
            'kelas_id' => $request->kelas_id,
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Akun guru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
{
    $guru = User::findOrFail($id);

    $data = [
        'name' => $request->name,
        'username' => $request->username,
        'kelas_id' => $request->kelas_id,
    ];

    if($request->password){
        $data['password'] = bcrypt($request->password);
    }

    $guru->update($data);

    return back()->with('success', 'Data guru berhasil diperbarui');
}

public function destroy($id)
{
    User::findOrFail($id)->delete();

    return back()->with('success', 'Data guru berhasil dihapus');
}
}
