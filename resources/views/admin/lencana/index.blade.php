@extends('layouts.admin')

@section('title', 'Kelola Lencana — HafalQU Guru')
@section('page_title', 'Kelola Lencana')
@section('page_subtitle', 'Daftar semua lencana yang tersedia')

@section('header_actions')
  <a href="{{ route('guru.lencana.create') }}" class="flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Lencana
  </a>
@endsection

@section('content')
  @if(session('success'))
    <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
  @endif

  @if($badges->isEmpty())
    <div class="text-center py-20 text-slate-400">
      <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <circle cx="12" cy="8" r="6"/>
        <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
      </svg>
      <p class="text-sm font-medium">Belum ada lencana.</p>
      <a href="{{ route('guru.lencana.create') }}" class="text-teal-600 text-sm hover:underline mt-2 inline-block">Buat lencana pertama</a>
    </div>
  @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach($badges as $badge)
      <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-2xl flex-shrink-0">{{ $badge->icon ?? '🏅' }}</div>
            <div>
              <h4 class="font-semibold text-slate-800">{{ $badge->name }}</h4>
              <div class="flex items-center gap-2 mt-0.5">
                <span class="text-xs px-2 py-0.5 rounded-full {{ $badge->level_badge['bg'] }} {{ $badge->level_badge['text'] }}">{{ $badge->level_badge['label'] }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $badge->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">{{ $badge->is_active ? 'Aktif' : 'Nonaktif' }}</span>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-1">
            <a href="{{ route('guru.lencana.edit', $badge->id) }}" class="p-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-md transition-colors" title="Edit">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </a>
            <form action="{{ route('guru.lencana.toggle', $badge->id) }}" method="POST" class="inline">
              @csrf @method('PATCH')
              <button type="submit" title="{{ $badge->is_active ? 'Nonaktifkan' : 'Aktifkan' }}" class="p-1.5 {{ $badge->is_active ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }} rounded-md transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="{{ $badge->is_active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' }}"/></svg>
              </button>
            </form>
            <form action="{{ route('guru.lencana.destroy', $badge->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus lencana ini?')">
              @csrf @method('DELETE')
              <button type="submit" title="Hapus" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-md transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
              </button>
            </form>
          </div>
        </div>
        <div class="mt-3 text-sm text-slate-500 line-clamp-2">{{ $badge->description ?? 'Tidak ada deskripsi' }}</div>
        <div class="mt-3 flex flex-wrap gap-2 text-xs">
          <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $badge->criteria_label }}: {{ $badge->criteria_value }}</span>
          @if($badge->quiz) <span class="bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full">Quiz: {{ $badge->quiz->title }}</span> @endif
          @if($badge->surat) <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">Surat: {{ $badge->surat->nama_surat }}</span> @endif
          @if($badge->juz) <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full">Juz: {{ $badge->juz->nomor }}</span> @endif
        </div>
      </div>
      @endforeach
    </div>
  @endif
@endsection