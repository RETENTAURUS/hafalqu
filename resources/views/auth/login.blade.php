<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <title>HafalQu · Login Sistem Monitoring Hafalan</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Amiri&family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'DM Sans', sans-serif;
    }
    .font-serif-display {
      font-family: 'DM Serif Display', serif;
    }
    .font-arabic {
      font-family: 'Amiri', serif;
    }
  </style>
</head>
<body class="min-h-screen flex md:flex-row flex-col bg-[#fdfaf4]">

  <div class="md:w-[48%] w-full bg-[#064e3b] text-white relative overflow-hidden flex flex-col items-center justify-center px-6 py-10 md:py-12 md:px-10">
    <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2280%22 height=%2280%22%3E%3Cg fill=%22none%22 stroke=%22rgba(255,255,255,0.2)%22 stroke-width=%220.8%22%3E%3Cpolygon points=%2240,4 48,28 74,28 53,44 61,68 40,52 19,68 27,44 6,28 32,28%22/%3E%3C/g%3E%3C/svg%3E'); background-size: 80px;"></div>
    
    <div class="relative z-10 text-center max-w-sm">
      <div class="w-24 h-24 mx-auto mb-6 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center p-4 shadow-md">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Buku" class="w-full h-full object-contain">
      </div>
      
      <div class="font-arabic text-2xl text-white/80 mb-3">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>
      <h1 class="font-serif-display text-4xl md:text-5xl mb-2">HafalQu</h1>
      <p class="text-sm text-white/60 leading-relaxed mb-8">Platform digital untuk memantau dan meningkatkan semangat hafalan siswa SDIT Ulul Albab Mataram</p>
      
      <div class="space-y-3 text-left">
        <div class="flex items-center gap-3 bg-white/10 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white/80">
          <span>📊</span> Pantau perkembangan hafalan siswa secara real-time
        </div>
        <div class="flex items-center gap-3 bg-white/10 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white/80">
          <span>🏆</span> Sistem gamifikasi poin, level & badge motivasi
        </div>
        <div class="flex items-center gap-3 bg-white/10 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white/80">
          <span>📝</span> Bank soal dan penjadwalan quiz yang fleksibel
        </div>
      </div>
    </div>
    
    <div class="relative z-10 mt-10 md:mt-12 inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-5 py-2 text-xs text-white/70">
      <span>🏫</span> SDIT Ulul Albab Mataram
    </div>
  </div>

  <div class="flex-1 flex items-center justify-center px-6 py-10 md:px-8 md:py-12 bg-[#fdfaf4]">
    <div class="w-full max-w-md animate-[fadeUp_0.4s_ease_both]">
      <div class="flex items-center gap-3 mb-8">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#065f46] to-[#047857] flex items-center justify-center p-2 shadow-md">
          <img src="{{ asset('img/logo.png') }}" alt="Logo Buku Mini" class="w-full h-full object-contain brightness-0 invert">
        </div>
        <div>
          <div class="font-serif-display text-xl text-[#1a1208]">HafalQu</div>
          <div class="text-[10px] uppercase tracking-wide text-[#b8a898]">Monitoring Hafalan</div>
        </div>
      </div>

      <h2 class="font-serif-display text-3xl md:text-4xl text-[#1a1208] leading-tight">Selamat Datang, <br><em class="not-italic font-bold">Siswa HafalQu</em> 👋</h2>
      <p class="text-sm text-[#7a6652] mt-2 mb-8 leading-relaxed">Masuk untuk mengerjakan quiz hafalan, lihat poin, badge, dan posisi kamu di leaderboard.</p>

      <div id="errorMsg" class="hidden items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl px-4 py-3 mb-5">
        <i class="fas fa-exclamation-circle"></i>
        <span id="errorText">Username atau password salah</span>
      </div>

      <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-semibold text-[#3d2e18] mb-1">Username</label>
          <div class="relative">
            <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-[#b8a898] text-sm"></i>
            <input type="text" id="username" name="username" placeholder="Masukkan username Anda"
                   class="w-full pl-9 pr-4 py-2.5 border border-[#e8e0d4] rounded-xl bg-white focus:border-[#047857] focus:ring-2 focus:ring-[#047857]/20 outline-none transition text-sm" required>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-[#3d2e18] mb-1">Password</label>
          <div class="relative">
            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-[#b8a898] text-sm"></i>
            <input type="password" id="password" name="password" placeholder="Masukkan password Anda"
                   class="w-full pl-9 pr-10 py-2.5 border border-[#e8e0d4] rounded-xl bg-white focus:border-[#047857] focus:ring-2 focus:ring-[#047857]/20 outline-none transition text-sm" required>
            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#b8a898] hover:text-[#3d2e18] text-sm">
              <i class="fas fa-eye-slash"></i>
            </button>
          </div>
        </div>

        @error('login')
          <div class="items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl px-4 py-3 mb-5">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $message }}</span>
          </div>
        @enderror

        <button type="submit" id="loginBtn"
                class="w-full bg-gradient-to-r from-[#065f46] to-[#047857] text-white font-bold py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 active:scale-[0.98]">
          <span>Masuk ke Dashboard</span>
          <i class="fas fa-arrow-right text-sm"></i>
        </button>
      </form>

      <div class="text-center text-[11px] text-[#b8a898] mt-8 pt-4 border-t border-[#e8e0d4]">
        © 2026 HafalQu · SDIT Ulul Albab Mataram
      </div>
    </div>
  </div>

  <style>
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-\[fadeUp_0\.4s_ease_both\] {
      animation: fadeUp 0.4s ease both;
    }
  </style>

  <script>
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const loginBtn = document.getElementById('loginBtn');
    const errorDiv = document.getElementById('errorMsg');
    const errorTextSpan = document.getElementById('errorText');
    const toggleBtn = document.getElementById('togglePassword');

    // Toggle password visibility
    let passwordVisible = false;
    if(toggleBtn) {
      toggleBtn.addEventListener('click', () => {
        passwordVisible = !passwordVisible;
        passwordInput.type = passwordVisible ? 'text' : 'password';
        toggleBtn.innerHTML = passwordVisible ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
      });
    }
  </script>
</body>
</html>