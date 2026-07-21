@extends('layouts.siswa')

@section('title', 'Dashboard Siswa — HafalQU')
@section('page_title', 'Dashboard Siswa')
@section('page_subtitle', 'Selamat datang, ' . Auth::user()->name)

@section('styles')
<style>
  .hero-card {
    background: #1a3a2e;
    border-radius: 16px;
    padding: 20px 24px;
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
    margin-bottom: 20px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.08);
  }

  .hero-divider {
    height: 1px;
    width: 100%;
    background: rgba(255, 255, 255, 0.12);
  }

  @media (min-width: 768px) {
    .hero-card {
      grid-template-columns: 1fr auto 1.6fr auto 1fr;
      align-items: center;
      gap: 0;
      padding: 24px 28px;
    }

    .hero-divider {
      width: 1px;
      height: 100%;
      align-self: stretch;
      margin: 0 22px;
    }
  }

  .quick-action-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1px;
    background: #f0ede6;
  }
</style>
@endsection

@section('content')

{{-- ═══ Hero Card ═══ --}}
<div class="hero-card">

  {{-- Dekorasi lingkaran --}}
  <div style="position:absolute;right:-40px;top:-50px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.03);pointer-events:none;"></div>
  <div style="position:absolute;right:60px;bottom:-60px;width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,0.025);pointer-events:none;"></div>

  {{-- Kolom 1: Total Poin --}}
  <div style="position:relative;">
    <p style="font-size:10px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:#8ab89e;margin-bottom:6px;">Total Poin</p>
    <p style="font-size:36px;font-weight:800;color:#fff;line-height:1;">{{ $totalPoin }}</p>
    <p style="font-size:11px;color:#7aab8c;margin-top:4px;">Poin hafalan</p>
    <span style="display:inline-flex;align-items:center;gap:5px;background:#d4a843;color:#5a3200;
                 font-size:10px;font-weight:700;padding:4px 10px;border-radius:20px;margin-top:10px;">
      ★ {{ $levelNama }}
    </span>
  </div>

  <div class="hero-divider"></div>

  {{-- Kolom 2: Progress Level --}}
  <div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
      <p style="font-size:10px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:#8ab89e;">
        Progress ke {{ $labelNextLevel }}
      </p>
      <span style="font-size:10px;color:#8ab89e;font-weight:600;">{{ $progress }}%</span>
    </div>

    <div style="display:flex;align-items:baseline;gap:6px;margin-bottom:8px;">
      <span style="font-size:24px;font-weight:800;color:#fff;">{{ $totalPoin }}</span>
      <span style="font-size:12px;color:#7aab8c;">/ {{ $poinTarget }}</span>
    </div>

    <div style="height:8px;background:rgba(255,255,255,0.12);border-radius:4px;overflow:hidden;">
      <div style="height:100%;background:linear-gradient(90deg,#d4a843,#e8c064);border-radius:4px;
                  width:{{ $progress }}%;transition:width 1s ease;"></div>
    </div>

    <div style="display:flex;justify-content:space-between;margin-top:6px;">
      <span style="font-size:10px;color:#7aab8c;">{{ $progress }}% tercapai</span>
      @if($sisaPoin > 0)
        <span style="font-size:10px;color:#7aab8c;">{{ $sisaPoin }} poin lagi</span>
      @else
        <span style="font-size:10px;color:#d4a843;font-weight:700;">Level tertinggi!</span>
      @endif
    </div>

    {{-- Lencana dots --}}
    <div style="display:flex;align-items:center;gap:6px;margin-top:12px;">
      <span style="font-size:10px;color:#8ab89e;font-weight:600;">Lencana</span>
      <div style="display:flex;align-items:center;gap:4px;">
        @for ($i = 1; $i <= max($totalLencana, 1); $i++)
          <div style="width:14px;height:14px;border-radius:50%;
                      background:{{ $i <= $lencanaDiraih ? '#d4a843' : 'rgba(255,255,255,0.15)' }};
                      transition:background 0.3s;"></div>
        @endfor
      </div>
      <span style="font-size:10px;color:#8ab89e;margin-left:2px;font-weight:600;">{{ $lencanaDiraih }}/{{ $totalLencana }}</span>
    </div>
  </div>

  <div class="hero-divider"></div>

  {{-- Kolom 3: Peringkat --}}
  <div>
    <p style="font-size:10px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:#8ab89e;margin-bottom:6px;">Peringkat Kelas</p>
    <p style="font-size:38px;font-weight:800;color:#d4a843;line-height:1;">#{{ $peringkat }}</p>
    <p style="font-size:11px;color:#7aab8c;margin-top:4px;">Dari {{ $totalSiswa }} siswa</p>
  </div>
</div>

{{-- ═══ Bottom Grid ═══ --}}
<div style="display:grid;grid-template-columns:1fr;gap:16px;" class="md:grid-cols-2">

  {{-- Panel: Daftar Quiz --}}
  <div style="background:#fff;border-radius:14px;border:1px solid #e8e4dc;overflow:hidden;box-shadow: 0 2px 8px -2px rgba(0,0,0,0.04);">
    <div style="padding:14px 16px 12px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #f0ede6;">
      <span style="font-size:13px;font-weight:700;color:#1e3a2a;">Daftar Quiz Tersedia</span>
      <a href="{{ route('siswa.quiz.index') }}"
         style="font-size:11px;color:#2d7a5f;font-weight:600;text-decoration:none;">Lihat Semua</a>
    </div>

    @forelse($quizzes as $quiz)
    <div style="display:flex;align-items:center;padding:12px 16px;
                border-bottom:{{ $loop->last ? 'none' : '1px solid #f5f2ec' }};
                transition:background 0.15s;"
         onmouseover="this.style.background='#faf8f5'" onmouseout="this.style.background='transparent'">
      <span style="font-size:11px;color:#a09882;font-weight:700;min-width:22px;">{{ $loop->iteration }}</span>
      <div style="flex:1;min-width:0;padding-right:8px;">
        <p style="font-size:12px;font-weight:600;color:#2d3a33;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
          {{ $quiz->title }}
        </p>
        <p style="font-size:10px;color:#a09882;margin-top:2px;">{{ $quiz->soals->count() }} soal</p>
      </div>
      <a href="{{ route('siswa.quiz.index') }}"
         style="background:#1a3a2e;color:#fff;font-size:11px;font-weight:600;
                padding:6px 12px;border-radius:8px;text-decoration:none;white-space:nowrap;flex-shrink:0;">
        Kerjakan
      </a>
    </div>
    @empty
    <div style="padding:32px 16px;text-align:center;color:#a09882;font-size:12px;">
      Belum ada quiz tersedia.
    </div>
    @endforelse
  </div>

  {{-- Panel: Aksi Cepat --}}
  <div style="background:#fff;border-radius:14px;border:1px solid #e8e4dc;overflow:hidden;box-shadow: 0 2px 8px -2px rgba(0,0,0,0.04);">
    <div style="padding:14px 16px 12px;border-bottom:1px solid #f0ede6;">
      <span style="font-size:13px;font-weight:700;color:#1e3a2a;">Aksi Cepat</span>
    </div>
    <div class="quick-action-grid">
      @php
        $aksi = [
          [
            'bg'    => '#e1f5f0', 'color' => '#1a7a5e',
            'label' => 'Menu Quiz',
            'href'  => route('siswa.quiz.index'),
            'icon'  => '<svg style="width:20px;height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>',
          ],
          [
            'bg'    => '#fdf3e0', 'color' => '#a06900',
            'label' => 'Lihat Peringkat',
            'href'  => route('siswa.leaderboard'),
            'icon'  => '<svg style="width:20px;height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/></svg>',
          ],
          [
            'bg'    => '#fdf0d0', 'color' => '#8a6000',
            'label' => 'Lihat Lencana',
            'href'  => route('siswa.lencana.index'),
            'icon'  => '<svg style="width:20px;height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>',
          ],
          [
            'bg'    => '#e8f2fc', 'color' => '#1a5a99',
            'label' => 'Riwayat Quiz',
            'href'  => route('siswa.riwayat'),
            'icon'  => '<svg style="width:20px;height:20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
          ],
        ];
      @endphp

      @foreach($aksi as $item)
      <a href="{{ $item['href'] }}"
         style="background:#fff;display:flex;flex-direction:column;align-items:center;
                justify-content:center;padding:18px 12px;text-decoration:none;
                transition:background 0.15s;"
         onmouseover="this.style.background='#f7f5f0'" onmouseout="this.style.background='#fff'">
        <div style="width:40px;height:40px;border-radius:11px;background:{{ $item['bg'] }};
                    color:{{ $item['color'] }};display:flex;align-items:center;
                    justify-content:center;margin-bottom:8px;">
          {!! $item['icon'] !!}
        </div>
        <span style="font-size:11px;font-weight:600;color:#3d4a42;text-align:center;line-height:1.4;">
          {{ $item['label'] }}
        </span>
      </a>
      @endforeach
    </div>
  </div>

</div>
@endsection