@extends('layouts.siswa')

@section('title', 'Riwayat Quiz — HafalQU')
@section('page_title', 'Riwayat Quiz')
@section('page_subtitle', 'Catatan setiap quiz yang pernah kamu kerjakan')
@section('content')

<style>
  .hq-hist-card { transition: transform .18s ease, box-shadow .18s ease; }
  .hq-hist-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px -8px rgba(26,58,46,0.2); }
</style>

<div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
  <svg style="width:16px;height:16px;color:#1a3a2e;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
    <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
  </svg>
  <h3 style="font-size:14px; font-weight:700; color:#1e3a2a;">Riwayat Quiz</h3>
  <span style="font-size:11px; background:#f0ede6; color:#6b7c74; padding:2px 8px; border-radius:20px; font-weight:700;">
    {{ $attemptedQuizzes->count() }}
  </span>
</div>

@forelse($attemptedQuizzes as $attempt)
  @php
    $isFinished = !is_null($attempt->finished_at);
    $total = $attempt->total_questions ?? 0;
    $correct = $attempt->correct_answers ?? 0;
    $pct = $total > 0 ? ($correct / $total) * 100 : 0;
    $stars = $isFinished ? max(1, min(3, (int) ceil($pct / 34))) : 0;
  @endphp

  <div class="hq-hist-card" style="background:#fff; border-radius:16px; border:2px solid {{ $isFinished ? '#c8e6d4' : '#f0dba0' }}; padding:16px 18px; margin-bottom:12px;">
    <div style="display:flex; gap:12px; align-items:flex-start;">
      <div style="width:38px; height:38px; border-radius:12px; flex-shrink:0; display:flex; align-items:center; justify-content:center;
        background:{{ $isFinished ? 'linear-gradient(135deg,#a7dfc4,#2d7a5f)' : 'linear-gradient(135deg,#ffe9a8,#d4a843)' }};">
        @if($isFinished)
          <svg style="width:18px;height:18px;color:#fff;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        @else
          <svg style="width:18px;height:18px;color:#7a5a1a;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        @endif
      </div>

      <div style="flex:1; min-width:0;">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:10px;">
          <div style="flex:1; min-width:0;">
            <p style="font-size:14px; font-weight:700; color:#1e3a2a; margin-bottom:5px;">
              {{ $attempt->quiz->title ?? 'Quiz' }}
            </p>

            @if($isFinished)
              <div style="margin-bottom:6px;">
                @for($i = 1; $i <= 3; $i++)
                  <span style="font-size:14px; color:{{ $i <= $stars ? '#d4a843' : '#e8e4dc' }};">★</span>
                @endfor
              </div>
            @endif

            <div style="display:flex; flex-wrap:wrap; gap:10px;">
              <span style="font-size:11px; color:#6b7c74; font-weight:600; background:#f5f2ec; padding:2px 8px; border-radius:8px;">
                Skor {{ $attempt->score }}
              </span>
              <span style="font-size:11px; color:#6b7c74; font-weight:600; background:#f5f2ec; padding:2px 8px; border-radius:8px;">
                {{ $correct }}/{{ $total }} benar
              </span>
              <span style="font-size:11px; color:#a09882; align-self:center;">{{ $attempt->created_at->diffForHumans() }}</span>
            </div>
            <span style="display:inline-block; margin-top:6px; font-size:10px; font-weight:700; padding:3px 9px; border-radius:20px;
              background:{{ $isFinished ? '#e1f5f0' : '#fdf3e0' }};
              color:{{ $isFinished ? '#1a7a5e' : '#a06900' }};">
              {{ $isFinished ? 'Selesai' : 'Belum Selesai' }}
            </span>
          </div>

          <div style="flex-shrink:0;">
            @if($isFinished)
              <a href="{{ route('siswa.quiz.result', $attempt->id) }}"
                 style="display:inline-block; border:2px solid #ddd; background:#fff; color:#4a5a52; font-size:11px; font-weight:700; padding:7px 14px; border-radius:20px; text-decoration:none;">
                Lihat
              </a>
            @else
              <a href="{{ route('siswa.quiz.continue', $attempt->id) }}"
                 style="display:inline-block; background:linear-gradient(135deg,#ffe9a8,#d4a843); color:#7a5a1a; font-size:11px; font-weight:700; padding:7px 14px; border-radius:20px; text-decoration:none; box-shadow:0 4px 10px -3px rgba(212,168,67,0.5);">
                Lanjutkan
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

@empty
  <div style="background:#fff; border-radius:16px; border:2px dashed #d0cbc2; padding:32px 20px; text-align:center;">
    <svg style="width:32px;height:32px;color:#c8d5cc;margin:0 auto 10px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
    </svg>
    <p style="font-size:13px; color:#a09882; font-weight:600;">Belum ada riwayat quiz.</p>
    <p style="font-size:11px; color:#c2bcae; margin-top:4px;">Riwayat akan muncul setelah kamu mengerjakan quiz pertama.</p>
  </div>
@endforelse

@endsection