<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GuruSiswaController extends Controller
{
    /**
     * Menampilkan daftar siswa di kelas yang diampu oleh guru yang login.
     */
    public function index()
    {
        // Ambil guru yang sedang login
        $guru = Auth::user();

        // Cek apakah guru memiliki kelas yang diampu
        $kelas = $guru->kelas; // relasi di model User (guru) -> one to one / belongsTo ke Kelas

        if (!$kelas) {
            // Jika guru belum memiliki kelas, tampilkan pesan
            return view('guru.siswa.index', [
                'siswas' => collect([]),
                'kelas' => null,
            ]);
        }

        // Ambil semua siswa yang memiliki kelas_id = $kelas->id
        $siswas = User::where('role', 'siswa')
            ->where('kelas_id', $kelas->id)
            ->orderBy('name')
            ->get();

        return view('guru.siswa.index', [
            'siswas' => $siswas,
            'kelas' => $kelas,
        ]);
    }

    /**
     * Menampilkan form tambah siswa (opsional, bisa pakai modal saja)
     */
    public function create()
    {
        // Jika menggunakan modal, tidak perlu halaman terpisah
        // Tapi kita bisa return view jika diperlukan
    }

    /**
     * Menyimpan data siswa baru
     */
    public function store(Request $request)
    {
        $guru = Auth::user();
        $kelas = $guru->kelas;

        if (!$kelas) {
            return back()->with('error', 'Anda belum memiliki kelas yang diampu.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $siswa = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'kelas_id' => $kelas->id,
        ]);

        return back()->with('success', 'Siswa berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit (bisa pakai modal)
     */
    public function edit($id)
    {
        // Tidak perlu halaman terpisah, karena kita pakai modal
    }

    /**
     * Update data siswa
     */
    public function update(Request $request, $id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);

        // Pastikan siswa berada di kelas yang diampu oleh guru ini
        $guru = Auth::user();
        $kelas = $guru->kelas;
        if ($siswa->kelas_id !== $kelas->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke siswa ini.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $siswa->update($data);

        return back()->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Hapus siswa
     */
    public function destroy($id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);

        // Pastikan siswa berada di kelas yang diampu oleh guru ini
        $guru = Auth::user();
        $kelas = $guru->kelas;
        if ($siswa->kelas_id !== $kelas->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke siswa ini.');
        }

        $siswa->delete();

        return back()->with('success', 'Siswa berhasil dihapus!');
    }
}