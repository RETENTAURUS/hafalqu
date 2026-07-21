{{--
    Partial jalur hafalan (satu track). Dipakai 2x di index.blade.php:
    sekali untuk jalur "Di Sekolah", sekali untuk jalur "Di Rumah".

    Variabel yang diharapkan sudah di-pass lewat @include:
    - $list         : koleksi Quiz untuk track ini (sudah terurut by `order`)
    - $quizStatus   : array status per quiz (dari SiswaQuizController::buildQuizStatus)
    - $emptyMessage : teks yang ditampilkan kalau track ini kosong
--}}
@php
    $nextQuizId = null;
    foreach ($list as $q) {
        $st = $quizStatus[$q->id] ?? null;
        if ($st && $st['canAccess'] && !$st['isPerfect']) { $nextQuizId = $q->id; break; }
    }
    $totalDitaklukkan = 0;
    foreach ($list as $q) {
        $st = $quizStatus[$q->id] ?? null;
        if ($st && $st['isPerfect']) $totalDitaklukkan++;
    }
@endphp

@if($list->isEmpty())
  <div style="position: relative; z-index: 1; background:#fff; border-radius:20px; border:3px dashed #d0cbc2; padding:50px 20px; text-align:center;">
    <svg style="width:44px;height:44px;color:#c8d5cc;margin:0 auto 12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 11l18-8-8 18-2-8-8-2z"/></svg>
    <p class="hq-display" style="font-size:16px; color:#8a8272; font-weight:800; margin:0;">{{ $emptyMessage ?? 'Belum ada jalur terbuka' }}</p>
    <p style="font-size:13px; color:#b7ae9e; margin:6px 0 0;">Pos quiz pertama akan otomatis muncul di sini begitu dirilis gurumu!</p>
  </div>
@else
  <div class="hq-track">
    {{-- Garis Penghubung --}}
    @php $rowHeight = 120; $topOffset = 32; @endphp
    <div style="position:relative;">
      @foreach($list as $i => $quiz)
        @php
          $st = $quizStatus[$quiz->id] ?? null;
          $prevSt = $i > 0 ? ($quizStatus[$list[$i-1]->id] ?? null) : null;
          $segmentDone = $i > 0 && $prevSt && $prevSt['canAccess'];
        @endphp
        @if($i > 0)
          <div class="hq-segment" style="top:{{ $topOffset + ($i-1) * $rowHeight + 35 }}px; height:{{ $rowHeight }}px; background:{{ $segmentDone ? 'var(--line-done)' : 'var(--line-todo)' }};"></div>
        @endif
      @endforeach
    </div>

    {{-- Render Baris Jalur --}}
    @foreach($list as $i => $quiz)
      @php
        $st = $quizStatus[$quiz->id] ?? null;
        $canAccess = $st ? $st['canAccess'] : false;
        $isPerfect = $st ? $st['isPerfect'] : false;
        $isCurrent = $quiz->id === $nextQuizId;
        $side = $i % 2 === 0 ? 'hq-left' : 'hq-right';
      @endphp

      <div class="hq-row {{ $side }}" style="min-height:{{ $rowHeight }}px; margin-bottom: 10px;">

        <div class="hq-spacer"></div>

        {{-- Node Tombol Fisik (3D Look) --}}
        <div class="hq-node {{ $isCurrent ? 'hq-node-current' : '' }}" style="width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; position:relative;
          background:{{ $isPerfect ? 'linear-gradient(135deg,#ffe9a8,#d4a843)' : ($canAccess ? 'linear-gradient(135deg,#6fc9a8,#2d7a5f)' : '#e7e2d6') }};
          border:3px solid #fff;
          box-shadow: 0 {{ $isCurrent ? '8px' : '6px' }} 0 {{ $isPerfect ? '#ab8126' : ($canAccess ? '#1a3a2e' : '#b0aaa0') }};">

          @if($isPerfect)
            <svg style="width:26px;height:26px;color:#7a5a1a;" viewBox="0 0 24 24" fill="currentColor"><path d="M2 20h20l-1.6-9.4-4.9 4-2.5-8-2.5 8-4.9-4L2 20z"/></svg>
          @elseif($canAccess)
            <svg style="width:22px;height:22px;color:#fff; margin-left:3px;" viewBox="0 0 24 24" fill="currentColor"><polygon points="8,5 19,12 8,19"/></svg>
          @else
            <svg style="width:20px;height:20px;color:#a09882;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/></svg>
          @endif

          @if($isCurrent)
            <svg class="hq-flag" style="position:absolute; top:-20px; right:-12px; width:26px;height:26px;color:#e65c40; filter: drop-shadow(0 2px 0 #b3361e);" viewBox="0 0 24 24" fill="currentColor"><path d="M5 2v20h2v-8l3-1 3 1 3-1 3 1V4l-3-1-3 1-3-1-3 1z"/></svg>
          @endif
        </div>

        {{-- Kartu Info Dialog --}}
        <div class="hq-callout" style="border:2px solid {{ $isPerfect ? '#ecd9a0' : ($canAccess ? ($isCurrent ? '#6fc9a8' : '#e3ded3') : '#e8e4dc') }};">
          <p class="hq-display" style="font-size:14px; font-weight:800; color:#1a3a2e; margin:0 0 4px 0;">{{ $quiz->title }}</p>

          <div style="display:flex; gap:6px; flex-wrap:wrap; margin-bottom:8px; {{ $side === 'hq-left' ? 'justify-content:flex-end;' : '' }}">
            <span style="font-size:11px; color:#5c6960; font-weight:700; background:#f0ede2; padding:2px 6px; border-radius:6px;">{{ $quiz->soals->count() }} Soal</span>
            @if($quiz->duration)
              <span style="font-size:11px; color:#5c6960; font-weight:700; background:#f0ede2; padding:2px 6px; border-radius:6px;">{{ $quiz->duration }} Mnt</span>
            @endif
            @if($st && $st['attempts'] > 0)
              <span style="font-size:11px; color:#a06900; font-weight:700; background:#fff2cc; padding:2px 6px; border-radius:6px;">{{ $st['attempts'] }}x Uji</span>
            @endif
          </div>

          @if($st && $st['message'] && !in_array($st['message'], ['Mulai','Ulangi','Coba Lagi']))
            <p style="font-size:11px; margin:4px 0 8px 0; color:#8a6d20; font-weight:600; background:#fdf9ee; padding:4px 8px; border-radius:8px; display:inline-block;">🎯 {{ $st['message'] }}</p>
          @endif

          <div style="{{ $side === 'hq-left' ? 'display:flex; justify-content:flex-end;' : '' }}">
            @if($canAccess)
              @if($isPerfect)
                <a href="{{ route('siswa.quiz.confirm', $quiz->id) }}" class="hq-btn-3d hq-btn-secondary">
                  Asah Lagi
                </a>
              @else
                <a href="{{ route('siswa.quiz.confirm', $quiz->id) }}" class="hq-btn-3d hq-btn-primary">
                  {{ $isCurrent ? 'Mulai Misi' : $st['message'] }}
                </a>
              @endif
            @else
              <span style="display:inline-block; color:#a09882; font-size:11px; font-weight:700; font-style:italic;">🔒 Belum Terbuka</span>
            @endif
          </div>
        </div>

      </div>
    @endforeach

    {{-- Penutup Jalur Akhir --}}
    <div style="display:flex; justify-content:center; margin-top:25px;">
      @if($totalDitaklukkan === $list->count() && $list->count() > 0)
        <div style="display:flex; align-items:center; gap:10px; background:linear-gradient(135deg,#ffe9a8,#d4a843); padding:12px 24px; border-radius:24px; box-shadow: 0 5px 0 #ab8126;">
          <svg style="width:22px;height:22px;color:#7a5a1a;" viewBox="0 0 24 24" fill="currentColor"><path d="M2 20h20l-1.6-9.4-4.9 4-2.5-8-2.5 8-4.9-4L2 20z"/></svg>
          <span class="hq-display" style="font-size:14px; font-weight:800; color:#7a5a1a;">Hebat! Kamu Menguasai Seluruh Jalur! 🎉</span>
        </div>
      @else
        <div style="width:48px; height:48px; border-radius:50%; background:#f0ede2; display:flex; align-items:center; justify-content:center; border:3px dashed #d0cbc2;">
          <svg style="width:20px;height:20px;color:#b7ae9e;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        </div>
      @endif
    </div>
  </div>
@endif