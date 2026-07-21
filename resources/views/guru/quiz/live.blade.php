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
    box-shadow: 0 8px 0 #E2E8F0;
    border-radius: 24px;
    transition: all 0.2s ease;
  }
  .stat-card-fun {
    background: #ffffff;
    border: 3px solid #E2E8F0;
    box-shadow: 0 8px 0 #E2E8F0;
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  .stat-card-fun:hover {
    transform: translateY(-2px);
  }

  .progress-track {
    height: 12px;
    border-radius: 30px;
    background: #F1F5F9;
    border: 2px solid #E2E8F0;
    overflow: hidden;
    min-width: 130px;
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

  .student-row {
    display: grid;
    grid-template-columns: 42px 1fr 180px 100px 70px 75px;
    align-items: center;
    gap: 16px;
    padding: 14px 20px;
    border-bottom: 3px solid #F1F5F9;
    font-size: 14px;
    transition: all 0.2s ease;
  }
  .student-row:last-child { border-bottom: none; }
  .student-row:hover {
    background: #F8FAFC;
    animation: pulseGlow 1.5s infinite alternate;
  }

  .avatar-zone {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 800;
    flex-shrink: 0;
    position: relative;
    border: 2px solid white;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
  }
  .avatar-active::after {
    content: '';
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    border: 3px solid #F59E0B;
    animation: ping 1.2s ease-in-out infinite;
  }
  .avatar-done::after {
    content: '✓';
    position: absolute;
    bottom: -3px;
    right: -3px;
    background: #10B981;
    color: white;
    font-size: 9px;
    font-weight: 900;
    width: 16px;
    height: 16px;
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
    gap: 4px;
    font-size: 12px;
    font-weight: 800;
    padding: 5px 12px;
    border-radius: 30px;
    box-shadow: 0 3px 0 rgba(0,0,0,0.05);
  }
  .badge-done { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
  .badge-active { background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; animation: float 2.5s ease-in-out infinite; }
  .badge-waiting { background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0; }

  .leaderboard-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    margin-bottom: 8px;
    background: #F8FAFC;
    border: 2px solid #E2E8F0;
    box-shadow: 0 4px 0 #E2E8F0;
    border-radius: 16px;
    font-size: 14px;
    transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  .leaderboard-item:hover {
    transform: scale(1.03);
    background: #FFF;
  }
  .leaderboard-item .rank-badge {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 14px;
    border-radius: 50%;
  }
  
  .leaderboard-item .score-tag {
    margin-left: auto;
    font-size: 13px;
    font-weight: 800;
    color: #0369A1;
    background: #E0F2FE;
    padding: 3px 10px;
    border-radius: 30px;
    border: 1px solid #BAE6FD;
  }
  .leaderboard-item .score-gold {
    background: linear-gradient(135deg, #FEF3C7, #FCD34D);
    color: #78350F;
    border: 1px solid #FDE68A;
    box-shadow: 0 2px 5px rgba(251,191,36,0.3);
  }

  .confetti-container {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    pointer-events: none; z-index: 9999; overflow: hidden;
  }
  .confetti-piece {
    position: absolute; width: 9px; height: 9px;
    animation: confettiDrop 1.6s ease-out forwards;
  }

  @media (max-width: 992px) {
    .student-row {
      grid-template-columns: 38px 1fr 120px 80px 50px 65px;
      gap: 10px;
      padding: 12px 14px;
      font-size: 13px;
    }
  }
</style>
@endsection

@section('content')
<div class="live-monitor" style="max-width:1280px; margin:0 auto; padding: 0 10px 2rem;">

  {{-- Header Arena Live --}}
  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:16px;">
    <div style="display:flex; align-items:center; gap:16px; background:#FFF5F5; border:3px solid #FCA5A5; padding:10px 20px; border-radius:30px; box-shadow:0 6px 0 #FCA5A5;">
      <div style="position:relative; width:16px; height:16px; flex-shrink:0;">
        <div class="ping" style="position:absolute; inset:0; border-radius:50%; background:#EF4444; opacity:.6;"></div>
        <div style="position:relative; width:16px; height:16px; border-radius:50%; background:#EF4444; box-shadow:0 0 15px #EF4444;"></div>
      </div>
      <div>
        <span style="font-size:15px; font-weight:900; color:#EF4444; letter-spacing:0.8px;">ARENA LIVE MONITOR</span>
        <span id="updated-at" style="font-size:13px; color:#7F1D1D; font-weight:700; margin-left:12px; opacity:0.75;"></span>
      </div>
    </div>
    
    <div style="display:flex; align-items:center; gap:14px;">
      <span style="font-size:13px; color:#64748B; font-weight:700; background:#F1F5F9; padding:8px 14px; border-radius:30px;">
        🔄 Refresh tiap 4 detik
      </span>
      <a href="{{ url()->previous() }}"
         style="font-size:14px; font-weight:800; color:#475569; text-decoration:none; display:flex; align-items:center; gap:6px; 
                padding:8px 18px; border-radius:30px; background:#FFF; border:3px solid #E2E8F0; box-shadow:0 5px 0 #E2E8F0; transition:all 0.1s ease;"
         onmousedown="this.style.transform='translateY(3px)'; this.style.boxShadow='0 2px 0 #E2E8F0';"
         onmouseup="this.style.transform='translateY(0px)'; this.style.boxShadow='0 5px 0 #E2E8F0';"
         onmouseleave="this.style.transform='translateY(0px)'; this.style.boxShadow='0 5px 0 #E2E8F0';">
        <i class="ti ti-arrow-left" aria-hidden="true"></i> Keluar Arena
      </a>
    </div>
  </div>

  {{-- Informasi Kuis --}}
  <div style="display:flex; align-items:center; gap:10px; margin-bottom:24px; padding:12px 20px; background:linear-gradient(135deg, #FFE4E6 0%, #FECDD3 100%); border-radius:18px; border:3px solid #FB7185; box-shadow:0 6px 0 #FB7185;">
    <span style="font-size:20px;">📝</span>
    <span style="font-size:15px; color:#881337; font-weight:900;">Kuis: {{ $quiz->title }}</span>
    <span style="font-size:13px; background:#FFF; color:#E11D48; font-weight:800; padding:4px 12px; border-radius:30px; margin-left:auto; border:2px solid #FDA4AF;">
      👥 {{ $quiz->participants_count ?? 0 }} Jagoan Terdaftar
    </span>
  </div>

  {{-- Grid Dasbor Statistik Makro --}}
  <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:28px;">
    <div class="stat-card-fun" style="border-color:#CBD5E1; box-shadow: 0 8px 0 #CBD5E1; padding:20px;">
      <div style="display:flex; align-items:center; justify-content:space-between;">
        <div>
          <p id="c-total" style="font-size:36px; font-weight:900; color:#1E293B; margin:0; line-height:1;">0</p>
          <p style="font-size:13px; color:#64748B; margin:6px 0 0; font-weight:800;">Total Ikut</p>
        </div>
        <div style="font-size:32px; background:#F1F5F9; width:54px; height:54px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid #E2E8F0;">👥</div>
      </div>
    </div>
    <div class="stat-card-fun" style="border-color:#FDE68A; box-shadow: 0 8px 0 #FDE68A; padding:20px;">
      <div style="display:flex; align-items:center; justify-content:space-between;">
        <div>
          <p id="c-active" style="font-size:36px; font-weight:900; color:#D97706; margin:0; line-height:1;">0</p>
          <p style="font-size:13px; color:#B45309; margin:6px 0 0; font-weight:800;">Mengerjakan</p>
        </div>
        <div style="font-size:32px; background:#FEF3C7; width:54px; height:54px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid #FDE68A;">⚡</div>
      </div>
    </div>
    <div class="stat-card-fun" style="border-color:#A7F3D0; box-shadow: 0 8px 0 #A7F3D0; padding:20px;">
      <div style="display:flex; align-items:center; justify-content:space-between;">
        <div>
          <p id="c-done" style="font-size:36px; font-weight:900; color:#059669; margin:0; line-height:1;">0</p>
          <p style="font-size:13px; color:#065F46; margin:6px 0 0; font-weight:800;">Sudah Selesai</p>
        </div>
        <div style="font-size:32px; background:#D1FAE5; width:54px; height:54px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid #A7F3D0;">✅</div>
      </div>
    </div>
    <div class="stat-card-fun" style="border-color:#C7D2FE; box-shadow: 0 8px 0 #C7D2FE; padding:20px;">
      <div style="display:flex; align-items:center; justify-content:space-between;">
        <div>
          <p id="c-avg" style="font-size:36px; font-weight:900; color:#4F46E5; margin:0; line-height:1;">0</p>
          <p style="font-size:13px; color:#3730A3; margin:6px 0 0; font-weight:800;">Rata-rata Skor</p>
        </div>
        <div style="font-size:32px; background:#E0E7FF; width:54px; height:54px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid #C7D2FE;">🎯</div>
      </div>
    </div>
  </div>

  {{-- Grid Utama Pemantauan --}}
  <div style="display:grid; grid-template-columns:1fr 320px; gap:24px; align-items:start;">

    {{-- Tabel Aktivitas Siswa --}}
    <div class="card-gameplay">
      <div style="padding:16px 20px; background:#F8FAFC; border-bottom:3px solid #E2E8F0; border-radius:20px 20px 0 0; display:flex; align-items:center; gap:10px;">
        <span style="font-size:18px;">🏃‍♂️</span>
        <span style="font-size:15px; font-weight:800; color:#1E293B;">Perjuangan Para Jagoan</span>
        <span id="student-count" style="font-size:12px; font-weight:900; background:#4F46E5; color:white; padding:3px 12px; border-radius:30px; margin-left:auto; box-shadow:0 2px 4px rgba(79,70,229,0.2);">0</span>
      </div>
      
      {{-- Label Tabel Kolom --}}
      <div class="student-row" style="background:#F8FAFC; font-size:12px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:.05em; border-bottom:2px solid #E2E8F0; padding:10px 20px;">
        <span></span>
        <span>Nama Siswa</span>
        <span>Progress Bar</span>
        <span style="text-align:center;">Status</span>
        <span style="text-align:center;">Skor</span>
        <span style="text-align:center;">Jam Mulai</span>
      </div>
      
      {{-- Area Suntikan Data Realtime via JS --}}
      <div id="student-list" style="max-height:560px; overflow-y:auto; padding: 4px 0;">
        <div style="padding:60px 20px; text-align:center; color:#94A3B8;">
          <i class="ti ti-loader" style="font-size:28px; display:block; margin-bottom:12px; animation:spin 1s linear infinite;" aria-hidden="true"></i>
          <span style="font-size:14px; font-weight:700;">Mempersiapkan Arena Live...</span>
        </div>
      </div>
    </div>

    {{-- Panel Pemimpin Turnamen (Leaderboard) --}}
    <div class="card-gameplay" style="position:sticky; top:20px; border-color:#FDE68A; box-shadow:0 8px 0 #FDE68A;">
      <div style="padding:16px 20px; background:linear-gradient(135deg, #FEFCE8 0%, #FEF3C7 100%); border-bottom:3px solid #FDE68A; border-radius:20px 20px 0 0; display:flex; align-items:center; gap:10px;">
        <span style="font-size:20px;">👑</span>
        <span style="font-size:15px; font-weight:900; color:#78350F;">Papan Bintang Top 8</span>
      </div>
      <div style="padding:16px;">
        <ul id="leaderboard-list" style="list-style:none; margin:0; padding:0;">
          <li style="padding:30px 0; text-align:center; color:#94A3B8; font-size:14px; font-weight:700;">
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
      listEl.innerHTML = `<div style="padding:60px 20px; text-align:center; color:#94A3B8; font-weight:700;">
        <span style="font-size:36px; display:block; margin-bottom:10px;">💤</span>
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

      return `<div class="student-row${isNew ? ' row-new' : ''}">
        <div class="${avatarClass}" style="background:${bg};color:${fg}; font-weight:900;" aria-hidden="true">${esc(initials)}</div>
        <span style="font-weight:800; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:#334155;" title="${esc(s.nama)}">${esc(s.nama)}</span>
        <div>
          <div class="progress-track">
            <div class="progress-fill ${barClass}" style="width:${s.percent || 0}%; background:${fill};"></div>
          </div>
          <span style="font-size:11px; font-weight:700; color:#64748B; margin-top:4px; display:block;">
            🎯 Terjawab ${s.answered || 0}/${s.total_soal || 0} Soal
          </span>
        </div>
        <div style="text-align:center;">${getStatusBadge(s.status)}</div>
        <div style="text-align:center; font-weight:900; font-size:16px; color:${finished ? '#059669' : '#94A3B8'};">
          ${s.score !== null ? `<span class="score-pop">${s.score}</span>` : '—'}
        </div>
        <div style="text-align:center; font-size:12px; font-weight:700; color:#64748B;">${esc(s.started_at) || '—'}</div>
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
      lbEl.innerHTML = `<li style="padding:30px 0; text-align:center; color:#94A3B8; font-size:13px; font-weight:700;">
        <span style="font-size:24px; display:block; margin-bottom:6px;">⏳</span>
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
        <div class="avatar-zone" style="background:${bg};color:${fg};width:30px;height:30px;font-size:10px; font-weight:800;" aria-hidden="true">${esc(initials)}</div>
        <span style="font-size:13px; font-weight:800; color:#334155; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1;" title="${esc(s.nama)}">${esc(s.nama)}</span>
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

<style>
  @keyframes spin {
    0%{transform:rotate(0deg)} 100%{transform:rotate(360deg)}
  }
</style>
@endsection