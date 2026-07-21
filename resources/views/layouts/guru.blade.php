<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'HafalQU — Guru')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    * { font-family: 'Inter', sans-serif; }

    /* ── Sidebar ─────────────────────────────────── */
    .sidebar-brand-sub {
      font-size: 9px;
      letter-spacing: 1.6px;
      text-transform: uppercase;
      color: #8ab89e;
    }

    .nav-item {
      border-left: 2px solid transparent;
      transition: background 0.15s, border-color 0.15s, color 0.15s;
    }
    .nav-item:hover {
      background: rgba(255,255,255,0.06);
      border-left-color: rgba(212,168,67,0.5);
      color: #d4e8dc;
    }
    .nav-active {
      background: rgba(255,255,255,0.10);
      border-left-color: #d4a843 !important;
      color: #fff !important;
    }
    .nav-active svg { color: #d4a843; }

    /* ── Content area ────────────────────────────── */
    .page-header {
      background: #fff;
      border-bottom: 1px solid #eae6de;
    }

    /* ── Flash messages ──────────────────────────── */
    .flash-success {
      background: #f0faf5;
      border: 1px solid #a7dfc4;
      color: #1a5e3a;
    }
    .flash-error {
      background: #fdf2f2;
      border: 1px solid #f5b8b8;
      color: #7a2020;
    }

    /* ── Scrollbar ───────────────────────────────── */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-thumb { background: #c8d5cc; border-radius: 2px; }

    /* ── Focus ───────────────────────────────────── */
    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: #2d7a5f;
      box-shadow: 0 0 0 3px rgba(45,122,95,0.12);
    }

    @yield('styles')
  </style>
</head>
<body style="background:#f8f7f4; display:flex; height:100vh; overflow:hidden;">

  <aside style="width:200px; background:#1a3a2e; display:flex; flex-direction:column; flex-shrink:0; overflow-y:auto;">

    {{-- Gold stripe ornament --}}
    <div style="height:3px; background:repeating-linear-gradient(90deg, transparent, transparent 5px, rgba(212,168,67,0.4) 5px, rgba(212,168,67,0.4) 6px);"></div>

    {{-- Logo --}}
    <div style="padding:20px 18px 16px; border-bottom:1px solid rgba(255,255,255,0.08);">
      <div style="display:flex; align-items:center; gap:9px;">
        <div style="width:28px; height:28px; border-radius:7px; background:rgba(255,255,255,0.08); display:flex; align-items:center; justify-content:center; flex-shrink:0; padding:4px;">
          <img src="{{ asset('img/logo.png') }}" alt="Logo HafalQu" style="width:100%; height:100%; object-fit:contain;">
        </div>
        <div>
          <div style="font-size:14px; font-weight:700; color:#fff; letter-spacing:0.2px; line-height:1;">HafalQU</div>
          <div class="sidebar-brand-sub">Guru Tahfidz</div>
        </div>
      </div>
    </div>

    {{-- User info --}}
    <div style="padding:12px 18px 14px; border-bottom:1px solid rgba(255,255,255,0.08);">
      <div style="display:flex; align-items:center; gap:9px; background:rgba(255,255,255,0.06); border-radius:8px; padding:8px 10px;">
        <div style="width:30px; height:30px; border-radius:50%; background:#d4a843; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:#5a3200; flex-shrink:0;">
          {{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}
        </div>
        <div style="min-width:0;">
          <div style="font-size:12px; font-weight:600; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            {{ Auth::user()->name ?? 'Guru' }}
          </div>
          <div style="font-size:10px; color:#8ab89e; margin-top:1px;">Guru Tahfidz</div>
        </div>
      </div>
    </div>

    {{-- Navigation --}}
    <nav style="flex:1; padding:10px 8px;">
      <p style="font-size:9px; font-weight:600; letter-spacing:1.4px; text-transform:uppercase; color:#6a9a7e; padding:0 10px 8px;">Menu</p>

      <a href="{{ route('guru.dashboard') }}"
         class="nav-item {{ request()->routeIs('guru.dashboard') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg style="width:15px;height:15px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
        </svg>
        Dashboard
      </a>

      <a href="{{ route('guru.siswa.index') }}"
         class="nav-item {{ request()->routeIs('guru.siswa.*') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg style="width:15px;height:15px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        Manajemen Siswa
      </a>

      <a href="{{ route('guru.soal.index') }}"
         class="nav-item {{ request()->routeIs('guru.soal.*') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg style="width:15px;height:15px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
          <rect x="9" y="3" width="6" height="4" rx="1"/>
          <line x1="9" y1="12" x2="15" y2="12"/>
          <line x1="9" y1="16" x2="13" y2="16"/>
        </svg>
        Bank Soal
      </a>

      <a href="{{ route('guru.quiz.pilihJuz') }}"
         class="nav-item {{ request()->routeIs('guru.quiz.*') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg style="width:15px;height:15px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <circle cx="12" cy="12" r="10"/>
          <polygon points="10 8 16 12 10 16 10 8"/>
        </svg>
        Kelola Quiz
      </a>

      <a href="{{ route('guru.poin.index') }}"
         class="nav-item {{ request()->routeIs('guru.poin.index*') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg style="width:15px;height:15px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
        </svg>
        Sistem Poin
      </a>

      <a href="{{ route('guru.lencana.index') }}"
         class="nav-item {{ request()->routeIs('guru.lencana.*') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg style="width:15px;height:15px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <circle cx="12" cy="8" r="6"/>
          <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
        </svg>
        Kelola Lencana
      </a>

      <a href="{{ route('guru.leaderboard.index') }}"
         class="nav-item {{ request()->routeIs('guru.leaderboard.*') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg style="width:15px;height:15px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
          <path d="M4 22h16"/>
          <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
          <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
          <path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/>
        </svg>
        Papan Peringkat
      </a>

      <a href="{{ route('guru.laporan.index') }}"
         class="nav-item {{ request()->routeIs('guru.laporan.*') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg style="width:15px;height:15px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/>
          <line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
        Laporan
      </a>
    </nav>

    {{-- Logout --}}
    <div style="padding:10px 8px 16px; border-top:1px solid rgba(255,255,255,0.08);">
      <form action="{{ route('logout') }}" method="POST" id="guru-logout-form">
        @csrf
        <button type="button" onclick="confirmLogout()"
          class="nav-item"
          style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#c07a74; background:none; border-top:none; border-right:none; border-bottom:none; cursor:pointer; width:100%; text-align:left;">
          <svg style="width:15px;height:15px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          Keluar
        </button>
      </form>
    </div>

  </aside>

  <main style="flex:1; display:flex; flex-direction:column; overflow-y:auto;">

    {{-- Page Header --}}
    @section('header')
    <header class="page-header" style="padding:18px 28px; display:flex; align-items:center; justify-content:space-between; flex-shrink:0;">
      <div style="display:flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#1e3a2a;">
        @yield('breadcrumb')
      </div>
      <div>@yield('header_actions')</div>
    </header>
    @show

    {{-- Flash: Success --}}
    @if(session('success'))
      <div class="flash-success" style="margin:16px 28px 0; display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:9px; font-size:13px;">
        <svg style="width:16px;height:16px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        {{ session('success') }}
      </div>
    @endif

    {{-- Flash: Error --}}
    @if(session('error'))
      <div class="flash-error" style="margin:16px 28px 0; display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:9px; font-size:13px;">
        <svg style="width:16px;height:16px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ session('error') }}
      </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
      <div class="flash-error" style="margin:16px 28px 0; padding:10px 14px; border-radius:9px; font-size:13px;">
        @foreach($errors->all() as $error)
          <div style="display:flex; align-items:center; gap:7px; {{ !$loop->first ? 'margin-top:4px;' : '' }}">
            <span style="width:5px; height:5px; background:#b83232; border-radius:50%; flex-shrink:0;"></span>
            {{ $error }}
          </div>
        @endforeach
      </div>
    @endif

    {{-- Main page content --}}
    <div style="flex:1; padding:22px 28px; overflow:auto;">
      @yield('content')
    </div>

  </main>

  {{-- Logout confirm modal --}}
  <div id="logout-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:14px; padding:28px 28px 24px; width:320px; box-shadow:0 20px 60px rgba(0,0,0,0.15);">
      <div style="width:44px; height:44px; border-radius:50%; background:#fdf2f2; display:flex; align-items:center; justify-content:center; margin-bottom:14px;">
        <svg style="width:20px;height:20px;color:#b83232;" viewBox="0 0 24 24" fill="none" stroke="#b83232" stroke-width="2">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
      </div>
      <p style="font-size:15px; font-weight:700; color:#1e3a2a; margin-bottom:6px;">Keluar dari HafalQU?</p>
      <p style="font-size:13px; color:#6b7c74; margin-bottom:22px; line-height:1.5;">Sesi kamu akan diakhiri dan kamu perlu login kembali untuk melanjutkan.</p>
      <div style="display:flex; gap:10px;">
        <button onclick="closeLogout()"
          style="flex:1; padding:9px; border-radius:8px; border:1px solid #ddd; background:#fff; font-size:13px; font-weight:500; color:#4a5a52; cursor:pointer;">
          Batal
        </button>
        <button onclick="document.getElementById('guru-logout-form').submit()"
          style="flex:1; padding:9px; border-radius:8px; border:none; background:#1a3a2e; font-size:13px; font-weight:600; color:#fff; cursor:pointer;">
          Ya, Keluar
        </button>
      </div>
    </div>
  </div>

  {{-- Modals slot --}}
  @yield('modals')

  <script>
    function confirmLogout() {
      document.getElementById('logout-modal').style.display = 'flex';
    }
    function closeLogout() {
      document.getElementById('logout-modal').style.display = 'none';
    }

    document.getElementById('logout-modal').addEventListener('click', function(e) {
      if (e.target === this) closeLogout();
    });

    window.addEventListener('click', function(e) {
      document.querySelectorAll('.modal-backdrop').forEach(function(el) {
        if (e.target === el) el.classList.add('hidden');
      });
    });
  </script>

  @yield('scripts')
</body>
</html>