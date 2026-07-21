@extends('layouts.admin')

@section('title', 'HafalQU — Dashboard Admin')

{{-- Override header sepenuhnya karena dashboard memiliki tanggal dan notifikasi --}}
@section('header')
  <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between flex-shrink-0">
    <div>
      <h1 class="text-base font-semibold text-slate-800">Dashboard</h1>
      <p class="text-xs text-slate-400 mt-0.5">Selamat datang kembali, Ustadz Ali</p>
    </div>
    <div class="flex items-center gap-3">
      <!-- Date badge -->
      <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">
        {{ date('d M Y') }}
      </span>
      <!-- Notification dot -->
      <button class="relative w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-500">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-teal-500 rounded-full"></span>
      </button>
    </div>
  </header>
@endsection

@section('content')
  <!-- Section label -->
  <p class="text-[11px] font-semibold tracking-widest uppercase text-slate-400 mb-4">Ringkasan</p>

  <!-- Stat Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">

    <!-- Guru -->
    <div class="card-guru bg-white rounded-xl px-6 py-5">
      <div class="flex items-start justify-between mb-4">
        <div class="w-9 h-9 rounded-lg bg-teal-50 flex items-center justify-center">
          <svg class="w-4.5 h-4.5 text-teal-600" style="width:18px;height:18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>
        <span class="text-[10px] font-medium text-teal-600 bg-teal-50 px-2 py-1 rounded-full">Aktif</span>
      </div>
      <p class="text-3xl font-bold text-slate-800 leading-none mb-1">{{ $totalGuru ?? 0 }}</p>
      <p class="text-sm text-slate-400">Guru terdaftar</p>
    </div>

    <!-- Siswa -->
    <div class="card-siswa bg-white rounded-xl px-6 py-5">
      <div class="flex items-start justify-between mb-4">
        <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
          <svg class="w-4.5 h-4.5 text-amber-600" style="width:18px;height:18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </div>
        <span class="text-[10px] font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Aktif</span>
      </div>
      <p class="text-3xl font-bold text-slate-800 leading-none mb-1">{{ $totalSiswa ?? 0 }}</p>
      <p class="text-sm text-slate-400">Siswa terdaftar</p>
    </div>

    <!-- Kelas -->
    <div class="card-kelas bg-white rounded-xl px-6 py-5">
      <div class="flex items-start justify-between mb-4">
        <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center">
          <svg class="w-4.5 h-4.5 text-indigo-600" style="width:18px;height:18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
          </svg>
        </div>
        <span class="text-[10px] font-medium text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">Semester ini</span>
      </div>
      <p class="text-3xl font-bold text-slate-800 leading-none mb-1">{{ $totalKelas ?? 0 }}</p>
      <p class="text-sm text-slate-400">Total kelas</p>
    </div>
  </div>

  <!-- Quick Actions -->
  <p class="text-[11px] font-semibold tracking-widest uppercase text-slate-400 mb-4">Aksi Cepat</p>
  <div class="flex flex-wrap gap-3">
    <a href="{{ route('admin.guru.index') }}" class="flex items-center gap-2 bg-white border border-slate-200 hover:border-teal-300 hover:text-teal-700 text-slate-600 text-sm px-4 py-2.5 rounded-lg transition-colors">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
      </svg>
      Tambah Guru
    </a>
    <a href="{{ route('admin.kelas.index') }}" class="flex items-center gap-2 bg-white border border-slate-200 hover:border-teal-300 hover:text-teal-700 text-slate-600 text-sm px-4 py-2.5 rounded-lg transition-colors">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
      </svg>
      Buat Kelas Baru
    </a>
    <a href="#" class="flex items-center gap-2 bg-white border border-slate-200 hover:border-teal-300 hover:text-teal-700 text-slate-600 text-sm px-4 py-2.5 rounded-lg transition-colors">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
        <polyline points="7 10 12 15 17 10"/>
        <line x1="12" y1="15" x2="12" y2="3"/>
      </svg>
      Unduh Laporan
    </a>
  </div>
@endsection