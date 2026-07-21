@extends('layouts.admin')

@section('title', 'HafalQU — Dashboard Admin')

{{-- Override header sepenuhnya karena dashboard memiliki tanggal dan notifikasi --}}
@section('header')
  <header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-3.5 sm:py-4 flex items-center justify-between flex-shrink-0">
    <div>
      <h1 class="text-sm sm:text-base font-semibold text-slate-800">Dashboard</h1>
      <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Selamat datang kembali, Ustadz Ali</p>
    </div>
    <div class="flex items-center gap-2 sm:gap-3">
      <!-- Date badge -->
      <span class="text-[11px] sm:text-xs text-slate-500 bg-slate-100 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full font-medium">
        {{ date('d M Y') }}
      </span>
      <!-- Notification dot -->
      <button class="relative w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-500 active:bg-slate-200 transition-colors">
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
  <p class="text-[11px] font-semibold tracking-widest uppercase text-slate-400 mb-3 sm:mb-4">Ringkasan</p>

  <!-- Stat Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-5 mb-8 sm:mb-10">

    <!-- Guru -->
    <div class="card-guru bg-white border border-slate-200/80 rounded-2xl px-5 sm:px-6 py-4 sm:py-5 shadow-sm">
      <div class="flex items-start justify-between mb-3 sm:mb-4">
        <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center">
          <svg class="w-4.5 h-4.5 text-teal-600" style="width:18px;height:18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>
        <span class="text-[10px] font-semibold text-teal-600 bg-teal-50 px-2.5 py-0.5 rounded-full">Aktif</span>
      </div>
      <p class="text-2xl sm:text-3xl font-bold text-slate-800 leading-none mb-1">{{ $totalGuru ?? 0 }}</p>
      <p class="text-xs sm:text-sm text-slate-400">Guru terdaftar</p>
    </div>

    <!-- Siswa -->
    <div class="card-siswa bg-white border border-slate-200/80 rounded-2xl px-5 sm:px-6 py-4 sm:py-5 shadow-sm">
      <div class="flex items-start justify-between mb-3 sm:mb-4">
        <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
          <svg class="w-4.5 h-4.5 text-amber-600" style="width:18px;height:18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </div>
        <span class="text-[10px] font-semibold text-amber-600 bg-amber-50 px-2.5 py-0.5 rounded-full">Aktif</span>
      </div>
      <p class="text-2xl sm:text-3xl font-bold text-slate-800 leading-none mb-1">{{ $totalSiswa ?? 0 }}</p>
      <p class="text-xs sm:text-sm text-slate-400">Siswa terdaftar</p>
    </div>

    <!-- Kelas -->
    <div class="card-kelas bg-white border border-slate-200/80 rounded-2xl px-5 sm:px-6 py-4 sm:py-5 shadow-sm sm:col-span-2 lg:col-span-1">
      <div class="flex items-start justify-between mb-3 sm:mb-4">
        <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center">
          <svg class="w-4.5 h-4.5 text-indigo-600" style="width:18px;height:18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
          </svg>
        </div>
        <span class="text-[10px] font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full">Semester ini</span>
      </div>
      <p class="text-2xl sm:text-3xl font-bold text-slate-800 leading-none mb-1">{{ $totalKelas ?? 0 }}</p>
      <p class="text-xs sm:text-sm text-slate-400">Total kelas</p>
    </div>
  </div>

  <!-- Quick Actions -->
  <p class="text-[11px] font-semibold tracking-widest uppercase text-slate-400 mb-3 sm:mb-4">Aksi Cepat</p>
  <div class="flex flex-col sm:flex-row flex-wrap gap-2.5 sm:gap-3">
    <a href="{{ route('admin.guru.index') }}" 
      class="w-full sm:w-auto flex items-center justify-center sm:justify-start gap-2 bg-white border border-slate-200 hover:border-teal-300 active:bg-slate-50 hover:text-teal-700 text-slate-600 text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl transition-colors shadow-sm">
      <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
      </svg>
      <span>Tambah Guru</span>
    </a>
    <a href="{{ route('admin.kelas.index') }}" 
      class="w-full sm:w-auto flex items-center justify-center sm:justify-start gap-2 bg-white border border-slate-200 hover:border-teal-300 active:bg-slate-50 hover:text-teal-700 text-slate-600 text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl transition-colors shadow-sm">
      <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
      </svg>
      <span>Buat Kelas Baru</span>
    </a>
    <a href="#" 
      class="w-full sm:w-auto flex items-center justify-center sm:justify-start gap-2 bg-white border border-slate-200 hover:border-teal-300 active:bg-slate-50 hover:text-teal-700 text-slate-600 text-xs sm:text-sm font-medium px-4 py-2.5 rounded-xl transition-colors shadow-sm">
      <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
        <polyline points="7 10 12 15 17 10"/>
        <line x1="12" y1="15" x2="12" y2="3"/>
      </svg>
      <span>Unduh Laporan</span>
    </a>
  </div>
@endsection