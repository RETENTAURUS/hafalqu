@extends('layouts.siswa')

@section('title', 'Hasil Quiz — HafalQU')
@section('page_title', 'Hasil Quiz')
@section('page_subtitle', $attempt->quiz->title)

@section('content')
<div style="max-width:560px; margin:0 auto;">

  {{-- Card Utama --}}
  <div style="background:#fff; border-radius:14px; border:1px solid #e0ddd6; padding:32px 28px; text-align:center; box-shadow:0 1px 4px rgba(0,0,0,.05);">

    {{-- Icon --}}
    <div style="width:72px; height:72px; border-radius:50%; margin:0 auto 16px; display:flex; align-items:center; justify-content:center;
                background:{{ $isPerfect ? '#d4f0e5' : ($isPassed ? '#e1f5f0' : '#fee2e2') }};">
      @if($isPerfect)
        {{-- Bintang --}}
        <svg style="width:36px;height:36px;color:#1a7a5e;" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
      @elseif($isPassed)
        {{-- Centang --}}
        <svg style="width:36px;height:36px;color:#1a7a5e;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
      @else
        {{-- X --}}
        <svg style="width:36px;height:36px;color:#dc2626;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      @endif
    </div>

    {{-- Label status --}}
    <p style="font-size:12px; font-weight:700; letter-spacing:.05em; text-transform:uppercase;
              color:{{ $isPerfect ? '#1a7a5e' : ($isPassed ? '#1a7a5e' : '#dc2626') }}; margin-bottom:6px;">
      {{ $isPerfect ? 'Sempurna!' : ($isPassed ? 'Lulus' : 'Belum Lulus') }}
    </p>

    {{-- Skor --}}
    <p style="font-size:48px; font-weight:800; color:#1e3a2a; line-height:1; margin-bottom:4px;">
      {{ $score }}
    </p>
    <p style="font-size:13px; color:#8a9a92; margin-bottom:24px;">dari 100 poin</p>

    {{-- Grid statistik --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:10px; background:#f7f5f0; border-radius:10px; padding:16px; margin-bottom:24px;">
      <div>
        <p style="font-size:10px; color:#8a9a92; margin-bottom:3px;">Total Soal</p>
        <p style="font-size:18px; font-weight:700; color:#1e3a2a;">{{ $totalSoal }}</p>
      </div>
      <div>
        <p style="font-size:10px; color:#8a9a92; margin-bottom:3px;">Benar</p>
        <p style="font-size:18px; font-weight:700; color:#1a7a5e;">{{ $correctCount }}</p>
      </div>
      <div>
        <p style="font-size:10px; color:#8a9a92; margin-bottom:3px;">Salah</p>
        <p style="font-size:18px; font-weight:700; color:#dc2626;">{{ $wrongCount }}</p>
      </div>
      <div>
        <p style="font-size:10px; color:#8a9a92; margin-bottom:3px;">Tidak Dijawab</p>
        <p style="font-size:18px; font-weight:700; color:#a09882;">{{ $notAnswered }}</p>
      </div>
    </div>

    {{-- Pesan --}}
    @if($isPerfect)
      <div style="background:#e1f5f0; border-radius:8px; padding:10px 14px; margin-bottom:20px;">
        <p style="font-size:12px; color:#1a7a5e; font-weight:600;">
          🎉 Nilai sempurna! Quiz berikutnya sudah terbuka.
        </p>
      </div>
    @elseif($isPassed)
      <div style="background:#fdf3e0; border-radius:8px; padding:10px 14px; margin-bottom:20px;">
        <p style="font-size:12px; color:#a06900; font-weight:600;">
          ✓ Lulus, tapi belum sempurna. Ulangi untuk membuka quiz berikutnya.
        </p>
      </div>
    @else
      <div style="background:#fee2e2; border-radius:8px; padding:10px 14px; margin-bottom:20px;">
        <p style="font-size:12px; color:#dc2626; font-weight:600;">
          Belum mencapai nilai minimum {{ $quiz->passing_score ?? 70 }}. Coba lagi!
        </p>
      </div>
    @endif

    {{-- Tombol --}}
    <div style="display:flex; gap:10px; justify-content:center;">
      <a href="{{ route('siswa.quiz.index') }}"
         style="padding:8px 20px; border:1px solid #ddd; border-radius:8px; font-size:12px; font-weight:600; color:#4a5a52; text-decoration:none; background:#fff;">
        Kembali
      </a>
      @if($canRetry)
        <a href="{{ route('siswa.quiz.confirm', $attempt->quiz_id) }}"
           style="padding:8px 20px; background:#1a3a2e; border-radius:8px; font-size:12px; font-weight:600; color:#fff; text-decoration:none;">
          Ulangi Quiz
        </a>
      @endif
    </div>

  </div>
</div>
@endsection