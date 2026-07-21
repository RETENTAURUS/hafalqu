@extends('layouts.guru')

@section('title', 'Dashboard Guru — HafalQU')
@section('page_title', 'Dashboard Guru')
@section('page_subtitle', 'Kelas ' . (auth()->user()->kelas?->nama ?? 'Anda') . ' · ' . $tanggal)

@section('content')

@php
  $namaKelas = auth()->user()->kelas?->nama ?? 'Anda';
  $top3     = $leaderboard->take(3)->values();
  $rank4to6 = $leaderboard->slice(3)->values();
@endphp

<div class="guru-dash">

  {{-- ═══ Header ═══ --}}
  <div class="gd-header">
    <p class="gd-eyebrow">Monitoring Hafalan · Kelas {{ $namaKelas }}</p>
    <h1 class="gd-title">Assalamu'alaikum, {{ $namaGuru }}</h1>
    <p class="gd-subtitle">{{ $tanggal }} — berikut ringkasan perkembangan kelas {{ $namaKelas }} hari ini.</p>
  </div>

  {{-- ═══ Stat Cards ═══ --}}
  <div class="gd-stats">

    <div class="gd-stat-card" style="--delay:0s">
      <div class="gd-medallion gd-medallion--blue">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
        </svg>
      </div>
      <p class="gd-stat-value">{{ $totalSiswa }}</p>
      <p class="gd-stat-label">Total Siswa</p>
    </div>

    <div class="gd-stat-card" style="--delay:0.06s">
      <div class="gd-medallion gd-medallion--gold">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
      </div>
      <p class="gd-stat-value">{{ $quizAktif }}</p>
      <p class="gd-stat-label">Quiz Aktif</p>
    </div>

    <div class="gd-stat-card" style="--delay:0.12s">
      <div class="gd-medallion gd-medallion--sage">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
      </div>
      <p class="gd-stat-value">{{ $rataRataSkor }}<span class="gd-stat-unit">%</span></p>
      <p class="gd-stat-label">Rata-rata Skor</p>
    </div>

    <div class="gd-stat-card" style="--delay:0.18s">
      <div class="gd-medallion gd-medallion--gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 13l4 4L19 7"/>
        </svg>
      </div>
      <p class="gd-stat-value">{{ $totalLencanaDiberikan }}</p>
      <p class="gd-stat-label">Lencana Diberikan</p>
    </div>

  </div>

  {{-- ═══ Bottom Grid ═══ --}}
  <div class="gd-grid">

    {{-- Leaderboard --}}
    <div class="gd-panel gd-panel--wide">
      <div class="gd-panel-head">
        <div>
          <p class="gd-panel-title">Peringkat Kelas {{ $namaKelas }}</p>
          <p class="gd-panel-sub">Berdasarkan poin hafalan terkumpul</p>
        </div>
        <a href="{{ route('guru.siswa.index') }}" class="gd-link-btn text-decoration-none">Lihat Semua</a>
      </div>

      @if($leaderboard->isEmpty())
        <div class="gd-empty">Belum ada data siswa di kelas ini.</div>
      @else
        {{-- Podium 3 besar --}}
        <div class="gd-podium">
          @if($top3->count() >= 2)
            <div class="gd-podium-col">
              <div class="gd-medallion gd-medallion--silver gd-medallion--sm">
                {{ strtoupper(substr($top3[1]->name, 0, 2)) }}
              </div>
              <p class="gd-podium-name" title="{{ $top3[1]->name }}">{{ $top3[1]->name }}</p>
              <p class="gd-podium-poin">{{ number_format($top3[1]->points) }} poin</p>
              <div class="gd-podium-block gd-podium-block--silver">2</div>
            </div>
          @endif

          @if($top3->count() >= 1)
            <div class="gd-podium-col gd-podium-col--first">
              <div class="gd-medallion gd-medallion--gold gd-medallion--lg">
                {{ strtoupper(substr($top3[0]->name, 0, 2)) }}
                <span class="gd-medallion-star">✦</span>
              </div>
              <p class="gd-podium-name gd-podium-name--first" title="{{ $top3[0]->name }}">{{ $top3[0]->name }}</p>
              <p class="gd-podium-poin gd-podium-poin--first">{{ number_format($top3[0]->points) }} poin</p>
              <div class="gd-podium-block gd-podium-block--gold">1</div>
            </div>
          @endif

          @if($top3->count() >= 3)
            <div class="gd-podium-col">
              <div class="gd-medallion gd-medallion--bronze gd-medallion--sm">
                {{ strtoupper(substr($top3[2]->name, 0, 2)) }}
              </div>
              <p class="gd-podium-name" title="{{ $top3[2]->name }}">{{ $top3[2]->name }}</p>
              <p class="gd-podium-poin">{{ number_format($top3[2]->points) }} poin</p>
              <div class="gd-podium-block gd-podium-block--bronze">3</div>
            </div>
          @endif
        </div>

        {{-- List rank 4-6 --}}
        @if($rank4to6->isNotEmpty())
        <div class="gd-rank-list">
          @foreach($rank4to6 as $siswa)
            <div class="gd-rank-row">
              <span class="gd-rank-num">{{ $loop->iteration + 3 }}</span>
              <div class="gd-rank-avatar">{{ strtoupper(substr($siswa->name, 0, 2)) }}</div>
              <span class="gd-rank-name">{{ $siswa->name }}</span>
              <span class="gd-rank-poin">{{ number_format($siswa->points) }} poin</span>
            </div>
          @endforeach
        </div>
        @endif
      @endif
    </div>

    {{-- Right Column --}}
    <div class="gd-side">

      {{-- Aksi Cepat --}}
      <div class="gd-panel">
        <p class="gd-panel-title gd-panel-title--sm">Aksi Cepat</p>
        <div class="gd-quick-grid">
          <a href="{{ route('guru.quiz.index') }}" class="gd-quick-btn">
            <span class="gd-quick-icon">📝</span>
            <span>Buat Quiz</span>
          </a>
          <a href="{{ route('guru.soal.index') }}" class="gd-quick-btn">
            <span class="gd-quick-icon">📋</span>
            <span>Bank Soal</span>
          </a>
          <a href="{{ route('guru.siswa.index') }}" class="gd-quick-btn">
            <span class="gd-quick-icon">👥</span>
            <span>Data Siswa</span>
          </a>
          <a href="{{ route('guru.quiz.index') }}" class="gd-quick-btn">
            <span class="gd-quick-icon">📊</span>
            <span>Hasil Quiz</span>
          </a>
        </div>
      </div>

      {{-- Daftar Lencana --}}
      <div class="gd-panel gd-panel--grow">
        <p class="gd-panel-title gd-panel-title--sm">Daftar Lencana Tersedia</p>
        <div class="gd-lencana-list">
          @forelse($lencanaList as $lencana)
            <div class="gd-lencana-row">
              <span class="gd-lencana-icon">{{ $lencana->icon ?? '🏅' }}</span>
              <span class="truncate">{{ $lencana->name }}</span>
            </div>
          @empty
            <div class="gd-empty gd-empty--sm">Belum ada lencana.</div>
          @endforelse
        </div>
      </div>

    </div>
  </div>

</div>

<style>
.guru-dash {
  --ink: #1F2A24;
  --sage: #6E8577;
  --emerald-900: #0F2E22;
  --emerald-700: #115E59;
  --emerald-100: #E4F0E9;
  --sand: #FAF6EC;
  --gold: #B8860B;
  --gold-soft: #F4E7C8;
  --silver: #8A97A0;
  --bronze: #B5773E;
  --line: #ECE7DC;
  font-family: 'Plus Jakarta Sans', sans-serif;
  color: var(--ink);
}

/* ── Header ── */
.gd-header { margin-bottom: 20px; }
.gd-eyebrow {
  font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
  color: var(--emerald-700); margin-bottom: 6px;
}
.gd-title {
  font-family: 'Amiri', serif; font-weight: 700; font-size: 24px; color: var(--emerald-900);
  line-height: 1.2; margin-bottom: 4px;
}
.gd-subtitle { font-size: 12px; color: var(--sage); }

@media (min-width: 640px) {
  .gd-header { margin-bottom: 26px; }
  .gd-title { font-size: 30px; margin-bottom: 6px; }
  .gd-subtitle { font-size: 13.5px; }
}

/* ── Stat cards ── */
.gd-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 20px; }
.gd-stat-card {
  background: #fff; border: 1px solid var(--line); border-radius: 16px; padding: 16px;
  animation: gd-rise 0.5s ease both; animation-delay: var(--delay, 0s);
}
.gd-stat-value { font-size: 24px; font-weight: 800; color: var(--emerald-900); line-height: 1; margin-top: 10px; font-variant-numeric: tabular-nums; }
.gd-stat-unit { font-size: 14px; font-weight: 700; color: var(--sage); }
.gd-stat-label { font-size: 11px; color: var(--sage); font-weight: 600; margin-top: 4px; }

@media (min-width: 768px) {
  .gd-stats { grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
  .gd-stat-card { padding: 20px 20px 18px; }
  .gd-stat-value { font-size: 30px; margin-top: 14px; }
  .gd-stat-unit { font-size: 16px; }
  .gd-stat-label { font-size: 12px; }
}

/* ── Medali oktagon (elemen ciri khas) ── */
.gd-medallion {
  width: 40px; height: 40px;
  clip-path: polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%);
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-weight: 700; font-size: 12px; position: relative; flex-shrink: 0;
}
.gd-medallion svg { width: 18px; height: 18px; }
.gd-medallion--blue   { background: linear-gradient(145deg,#3b6fa8,#274d7c); }
.gd-medallion--gold   { background: linear-gradient(145deg,var(--gold),#8f6607); }
.gd-medallion--sage   { background: linear-gradient(145deg,var(--emerald-700),var(--emerald-900)); }
.gd-medallion--silver { background: linear-gradient(145deg,#a7b1b8,#6d7880); }
.gd-medallion--bronze { background: linear-gradient(145deg,var(--bronze),#8a5527); }
.gd-medallion--sm { width: 40px; height: 40px; font-size: 12px; margin-bottom: 6px; }
.gd-medallion--lg { width: 52px; height: 52px; font-size: 16px; margin-bottom: 6px; box-shadow: 0 0 0 3px var(--gold-soft); }

@media (min-width: 640px) {
  .gd-medallion { width: 44px; height: 44px; }
  .gd-medallion svg { width: 20px; height: 20px; }
  .gd-medallion--sm { width: 46px; height: 46px; font-size: 14px; margin-bottom: 8px; }
  .gd-medallion--lg { width: 60px; height: 60px; font-size: 18px; margin-bottom: 8px; box-shadow: 0 0 0 4px var(--gold-soft); }
}

.gd-medallion-star {
  position: absolute; top: -8px; right: -4px; color: var(--gold); font-size: 13px;
  filter: drop-shadow(0 1px 1px rgba(0,0,0,0.15));
}

/* ── Grid bawah ── */
.gd-grid { display: grid; grid-template-columns: 1fr; gap: 16px; align-items: start; }
.gd-panel { background: #fff; border: 1px solid var(--line); border-radius: 18px; padding: 16px; }
.gd-panel--grow { flex: 1; }
.gd-side { display: flex; flex-direction: column; gap: 16px; }

@media (min-width: 900px) {
  .gd-grid { grid-template-columns: 2fr 1fr; gap: 18px; }
  .gd-panel { padding: 22px; }
  .gd-side { gap: 18px; }
}

.gd-panel-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
.gd-panel-title { font-family: 'Amiri', serif; font-weight: 700; font-size: 16px; color: var(--emerald-900); }
.gd-panel-title--sm { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 700; margin-bottom: 12px; }
.gd-panel-sub { font-size: 11px; color: var(--sage); margin-top: 2px; }
.gd-link-btn {
  font-size: 11px; font-weight: 700; color: var(--emerald-700);
  border: 1px solid var(--emerald-700); background: none; border-radius: 8px;
  padding: 5px 10px; cursor: pointer; transition: all .15s; display: inline-block;
}
.gd-link-btn:hover { background: var(--emerald-700); color: #fff; }

.gd-empty { text-align: center; padding: 24px 10px; color: var(--sage); font-size: 12px; }
.gd-empty--sm { padding: 14px 10px; }

/* ── Podium ── */
.gd-podium { display: flex; align-items: flex-end; justify-content: center; gap: 10px; margin-bottom: 20px; }
.gd-podium-col { display: flex; flex-direction: column; align-items: center; }
.gd-podium-name { font-size: 11px; font-weight: 600; color: #3d4b43; text-align: center; max-width: 75px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.gd-podium-name--first { font-weight: 700; color: var(--emerald-900); }
.gd-podium-poin { font-size: 10px; color: var(--sage); margin-bottom: 6px; }
.gd-podium-poin--first { color: var(--gold); font-weight: 700; }
.gd-podium-block {
  width: 62px; border-radius: 10px 10px 0 0; display: flex; align-items: flex-start; justify-content: center;
  padding-top: 6px; font-family: 'Amiri', serif; font-weight: 700; font-size: 18px; color: #fff;
}
.gd-podium-block--silver { height: 60px; background: linear-gradient(180deg,#9aa5ac,#6d7880); }
.gd-podium-block--gold   { height: 84px; background: linear-gradient(180deg,var(--gold),#8f6607); }
.gd-podium-block--bronze { height: 48px; background: linear-gradient(180deg,var(--bronze),#8a5527); }

@media (min-width: 640px) {
  .gd-podium { gap: 18px; margin-bottom: 24px; }
  .gd-podium-name { font-size: 12px; max-width: 90px; }
  .gd-podium-poin { font-size: 11px; margin-bottom: 10px; }
  .gd-podium-block { width: 74px; font-size: 20px; padding-top: 8px; }
  .gd-podium-block--silver { height: 78px; }
  .gd-podium-block--gold   { height: 104px; }
  .gd-podium-block--bronze { height: 60px; }
}

/* ── Rank list 4-6 ── */
.gd-rank-list { border-top: 1px solid var(--line); padding-top: 6px; }
.gd-rank-row { display: flex; align-items: center; gap: 10px; padding: 9px 4px; border-bottom: 1px solid #f4f1ea; }
.gd-rank-row:last-child { border-bottom: none; }
.gd-rank-num { font-size: 11.5px; font-weight: 700; color: #b4ab98; width: 14px; }
.gd-rank-avatar {
  width: 28px; height: 28px; border-radius: 50%; background: var(--emerald-100); color: var(--emerald-700);
  display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;
}
.gd-rank-name { flex: 1; font-size: 12px; font-weight: 600; color: #3d4b43; truncate: true; }
.gd-rank-poin { font-size: 11.5px; font-weight: 700; color: var(--emerald-700); }

/* ── Aksi Cepat ── */
.gd-quick-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.gd-quick-btn {
  display: flex; flex-direction: column; align-items: center; gap: 6px;
  background: var(--sand); border: 1px solid var(--line); border-radius: 12px;
  padding: 12px 6px; font-size: 11px; font-weight: 600; color: var(--emerald-900);
  text-decoration: none; transition: all .15s; text-align: center;
}
.gd-quick-btn:hover { background: var(--emerald-700); color: #fff; border-color: var(--emerald-700); }
.gd-quick-icon { font-size: 18px; }

/* ── Lencana list ── */
.gd-lencana-list { display: flex; flex-direction: column; gap: 8px; }
.gd-lencana-row {
  display: flex; align-items: center; gap: 10px; padding: 10px 12px;
  background: var(--sand); border-radius: 10px; font-size: 12px; font-weight: 600; color: #3d4b43;
}
.gd-lencana-icon { font-size: 16px; }

/* ── Motion ── */
@keyframes gd-rise { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
@media (prefers-reduced-motion: reduce) {
  .gd-stat-card { animation: none; }
}
</style>

@endsection

@section('scripts')
{{-- Tidak ada script tambahan diperlukan; seluruh animasi ditangani lewat CSS --}}
@endsection