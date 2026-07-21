<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'HafalQU Admin')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    * { font-family: 'Inter', sans-serif; }

    /* ── Sidebar & Responsive Drawer ─────────────────── */
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
    ::-webkit-scrollbar { width: 4px; height: 4px; }
    ::-webkit-scrollbar-thumb { background: #c8d5cc; border-radius: 2px; }

    /* ── Focus ───────────────────────────────────── */
    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: #2d7a5f;
      box-shadow: 0 0 0 3px rgba(45,122,95,0.12);
    }

    /* ── Stat card accents ───────────────────────── */
    .card-guru  { border-left: 3px solid #2d7a5f; }
    .card-siswa { border-left: 3px solid #d4a843; }
    .card-kelas { border-left: 3px solid #6366F1; }

    @yield('styles')
  </style>
</head>
<body class="bg-[#f8f7f4] flex flex-col md:flex-row h-screen overflow-hidden text-slate-800">

  {{-- Mobile Backdrop Overlay --}}
  <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>

  {{-- Sidebar Navigasi --}}
  <aside id="sidebar-menu" class="fixed md:static inset-y-0 left-0 z-50 w-52 bg-[#1a3a2e] flex flex-col flex-shrink-0 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out overflow-y-auto">

    {{-- Gold stripe ornament --}}
    <div style="height:3px; background:repeating-linear-gradient(90deg, transparent, transparent 5px, rgba(212,168,67,0.4) 5px, rgba(212,168,67,0.4) 6px);"></div>

    {{-- Logo --}}
    <div class="p-4 sm:p-5 border-b border-white/10 flex items-center justify-between">
      <div class="flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0 p-1">
          <img src="{{ asset('img/logo.png') }}" alt="Logo HafalQu" class="w-full h-full object-contain">
        </div>
        <div>
          <div class="text-sm font-bold text-white tracking-wide leading-none">HafalQU</div>
          <div class="sidebar-brand-sub">Administrator</div>
        </div>
      </div>
      <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white p-1">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    {{-- User info --}}
    <div class="px-3.5 py-3 border-b border-white/10">
      <div class="flex items-center gap-2.5 bg-white/5 rounded-xl p-2">
        <div class="w-7 h-7 rounded-full bg-[#d4a843] flex items-center justify-center text-xs font-bold text-[#5a3200] flex-shrink-0">
          {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
        </div>
        <div class="min-w-0 flex-1">
          <div class="text-xs font-semibold text-white truncate">
            {{ Auth::user()->name ?? 'Admin' }}
          </div>
          <div class="text-[10px] text-[#8ab89e] mt-0.5">Administrator</div>
        </div>
      </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-2 py-2.5 space-y-0.5">
      <p class="text-[9px] font-bold tracking-widest uppercase text-[#6a9a7e] px-2.5 pb-2">Menu</p>

      <a href="{{ route('admin.dashboard') }}"
         class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-medium text-[#b0cfc0] text-decoration-none {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
        </svg>
        <span>Dashboard</span>
      </a>

      <a href="{{ route('admin.guru.index') }}"
         class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-medium text-[#b0cfc0] text-decoration-none {{ request()->routeIs('admin.guru.*') ? 'nav-active' : '' }}">
        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        <span>Akun Guru</span>
      </a>

      <a href="{{ route('admin.kelas.index') }}"
         class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-medium text-[#b0cfc0] text-decoration-none {{ request()->routeIs('admin.kelas.*') ? 'nav-active' : '' }}">
        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
          <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        <span>Data Kelas</span>
      </a>

      <a href="{{ route('admin.siswa.kelasList') }}"
         class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-medium text-[#b0cfc0] text-decoration-none {{ request()->routeIs('admin.siswa.*') ? 'nav-active' : '' }}">
        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
          <circle cx="12" cy="7" r="4"/>
        </svg>
        <span>Akun Siswa</span>
      </a>

      <a href="{{ route('admin.soal.index') }}"
         class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-medium text-[#b0cfc0] text-decoration-none {{ request()->routeIs('admin.soal.*') ? 'nav-active' : '' }}">
        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
          <rect x="9" y="3" width="6" height="4" rx="1"/>
          <line x1="9" y1="12" x2="15" y2="12"/>
          <line x1="9" y1="16" x2="13" y2="16"/>
        </svg>
        <span>Bank Soal</span>
      </a>

      <a href="{{ route('admin.lencana.index') }}"
         class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-medium text-[#b0cfc0] text-decoration-none {{ request()->routeIs('admin.lencana.*') ? 'nav-active' : '' }}">
        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <circle cx="12" cy="8" r="7"/>
          <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
        </svg>
        <span>Daftar Lencana</span>
      </a>

      <a href="#"
         class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-medium text-[#b0cfc0] text-decoration-none {{ request()->routeIs('admin.laporan.*') ? 'nav-active' : '' }}">
        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/>
          <line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
        <span>Kelola Laporan</span>
      </a>
    </nav>

    {{-- Logout --}}
    <div class="p-2 pb-4 border-t border-white/10">
      <form action="{{ route('logout') }}" method="POST" id="admin-logout-form">
        @csrf
        <input type="hidden" name="_logout_invalidate" value="1">
        <button type="button" onclick="confirmLogout()"
          class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-semibold text-[#c07a74] hover:bg-white/5 w-full text-left transition-colors">
          <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          <span>Keluar</span>
        </button>
      </form>
    </div>

  </aside>

  {{-- Area Utama Content --}}
  <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">

    {{-- Mobile Top Bar Header --}}
    <div class="md:hidden bg-[#1a3a2e] text-white px-4 py-3 flex items-center justify-between shadow-sm flex-shrink-0">
      <div class="flex items-center gap-2.5">
        <button onclick="toggleSidebar()" class="p-1 rounded-md text-slate-300 hover:text-white hover:bg-white/10">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <span class="font-bold text-sm tracking-wide">HafalQU Admin</span>
      </div>
      <div class="w-7 h-7 rounded-full bg-[#d4a843] flex items-center justify-center text-xs font-bold text-[#5a3200]">
        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
      </div>
    </div>

    {{-- Page Header --}}
    @section('header')
    <header class="page-header px-4 sm:px-7 py-4 sm:py-4.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 flex-shrink-0">
      <div>
        <h1 class="text-sm sm:text-base font-bold text-[#1e3a2a] tracking-tight">
          @yield('page_title', 'Dashboard')
        </h1>
        <p class="text-xs text-[#6b7c74] mt-0.5">
          @yield('page_subtitle', '')
        </p>
      </div>
      <div class="flex items-center gap-2">@yield('header_actions')</div>
    </header>
    @show

    {{-- Flash: Success --}}
    @if(session('success'))
      <div class="flash-success mx-4 sm:mx-7 mt-4 flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm">
        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    {{-- Flash: Error --}}
    @if(session('error'))
      <div class="flash-error mx-4 sm:mx-7 mt-4 flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm">
        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
      <div class="flash-error mx-4 sm:mx-7 mt-4 p-3.5 rounded-xl text-xs sm:text-sm">
        @foreach($errors->all() as $error)
          <div class="flex items-center gap-2 {{ !$loop->first ? 'mt-1' : '' }}">
            <span class="w-1.5 h-1.5 bg-[#b83232] rounded-full flex-shrink-0"></span>
            <span>{{ $error }}</span>
          </div>
        @endforeach
      </div>
    @endif

    {{-- Main page content --}}
    <div class="flex-1 p-4 sm:p-7">
      @yield('content')
    </div>

  </main>

  {{-- Modal Confirm Logout --}}
  <div id="logout-modal" class="hidden fixed inset-0 bg-slate-900/60 z-50 items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-xs shadow-xl">
      <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center mb-3.5">
        <svg class="w-5 h-5 text-[#b83232]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
      </div>
      <p class="text-sm font-bold text-[#1e3a2a] mb-1">Keluar dari HafalQU?</p>
      <p class="text-xs text-[#6b7c74] mb-5 leading-relaxed">Sesi kamu akan diakhiri dan kamu perlu login kembali untuk melanjutkan.</p>
      <div class="flex gap-2">
        <button onclick="closeLogout()"
          class="flex-1 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
          Batal
        </button>
        <button onclick="document.getElementById('admin-logout-form').submit()"
          class="flex-1 py-2.5 rounded-xl border-none bg-[#1a3a2e] text-xs font-semibold text-white hover:bg-[#122820] transition-colors shadow-sm">
          Ya, Keluar
        </button>
      </div>
    </div>
  </div>

  {{-- Modals slot --}}
  @yield('modals')

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar-menu');
      const overlay = document.getElementById('sidebar-overlay');
      sidebar.classList.toggle('-translate-x-full');
      overlay.classList.toggle('hidden');
    }

    function confirmLogout() {
      const modal = document.getElementById('logout-modal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeLogout() {
      const modal = document.getElementById('logout-modal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    // Close logout modal on backdrop click
    document.getElementById('logout-modal').addEventListener('click', function(e) {
      if (e.target === this) closeLogout();
    });

    // Close any .modal-backdrop on click
    window.addEventListener('click', function(e) {
      document.querySelectorAll('.modal-backdrop').forEach(function(el) {
        if (e.target === el) el.classList.add('hidden');
      });
    });
  </script>

  @yield('scripts')
</body>
</html>