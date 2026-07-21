<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminSiswaController extends Controller
{
    // Menampilkan daftar kelas
    public function kelasList()
    {
        $kelas = Kelas::all();

        return view('admin.siswa.kelasList', compact('kelas'));
    }

    // Menampilkan siswa berdasarkan kelas
    public function showByKelas($id)
    {
        $kelas = Kelas::findOrFail($id);

        $siswas = User::where('role', 'siswa')
            ->where('kelas_id', $id)
            ->get();

        return view('admin.siswa.showByKelas', compact('kelas', 'siswas'));
    }

    // Simpan siswa baru
    public function store(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|alpha_num|min:3|max:50|unique:users,username',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'kelas_id' => $kelas->id,
        ]);

        return redirect()
            ->route('admin.siswa.showByKelas', $id)
            ->with('success', 'Akun siswa berhasil ditambahkan.');
    }

public function create($kelasId)
{
    $kelas = Kelas::findOrFail($kelasId);

    return view('admin.siswa.create', compact('kelas'));
}

public function edit($id)
{
    $siswa = User::findOrFail($id);

    return view('admin.siswa.edit', compact('siswa'));
}

public function update(Request $request, $id)
{
    $siswa = User::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'username' => 'required|username|unique:users,username,' . $siswa->id,
    ]);

    $siswa->update([
        'name' => $request->name,
        'username' => $request->username,
    ]);

    return back()->with('success', 'Data siswa berhasil diubah');
}

public function destroy($id)
{
    $siswa = User::findOrFail($id);

    $kelasId = $siswa->kelas_id;

    $siswa->delete();

    return redirect()
        ->route('admin.siswa.showByKelas', $kelasId)
        ->with('success', 'Akun siswa berhasil dihapus');
}


}