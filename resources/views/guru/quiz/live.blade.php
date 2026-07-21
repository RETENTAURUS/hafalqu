@extends('layouts.guru')

@section('title', 'Pantau Live — ' . $quiz->title)
@section('page_title', 'Pemantauan Live')
@section('page_subtitle', $quiz->title)

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
  /* ===== ANIMASI CERIA ===== */
  @keyframes ping {
    0%, 100% { transform: scale(1); opacity: .75 } 50% { transform: scale(1.6); opacity: 0 }
  }
  @keyframes countUp {
    0% { transform: scale(1.4); filter: brightness(1.3); } 60% { transform: scale(0.95) } 100% { transform: scale(1); }
  }
  @keyframes slideIn {
    0% { opacity: 0; transform: translateY(12px); } 100% { opacity: 1; transform: translateY(0); }
  }
  @keyframes shimmer {
    0% { background-position: -200% 0 } 100% { background-position: 200% 0 }
  }
  @keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-4px) rotate(1deg); }
  }
  @keyframes scorePop {
    0% { transform: scale(0.4); opacity: 0 } 70% { transform: scale(1.25) } 100% { transform: scale(1); opacity: 1 }
  }
  @keyframes confettiDrop {
    0% { transform: translateY(-10px) rotate(0deg); opacity: 1 }
    100% { transform: translateY(40px) rotate(720deg); opacity: 0 }
  }
  @keyframes pulseGlow {
    0%, 100% { transform: scale(1); box-shadow: 0 4px 0 #E2E8F0; }
    50% { transform: scale(1.01); box-shadow: 0 6px 12px rgba(56, 189, 248, 0.2); }
  }

  /* ===== CLASSES UTAMA ===== */
  .live-monitor { font-family: 'Nunito', 'Segoe UI', sans-serif; }
  .ping { animation: ping 1.4s ease-in-out infinite; }
  .count-animate { animation: countUp .4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
  .row-new { animation: slideIn .4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
  .float-effect { animation: float 3s ease-in-out infinite; }
  .score-pop { animation: scorePop .4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

  /* ===== KOMPONEN KARTU GAMIFIKASI ===== */
  .card-gameplay {
    background: #ffffff;
    border: 3px solid #E2E8F0;
    box-shadow: 0 6px 0 #E2E8F0;
    border-radius: 20px;
    transition: all 0.2s ease;
  }
  .stat-card-fun {
    background: #ffffff;
    border: 3px solid #E2E8F0;
    box-shadow: 0 6px 0 #E2E8F0;
    border-radius: 18px;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  .stat-card-fun:hover {
    transform: translateY(-2px);
  }

  .progress-track {
    height: 10px;
    border-radius: 30px;
    background: #F1F5F9;
    border: 2px solid #E2E8F0;
    overflow: hidden;
    min-width: 100px;
    position: relative;
  }
  .progress-fill {
    height: 100%;
    border-radius: 30px;
    transition: width .6s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
  }
  .progress-fill::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite linear;
  }

  /* Grid Tabel Desktop */
  .student-row-desktop {
    display: grid;
    grid-template-columns: 38px 1fr 150px 90px 60px 70px;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-bottom: 2px solid #F1F5F9;
    font-size: 13px;
    transition: all 0.2s ease;
  }
  .student-row-desktop:last-child { border-bottom: none; }
  .student-row-desktop:hover {
    background: #F8FAFC;
    animation: pulseGlow 1.5s infinite alternate;
  }

  .avatar-zone {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 800;
    flex-shrink: 0;
    position: relative;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  .avatar-active::after {
    content: '';
    position: absolute;
    inset: -3px;
    border-radius: 50%;
    border: 2px solid #F59E0B;
    animation: ping 1.2s ease-in-out infinite;
  }
  .avatar-done::after {
    content: '✓';
    position: absolute;
    bottom: -2px;
    right: -2px;
    background: #10B981;
    color: white;
    font-size: 8px;
    font-weight: 900;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
  }

  .badge-fun {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 11px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 30px;
  }
  .badge-done { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
  .badge-active { background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; animation: float 2.5s ease-in-out infinite; }
  .badge-waiting { background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0; }

  .leaderboard-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    margin-bottom: 8px;
    background: #F8FAFC;
    border: 2px solid #E2E8F0;
    box-shadow: 0 3px 0 #E2E8F0;
    border-radius: 14px;
    font-size: 13px;
    transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  .leaderboard-item:hover {
    transform: scale(1.02);
    background: #FFF;
  }
  .leaderboard-item .rank-badge {
    width: 26px;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 12px;
    border-radius: 50%;
  }
  
  .leaderboard-item .score-tag {
    margin-left: auto;
    font-size: 11px;
    font-weight: 800;
    color: #0369A1;
    background: #E0F2FE;
    padding: 2px 8px;
    border-radius: 30px;
    border: 1px solid #BAE6FD;
  }
  .leaderboard-item .score-gold {
    background: linear-gradient(135deg, #FEF3C7, #FCD34D);
    color: #78350F;
    border: 1px solid #FDE68A;
  }

  .confetti-container {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    pointer-events: none; z-index: 9999; overflow: hidden;
  }
  .confetti-piece {
    position: absolute; width: 8px; height: 8px;
    animation: confettiDrop 1.6s ease-out forwards;
  }

  @keyframes spin {
    0%{transform:rotate(0deg)} 100%{transform:rotate(360deg)}
  }
</style>
@endsection

@section('content')
<div class="live-monitor max-w-7xl mx-auto px-0 sm:px-2 pb-8">

  {{-- Header Arena Live --}}
  <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4 sm:mb-6">
    <div class="flex items-center gap-3 bg-red-50 border-2 sm:border-3 border-red-300 px-3.5 py-2 rounded-full shadow-sm">
      <div class="relative w-3.5 h-3.5 flex-shrink-0">
        <div class="ping absolute inset-0 rounded-full bg-red-500 opacity-75"></div>
        <div class="relative w-3.5 h-3.5 rounded-full bg-red-600 shadow-sm"></div>
      </div>
      <div class="flex items-center gap-2">
        <span class="text-xs sm:text-sm font-black text-red-600 tracking-wider">ARENA LIVE MONITOR</span>
        <span id="updated-at" class="text-[11px] sm:text-xs text-red-800 font-bold opacity-75"></span>
      </div>
    </div>
    
    <div class="flex items-center gap-2.5 w-full sm:w-auto justify-between sm:justify-end">
      <span class="text-[11px] sm:text-xs text-slate-500 font-bold bg-slate-100 px-3 py-1.5 rounded-full">
        🔄 Refresh tiap 4 detik
      </span>
      <a href="{{ url()->previous() }}"
         class="text-xs sm:text-sm font-bold text-slate-600 bg-white border-2 border-slate-200 px-3.5 py-1.5 rounded-full shadow-sm hover:bg-slate-50 active:scale-95 transition-all">
        ← Keluar Arena
      </a>
    </div>
  </div>

  {{-- Informasi Kuis --}}
  <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 mb-4 sm:mb-6 p-3 sm:p-4 bg-gradient-to-r from-rose-100 to-rose-200 rounded-2xl border-2 sm:border-3 border-rose-300 shadow-sm">
    <div class="flex items-center gap-2">
      <span class="text-lg sm:text-xl">📝</span>
      <span class="text-xs sm:text-sm font-black text-rose-950">Kuis: {{ $quiz->title }}</span>
    </div>
    <span class="text-[11px] sm:text-xs bg-white text-rose-600 font-extrabold px-3 py-1 rounded-full border border-rose-300 self-start sm:self-auto">
      👥 {{ $quiz->participants_count ?? 0 }} Jagoan Terdaftar
    </span>
  </div>

  {{-- Grid Dasbor Statistik Makro --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-5 sm:mb-7">
    <div class="stat-card-fun border-slate-300 p-3.5 sm:p-5">
      <div class="flex items-center justify-between">
        <div>
          <p id="c-total" class="text-2xl sm:text-3xl font-black text-slate-800 leading-none">0</p>
          <p class="text-[11px] sm:text-xs font-bold text-slate-500 mt-1">Total Ikut</p>
        </div>
        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-slate-100 rounded-full flex items-center justify-center border border-slate-200 text-lg sm:text-2xl">👥</div>
      </div>
    </div>

    <div class="stat-card-fun border-amber-300 p-3.5 sm:p-5">
      <div class="flex items-center justify-between">
        <div>
          <p id="c-active" class="text-2xl sm:text-3xl font-black text-amber-600 leading-none">0</p>
          <p class="text-[11px] sm:text-xs font-bold text-amber-700 mt-1">Mengerjakan</p>
        </div>
        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-50 rounded-full flex items-center justify-center border border-amber-200 text-lg sm:text-2xl">⚡</div>
      </div>
    </div>

    <div class="stat-card-fun border-emerald-300 p-3.5 sm:p-5">
      <div class="flex items-center justify-between">
        <div>
          <p id="c-done" class="text-2xl sm:text-3xl font-black text-emerald-600 leading-none">0</p>
          <p class="text-[11px] sm:text-xs font-bold text-emerald-800 mt-1">Sudah Selesai</p>
        </div>
        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 rounded-full flex items-center justify-center border border-emerald-200 text-lg sm:text-2xl">✅</div>
      </div>
    </div>

    <div class="stat-card-fun border-indigo-300 p-3.5 sm:p-5">
      <div class="flex items-center justify-between">
        <div>
          <p id="c-avg" class="text-2xl sm:text-3xl font-black text-indigo-600 leading-none">0</p>
          <p class="text-[11px] sm:text-xs font-bold text-indigo-800 mt-1">Rata-rata Skor</p>
        </div>
        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-50 rounded-full flex items-center justify-center border border-indigo-200 text-lg sm:text-2xl">🎯</div>
      </div>
    </div>
  </div>

  {{-- Grid Utama Pemantauan --}}
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-start">

    {{-- Tabel Aktivitas Siswa (8 Kolom di Desktop) --}}
    <div class="lg:col-span-8 card-gameplay overflow-hidden">
      <div class="px-4 py-3 bg-slate-50 border-b-2 border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="text-base sm:text-lg">🏃‍♂️</span>
          <span class="text-xs sm:text-sm font-extrabold text-slate-800">Perjuangan Para Jagoan</span>
        </div>
        <span id="student-count" class="text-[10px] sm:text-xs font-black bg-indigo-600 text-white px-2.5 py-0.5 rounded-full shadow-sm">0</span>
      </div>
      
      {{-- Label Tabel Kolom Desktop --}}
      <div class="hidden sm:grid student-row-desktop bg-slate-50 font-bold text-[11px] text-slate-400 uppercase tracking-wider border-b-2 border-slate-200 px-4 py-2">
        <span></span>
        <span>Nama Siswa</span>
        <span>Progress Bar</span>
        <span class="text-center">Status</span>
        <span class="text-center">Skor</span>
        <span class="text-center">Jam Mulai</span>
      </div>
      
      {{-- Area Suntikan Data Realtime via JS --}}
      <div id="student-list" class="max-h-[520px] overflow-y-auto divide-y divide-slate-100 p-1 sm:p-0">
        <div class="py-12 text-center text-slate-400">
          <svg class="w-7 h-7 mx-auto mb-2 text-slate-300 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
          <span class="text-xs sm:text-sm font-bold">Mempersiapkan Arena Live...</span>
        </div>
      </div>
    </div>

    {{-- Panel Pemimpin Turnamen (Leaderboard) (4 Kolom di Desktop) --}}
    <div class="lg:col-span-4 card-gameplay border-amber-300 shadow-sm sticky top-4">
      <div class="px-4 py-3 bg-gradient-to-r from-amber-50 to-amber-100 border-b-2 border-amber-300 flex items-center gap-2">
        <span class="text-base sm:text-lg">👑</span>
        <span class="text-xs sm:text-sm font-black text-amber-900">Papan Bintang Top 8</span>
      </div>
      <div class="p-3 sm:p-4">
        <ul id="leaderboard-list" class="space-y-2">
          <li class="py-8 text-center text-slate-400 text-xs font-bold">
            ⏳ Menunggu papan peringkat terisi...
          </li>
        </ul>
      </div>
    </div>

  </div>
</div>

<script>
(function () {
  const endpoint = "{{ route('guru.quiz.live-data', $quiz->id) }}";
  const POLL_MS  = 4000;

  let prevStudents = {};
  let prevScores = [];

  const avatarColors = [
    ['#E0F2FE','#0369A1'], ['#D1FAE5','#065F46'], ['#FEF3C7','#92400E'],
    ['#FCE7F3','#9D174D'], ['#E0E7FF','#3730A3'], ['#FFEDD5','#C2410C'],
    ['#F3E8FF','#6B21A8'], ['#E0F2FE','#0369A1'], ['#D1FAE5','#065F46']
  ];

  function avatarColor(name) {
    let h = 0;
    for (let i = 0; i < name.length; i++) h = (h * 31 + name.charCodeAt(i)) & 0xffff;
    return avatarColors[h % avatarColors.length];
  }

  function esc(s) {
    if (!s) return '';
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
  }

  function animateCount(el, newVal) {
    const oldVal = parseInt(el.textContent.replace(/,/g, '')) || 0;
    if (oldVal === newVal) return;
    el.textContent = newVal.toLocaleString();
    el.classList.remove('count-animate');
    void el.offsetWidth;
    el.classList.add('count-animate');
  }

  function barColor(pct, finished) {
    if (finished) return 'linear-gradient(90deg, #34D399, #059669)';
    if (pct >= 75) return 'linear-gradient(90deg, #38BDF8, #0284C7)';
    if (pct >= 40) return 'linear-gradient(90deg, #FBBF24, #D97706)';
    return 'linear-gradient(90deg, #CBD5E1, #94A3B8)';
  }

  function getStatusBadge(status) {
    if (status === 'selesai') {
      return `<span class="badge-fun badge-done">🎉 Selesai</span>`;
    } else if (status === 'aktif' || status === 'mengerjakan') {
      return `<span class="badge-fun badge-active">⚡ Aktif</span>`;
    }
    return `<span class="badge-fun badge-waiting">💤 Rehat</span>`;
  }

  function getAvatarClass(status) {
    if (status === 'selesai') return 'avatar-zone avatar-done';
    if (status === 'aktif' || status === 'mengerjakan') return 'avatar-zone avatar-active';
    return 'avatar-zone';
  }

  function getInitials(name) {
    return name.trim().split(' ').map(w => w[0]).slice(0,2).join('').toUpperCase();
  }

  // Confetti Selebrasi Gamifikasi
  function triggerConfetti() {
    const container = document.createElement('div');
    container.className = 'confetti-container';
    document.body.appendChild(container);
    
    const colors = ['#FF007F', '#FFB236', '#10B981', '#38BDF8', '#8B5CF6', '#FF5733'];
    const pieces = 35;
    
    for (let i = 0; i < pieces; i++) {
      const piece = document.createElement('div');
      piece.className = 'confetti-piece';
      const color = colors[Math.floor(Math.random() * colors.length)];
      const size = 5 + Math.random() * 8;
      piece.style.cssText = `
        left: ${Math.random() * 100}%;
        top: ${-10 + Math.random() * 15}%;
        background: ${color};
        width: ${size}px;
        height: ${size}px;
        animation-delay: ${Math.random() * 0.4}s;
        border-radius: ${Math.random() > 0.4 ? '50%' : '3px'};
      `;
      container.appendChild(piece);
    }
    
    setTimeout(() => container.remove(), 2000);
  }

  function checkNewHighScore(students) {
    const currentScores = students
      .filter(s => s.status === 'selesai' && s.score !== null)
      .map(s => s.score);
    
    if (currentScores.length > 0) {
      const maxCurrent = Math.max(...currentScores);
      const maxPrev = prevScores.length > 0 ? Math.max(...prevScores) : 0;
      
      if (maxCurrent > maxPrev && maxCurrent > 0) {
        triggerConfetti();
      }
    }
    prevScores = currentScores;
  }

  function render(data) {
    const students = data.students || [];

    // Perbarui Widget Angka Statistik Utama
    animateCount(document.getElementById('c-total'), data.summary.total_mengikuti || 0);
    animateCount(document.getElementById('c-active'), data.summary.sedang_mengerjakan || 0);
    animateCount(document.getElementById('c-done'), data.summary.sudah_selesai || 0);

    const finished = students.filter(s => s.status === 'selesai' && s.score !== null);
    const avgScore = finished.length > 0 
      ? Math.round(finished.reduce((a,b) => a + b.score, 0) / finished.length)
      : 0;
    animateCount(document.getElementById('c-avg'), avgScore);

    document.getElementById('updated-at').textContent = 'Live: ' + data.updated_at;
    document.getElementById('student-count').textContent = students.length + ' Anak';

    checkNewHighScore(students);

    // ── RENDER DAFTAR PERJUANGAN SISWA ──
    const listEl = document.getElementById('student-list');
    if (!students.length) {
      listEl.innerHTML = `<div class="py-12 text-center text-slate-400 font-bold text-xs sm:text-sm">
        <span class="text-3xl block mb-2">💤</span>
        <span>Belum ada ksatria yang memasuki arena kuis.</span>
      </div>`;
      return;
    }

    listEl.innerHTML = students.map(function(s) {
      const isNew = !prevStudents[s.attempt_id];
      const [bg, fg] = avatarColor(s.nama);
      const initials = getInitials(s.nama);
      const finished = s.status === 'selesai';
      const fill = barColor(s.percent || 0, finished);
      const barClass = !finished && s.status !== 'menunggu' ? 'bar-active' : '';
      const avatarClass = getAvatarClass(s.status);

      return `
      {{-- Mobile Card View --}}
      <div class="block sm:hidden p-3 border-b border-slate-100 ${isNew ? 'row-new' : ''}">
        <div class="flex items-center justify-between gap-2 mb-2">
          <div class="flex items-center gap-2">
            <div class="${avatarClass}" style="background:${bg};color:${fg}; font-weight:900;" aria-hidden="true">${esc(initials)}</div>
            <div>
              <span class="font-extrabold text-slate-800 text-xs block leading-tight">${esc(s.nama)}</span>
              <span class="text-[10px] text-slate-400 font-medium">${esc(s.started_at) || '—'}</span>
            </div>
          </div>
          <div>${getStatusBadge(s.status)}</div>
        </div>

        <div class="space-y-1">
          <div class="flex justify-between items-center text-[10px] text-slate-500 font-bold">
            <span>Progress (${s.answered || 0}/${s.total_soal || 0})</span>
            <span class="${finished ? 'text-emerald-600 font-black' : 'text-slate-400'}">
              Skor: ${s.score !== null ? `<span class="score-pop text-xs">${s.score}</span>` : '—'}
            </span>
          </div>
          <div class="progress-track">
            <div class="progress-fill ${barClass}" style="width:${s.percent || 0}%; background:${fill};"></div>
          </div>
        </div>
      </div>

      {{-- Desktop Table Row View --}}
      <div class="hidden sm:grid student-row-desktop ${isNew ? 'row-new' : ''}">
        <div class="${avatarClass}" style="background:${bg};color:${fg}; font-weight:900;" aria-hidden="true">${esc(initials)}</div>
        <span class="font-bold text-slate-700 truncate" title="${esc(s.nama)}">${esc(s.nama)}</span>
        <div>
          <div class="progress-track">
            <div class="progress-fill ${barClass}" style="width:${s.percent || 0}%; background:${fill};"></div>
          </div>
          <span class="text-[10px] font-bold text-slate-500 mt-1 block">
            🎯 Terjawab ${s.answered || 0}/${s.total_soal || 0} Soal
          </span>
        </div>
        <div class="text-center">${getStatusBadge(s.status)}</div>
        <div class="text-center font-black text-sm ${finished ? 'text-emerald-600' : 'text-slate-400'}">
          ${s.score !== null ? `<span class="score-pop">${s.score}</span>` : '—'}
        </div>
        <div class="text-center text-xs font-bold text-slate-500">${esc(s.started_at) || '—'}</div>
      </div>`;
    }).join('');

    const newMap = {};
    students.forEach(s => { newMap[s.attempt_id] = true; });
    prevStudents = newMap;

    // ── RENDER PAPAN BINTANG LEADERBOARD SIDEBAR ──
    const sorted = students
      .filter(s => s.status === 'selesai' && s.score !== null)
      .sort((a,b) => b.score - a.score)
      .slice(0, 8);

    const lbEl = document.getElementById('leaderboard-list');
    if (!sorted.length) {
      lbEl.innerHTML = `<li class="py-8 text-center text-slate-400 text-xs font-bold">
        <span class="text-2xl block mb-1">⏳</span>
        Belum ada skor yang masuk
      </li>`;
      return;
    }

    const medals = ['🥇', '🥈', '🥉'];
    const medalBgs = ['#FEF3C7', '#E2E8F0', '#FFEDD5'];
    
    lbEl.innerHTML = sorted.map((s, i) => {
      const [bg, fg] = avatarColor(s.nama);
      const initials = getInitials(s.nama);
      const isTopThree = i < 3;
      const scoreClass = i === 0 ? 'score-tag score-gold' : 'score-tag';
      const itemStyle = i === 0 ? 'border-color:#FCD34D; background:#FFFDF2;' : '';
      
      return `<li class="leaderboard-item" style="${itemStyle}">
        <span class="rank-badge" style="background:${isTopThree ? medalBgs[i] : '#F1F5F9'};">
          ${isTopThree ? medals[i] : (i + 1)}
        </span>
        <div class="avatar-zone" style="background:${bg};color:${fg};width:28px;height:28px;font-size:10px; font-weight:800;" aria-hidden="true">${esc(initials)}</div>
        <span class="text-xs font-bold text-slate-700 truncate flex-1" title="${esc(s.nama)}">${esc(s.nama)}</span>
        <span class="${scoreClass}">${s.score} Poin</span>
      </li>`;
    }).join('');
  }

  function poll() {
    fetch(endpoint, { headers: { 'Accept': 'application/json' } })
      .then(r => r.json())
      .then(render)
      .catch(err => console.error('Gagal memuat data live:', err));
  }

  poll();
  setInterval(poll, POLL_MS);
})();
</script>
@endsection