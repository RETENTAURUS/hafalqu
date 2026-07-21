@extends('layouts.siswa')

@section('title', 'Daftar Quiz — HafalQU')
@section('page_title', 'Jalur Hafalan')
@section('page_subtitle', 'Setiap quiz adalah satu langkah di jalurmu. Taklukkan berurutan, kumpulkan mahkota.')

@section('content')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&display=swap');

  .hq-wrap {
    --forest: #1a3a2e;
    --leaf: #2d7a5f;
    --leaf-light: #6fc9a8;
    --gold: #d4a843;
    --gold-light: #ffe9a8;
    --cream: #fbf9f3; /* Warna dasar krem hangat */
    --taupe: #b7ae9e;
    --line-done: linear-gradient(180deg, #2d7a5f, #6fc9a8);
    --line-todo: repeating-linear-gradient(180deg, #ded6c6 0px, #ded6c6 6px, transparent 6px, transparent 12px);
    
    max-width: 720px;
    margin: 0 auto;
    padding: 30px 24px;
    border-radius: 32px;
    position: relative;
    overflow: hidden;
    background-color: var(--cream);
    
    /* Efek Pola Titik Peta Petualangan (Dot Matrix Path) */
    background-image: radial-gradient(#e6dfd1 1.5px, transparent 1.5px);
    background-size: 24px 24px;
    box-shadow: inset 0 0 40px rgba(26,58,46,0.03), 0 8px 24px rgba(0,0,0,0.02);
  }

  /* Elemen Ornamen Mengapung Organik (Bukan buatan AI kaku) */
  .hq-wrap::before, .hq-wrap::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    z-index: 0;
    pointer-events: none;
  }
  /* Bola dekoratif hijau samar di pojok kiri atas */
  .hq-wrap::before {
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(111,201,168,0.12) 0%, transparent 70%);
    top: -50px; left: -80px;
  }
  /* Bola dekoratif emas samar di pojok kanan bawah */
  .hq-wrap::after {
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(255,233,168,0.2) 0%, transparent 70%);
    bottom: -50px; right: -80px;
  }

  .hq-wrap h2, .hq-wrap .hq-display {
    font-family: 'Baloo 2', sans-serif;
  }

  /* Animasi Khas Game */
  @keyframes hq-current-pulse {
    0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(45,122,95,0.4), 0 8px 0 #1a3a2e; }
    50% { transform: scale(1.04); box-shadow: 0 0 0 12px rgba(45,122,95,0), 0 8px 0 #1a3a2e; }
  }
  @keyframes hq-flag-wave {
    0%, 100% { transform: rotate(-5deg) translateY(0); }
    50% { transform: rotate(5deg) translateY(-3px); }
  }
  @keyframes hq-crown-glint {
    0%, 100% { transform: scale(1) rotate(0deg); }
    50% { transform: scale(1.1) rotate(5deg); filter: drop-shadow(0 0 6px rgba(212,168,67,0.6)); }
  }

  .hq-node-current { animation: hq-current-pulse 2s ease-in-out infinite; }
  .hq-flag { animation: hq-flag-wave 1.4s ease-in-out infinite; transform-origin: bottom left; }
  .hq-crown-anim { animation: hq-crown-glint 2.5s ease-in-out infinite; position: relative; z-index: 1; }

  /* Track & Grid Base */
  .hq-track { position: relative; padding: 20px 0 40px; z-index: 1; }
  .hq-segment {
    position: absolute;
    left: 50%;
    width: 6px;
    transform: translateX(-50%);
    border-radius: 4px;
  }

  .hq-row { display: grid; grid-template-columns: 1fr 80px 1fr; align-items: center; column-gap: 16px; position: relative; z-index: 1; }
  
  .hq-row.hq-left  .hq-callout { grid-column: 1; justify-self: end; text-align: right; transform: rotate(-0.5deg); }
  .hq-row.hq-left  .hq-spacer  { grid-column: 3; }
  .hq-row.hq-right .hq-callout { grid-column: 3; justify-self: start; text-align: left; transform: rotate(0.5deg); }
  .hq-row.hq-right .hq-spacer  { grid-column: 1; }
  .hq-row .hq-node { grid-column: 2; justify-self: center; }

  /* Kartu Callout ala Dialog Game */
  .hq-callout {
    position: relative;
    background: #fff;
    border-radius: 18px;
    padding: 14px 18px;
    max-width: 280px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 0px rgba(26,58,46,0.04);
  }
  .hq-callout:hover {
    transform: translateY(-2px) scale(1.02) !important;
    box-shadow: 0 8px 16px rgba(26,58,46,0.06);
  }

  /* Segitiga Petunjuk Arah balon dialog */
  .hq-row.hq-left .hq-callout::after {
    content: ''; position: absolute; right: -8px; top: 50%; transform: translateY(-50%);
    border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 9px solid #fff;
  }
  .hq-row.hq-right .hq-callout::after {
    content: ''; position: absolute; left: -8px; top: 50%; transform: translateY(-50%);
    border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-right: 9px solid #fff;
  }

  /* Tombol 3D Gaya Kasual/Gamifikasi */
  .hq-btn-3d {
    display: inline-block;
    padding: 6px 16px;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    border-radius: 14px;
    transition: transform 0.1s ease, box-shadow 0.1s ease;
  }
  .hq-btn-primary {
    background: #2d7a5f;
    color: #fff;
    box-shadow: 0 4px 0 #1a3a2e;
  }
  .hq-btn-primary:active {
    transform: translateY(3px);
    box-shadow: 0 1px 0 #1a3a2e;
  }
  .hq-btn-secondary {
    background: #fff;
    color: #2d7a5f;
    border: 2px solid #2d7a5f;
    box-shadow: 0 3px 0 #2d7a5f;
  }
  .hq-btn-secondary:active {
    transform: translateY(2px);
    box-shadow: 0 1px 0 #2d7a5f;
  }

  /* Tab Switcher Jalur */
  .hq-tab-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    font-family: 'Baloo 2', sans-serif;
    font-size: 13px;
    font-weight: 800;
    color: #8a8272;
    background: #fff;
    border: 2px solid #e3ded3;
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .hq-tab-btn:hover { border-color: #6fc9a8; }
  .hq-tab-btn.hq-tab-active {
    color: #fff;
    background: linear-gradient(135deg,#2d7a5f,#1a3a2e);
    border-color: #1a3a2e;
    box-shadow: 0 3px 0 #132b22;
  }
  .hq-tab-count {
    font-size: 10px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 8px;
    background: rgba(255,255,255,0.25);
  }
  .hq-tab-btn:not(.hq-tab-active) .hq-tab-count {
    background: #f0ede2;
    color: #5c6960;
  }

  /* Responsif Mobile */
  @media (max-width: 640px) {
    .hq-row { grid-template-columns: 64px 1fr; column-gap: 12px; }
    .hq-row .hq-node { grid-column: 1; }
    .hq-row.hq-left .hq-callout, .hq-row.hq-right .hq-callout {
      grid-column: 2; justify-self: start; text-align: left; max-width: none; transform: none !important;
    }
    .hq-row.hq-left .hq-callout::after, .hq-row.hq-right .hq-callout::after {
      left: -8px; right: auto; border-left: none; border-right: 9px solid #fff;
    }
    .hq-segment { left: 32px; }
  }
</style>

@php
  $totalSemua = $sekolahQuizzes->count() + $rumahQuizzes->count();

  $mahkotaSekolah = $sekolahQuizzes->filter(fn($q) => ($sekolahStatus[$q->id]['isPerfect'] ?? false))->count();
  $mahkotaRumah   = $rumahQuizzes->filter(fn($q) => ($rumahStatus[$q->id]['isPerfect'] ?? false))->count();
  $totalMahkota   = $mahkotaSekolah + $mahkotaRumah;
@endphp

<div class="hq-wrap">

  {{-- Top Bar / Stats Ringkas (gabungan kedua jalur) --}}
  <div style="position: relative; z-index: 1; display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; flex-wrap:wrap; gap:10px;">
    <div style="display:flex; align-items:center; gap:12px;">
      <div style="width:46px; height:46px; border-radius:14px; background:linear-gradient(135deg,#2d7a5f,#1a3a2e); display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 0 #132b22;">
        <svg style="width:22px;height:22px;color:#ffe9a8;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 11l18-8-8 18-2-8-8-2z"/></svg>
      </div>
      <div>
        <p class="hq-display" style="font-size:16px; font-weight:800; color:#1e3a2a; margin:0; line-height:1.2;">Jalur Hafalanmu</p>
        <p style="font-size:12px; color:#8a8272; margin:0;">{{ $totalSemua }} pos tantangan</p>
      </div>
    </div>
    <div class="hq-crown-anim" style="display:flex; align-items:center; gap:8px; background:#fff; border:2px solid #ecd9a0; padding:6px 14px; border-radius:20px; box-shadow: 0 3px 0 #ecd9a0;">
      <svg style="width:18px;height:18px;color:#d4a843;" viewBox="0 0 24 24" fill="currentColor"><path d="M2 20h20l-1.6-9.4-4.9 4-2.5-8-2.5 8-4.9-4L2 20z"/></svg>
      <span class="hq-display" style="font-size:13px; font-weight:800; color:#7a5a1a;">{{ $totalMahkota }} Mahkota</span>
    </div>
  </div>

  {{-- Tab Switcher 2 Jalur --}}
  <div style="position: relative; z-index: 1; display:flex; gap:10px; margin-bottom:24px;">
    <button type="button" onclick="hqSwitchTrack('sekolah')" id="hq-tab-sekolah" class="hq-tab-btn hq-tab-active">
      🏫 Di Sekolah <span class="hq-tab-count">{{ $mahkotaSekolah }}/{{ $sekolahQuizzes->count() }}</span>
    </button>
    <button type="button" onclick="hqSwitchTrack('rumah')" id="hq-tab-rumah" class="hq-tab-btn">
      🏠 Di Rumah <span class="hq-tab-count">{{ $mahkotaRumah }}/{{ $rumahQuizzes->count() }}</span>
    </button>
  </div>

  <div id="hq-track-sekolah">
    @include('siswa.quiz._jalur-track', [
        'list' => $sekolahQuizzes,
        'quizStatus' => $sekolahStatus,
        'emptyMessage' => 'Belum ada jalur di sekolah yang terbuka',
    ])
  </div>

  <div id="hq-track-rumah" style="display:none;">
    @include('siswa.quiz._jalur-track', [
        'list' => $rumahQuizzes,
        'quizStatus' => $rumahStatus,
        'emptyMessage' => 'Belum ada jalur di rumah yang terbuka',
    ])
  </div>

</div>

<script>
  function hqSwitchTrack(type) {
    document.getElementById('hq-track-sekolah').style.display = type === 'sekolah' ? '' : 'none';
    document.getElementById('hq-track-rumah').style.display = type === 'rumah' ? '' : 'none';
    document.getElementById('hq-tab-sekolah').classList.toggle('hq-tab-active', type === 'sekolah');
    document.getElementById('hq-tab-rumah').classList.toggle('hq-tab-active', type === 'rumah');
  }
</script>

@endsection