<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class AdminKelasController extends Controller
{
    // Menampilkan daftar kelas
    public function index()
    {
        $kelas = Kelas::all();
        return view('admin.kelas.index', compact('kelas'));
    }

    // Menyimpan kelas baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Kelas::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:255|unique:kelas,nama,' . $id,
            'deskripsi' => 'nullable|string',
        ]);

        $kelas->update([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with('success', 'Data kelas berhasil diperbarui!');
    }

    // Menghapus data kelas
    public function destroy($id)
    {
        Kelas::findOrFail($id)->delete();
        return back()->with('success', 'Kelas berhasil dihapus!');
    }
}
