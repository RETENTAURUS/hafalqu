<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;

class DashboardAdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalGuru' => User::where('role', 'guru')->count(),
            'totalSiswa' => User::where('role', 'siswa')->count(),
            'totalKelas' => Kelas::count(),
        ]);
    }
}
