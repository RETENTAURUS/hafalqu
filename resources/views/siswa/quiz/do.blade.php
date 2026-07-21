@section('title', 'Mengerjakan Quiz — HafalQU')
@section('page_title', 'Quiz')
@section('page_subtitle', '')

@section('content')

{{-- ═══ HEADER BANNER ═══ --}}
<div style="background:linear-gradient(135deg,#1a3a2e 0%,#2d6a4f 100%); border-radius:12px; padding:20px 24px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between; gap:20px;">

  <div style="flex:1; min-width:0;">
    <p style="font-size:10px; font-weight:700; letter-spacing:.1em; color:#a8d5b5; text-transform:uppercase; margin-bottom:6px;">QUIZ AKTIF</p>
    <p style="font-size:20px; font-weight:800; color:#fff; margin-bottom:4px;">{{ $quiz->title }}</p>
    <p style="font-size:12px; color:#a8d5b5;">
      {{ $soals->count() }} soal
      @if($quiz->duration) · {{ $quiz->duration }} Menit @endif
      · Soal <span id="currentQuestionNumber">1</span>-{{ $soals->count() }}
    </p>
  </div>

  <div style="text-align:center; flex-shrink:0;">
    <p id="timer" style="font-size:40px; font-weight:800; color:#fff; font-family:monospace; line-height:1;">--:--</p>
    <p style="font-size:11px; color:#a8d5b5; margin-top:2px;">Sisa Waktu</p>
  </div>

  <div style="flex-shrink:0; min-width:160px;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
      <p style="font-size:10px; font-weight:700; color:#a8d5b5; text-transform:uppercase; letter-spacing:.08em;">Daftar Soal</p>
      <button id="btnMute" onclick="toggleMute()" title="Matikan/Nyalakan musik"
              style="background:rgba(255,255,255,0.15); border:none; border-radius:6px;
                     width:26px; height:26px; display:flex; align-items:center; justify-content:center;
                     cursor:pointer; flex-shrink:0;">
        <svg id="iconSound" style="width:14px;height:14px;color:#fff;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
          <path d="M19.07 4.93a10 10 0 0 1 0 14.14"/>
          <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
        </svg>
        <svg id="iconMute" style="width:14px;height:14px;color:#a8d5b5;display:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
          <line x1="23" y1="9" x2="17" y2="15"/><line x1="17" y1="9" x2="23" y2="15"/>
        </svg>
      </button>
    </div>

    <div style="display:grid; grid-template-columns:repeat(10,1fr); gap:4px;" id="questionGrid">
      @foreach($soals as $soal)
        <a href="javascript:void(0)" onclick="goToQuestion({{ $loop->iteration }})"
           id="btn-{{ $loop->iteration }}"
           data-question="{{ $loop->iteration }}"
           data-soal-id="{{ $soal->id }}"
           style="width:22px; height:22px; border-radius:4px; display:flex; align-items:center; justify-content:center;
                  font-size:10px; font-weight:600; text-decoration:none; transition:all .15s;
                  background:{{ in_array($soal->id, $answeredSoalIds) ? '#fff' : 'rgba(255,255,255,0.15)' }};
                  color:{{ in_array($soal->id, $answeredSoalIds) ? '#1a3a2e' : '#fff' }};">
          {{ $loop->iteration }}
        </a>
      @endforeach
    </div>
    <p style="font-size:10px; color:#a8d5b5; margin-top:6px;">
      Terjawab: <strong style="color:#fff;" id="answeredCount">{{ count($answeredSoalIds) }}</strong> / {{ $soals->count() }}
    </p>
  </div>

</div>

{{-- ═══ SOAL AREA ═══ --}}
<div style="background:#fff; border-radius:12px; border:1px solid #e0ddd6; padding:28px; min-height:380px; display:flex; flex-direction:column; justify-content:space-between;">

  <div id="questionContainer" style="flex:1;">
    @foreach($soals as $soal)
    @php
      $selected = in_array($soal->id, $answeredSoalIds)
        ? $attempt->answers->firstWhere('soal_id', $soal->id)?->selected_answer
        : null;
    @endphp
    <div class="question-item"
         data-question="{{ $loop->iteration }}"
         data-soal-id="{{ $soal->id }}"
         style="{{ $loop->first ? '' : 'display:none;' }}">

      @if($soal->ayat ?? null)
        <div style="background:#f7f5f0; border-radius:10px; padding:20px; text-align:center; margin-bottom:20px; border:1px solid #e8e4dc;">
          <p style="font-size:26px; font-family:'Amiri',serif; direction:rtl; line-height:2; color:#1e3a2a;">{{ $soal->ayat }}</p>
        </div>
      @endif

      @if(($soal->jenis ?? null) === 'audio' && $soal->file_audio)
        <div style="background:#f7f5f0; border-radius:10px; padding:20px; text-align:center; margin-bottom:20px; border:1px solid #e8e4dc;">
          <p style="font-size:11px; font-weight:700; letter-spacing:.06em; color:#6b7c74; text-transform:uppercase; margin-bottom:10px;">Dengarkan Audio</p>
          <audio controls controlsList="nodownload" style="width:100%; max-width:420px;">
            <source src="{{ asset('storage/audio_soals/' . $soal->file_audio) }}" type="audio/mpeg">
          </audio>
        </div>
      @endif

      <p style="font-size:14px; color:#374151; margin-bottom:20px; line-height:1.6;">{{ $soal->pertanyaan }}</p>

      <div style="display:flex; flex-direction:column; gap:10px;">
        @foreach(['A','B','C','D'] as $option)
        <label id="label-{{ $soal->id }}-{{ $option }}"
               style="display:flex; align-items:center; gap:12px; padding:12px 16px;
                      border-radius:8px; cursor:pointer; transition:all .15s;
                      border:1.5px solid {{ $selected == $option ? '#1a7a5e' : '#e0ddd6' }};
                      background:{{ $selected == $option ? '#e1f5f0' : '#fff' }};">
          <input type="radio" name="answer_{{ $soal->id }}" value="{{ $option }}"
                 {{ $selected == $option ? 'checked' : '' }}
                 onchange="saveAnswer(this, {{ $soal->id }})"
                 style="width:16px; height:16px; accent-color:#1a7a5e; flex-shrink:0;">
          <span style="font-size:13px; font-weight:600; color:#1a3a2e; flex-shrink:0; width:18px;">{{ $option }}</span>
          <span style="font-size:13px; color:#374151;">{{ $soal->{'opsi_' . strtolower($option)} }}</span>
        </label>
        @endforeach
      </div>
    </div>
    @endforeach
  </div>

  <div style="display:flex; align-items:center; justify-content:space-between; margin-top:28px; padding-top:20px; border-top:1px solid #f0ede6;">
    <button onclick="prevQuestion()" id="btnPrev"
            style="padding:9px 24px; background:#1a3a2e; color:#fff; font-size:13px; font-weight:600; border:none; border-radius:8px; cursor:pointer; visibility:hidden;">
      Sebelum
    </button>
    <div style="display:flex; flex-direction:column; align-items:center; gap:6px;">
      <form action="{{ route('siswa.quiz.finish', $attempt->id) }}" method="POST" id="finishForm" style="margin:0;">
        @csrf
        <button type="submit" id="finishButton"
                style="padding:9px 20px; background:#dc2626; color:#fff; font-size:12px; font-weight:600; border:none; border-radius:8px; cursor:not-allowed; opacity:0.5;">
          Selesai Quiz
        </button>
      </form>
      <div id="finishWarning" style="font-size:12px; color:#dc2626; font-weight:500;">
        Jawab semua soal terlebih dahulu
      </div>
    </div>
    <button onclick="nextQuestion()" id="btnNext"
            style="padding:9px 24px; background:#1a3a2e; color:#fff; font-size:13px; font-weight:600; border:none; border-radius:8px; cursor:pointer;">
      Selanjutnya
    </button>
  </div>

</div>

{{-- 
  Download 1 file audio dari pixabay.com
  Search: "epic adventure quest" atau "mystery kids adventure"
  Rename jadi: quiz-bgm.mp3
  Taruh di: public/audio/quiz/quiz-bgm.mp3
--}}
<audio id="bgm" loop preload="auto">
  <source src="{{ asset('audio/quiz/quiz_audio.mp3') }}" type="audio/mpeg">
</audio>

@section('scripts')
<script>
  let currentQuestion  = 1;
  const totalQuestions = {{ $soals->count() }};
  let answeredSet      = new Set(@json($answeredSoalIds));
  let timeLeft         = {{ $remainingSeconds }};

  const timerEl       = document.getElementById('timer');
  const finishBtn     = document.getElementById('finishButton');
  const finishWarning = document.getElementById('finishWarning');
  const finishForm    = document.getElementById('finishForm');
  const bgm           = document.getElementById('bgm');

  let isMuted    = false;
  let bgmStarted = false;
  bgm.volume     = 0.80;

  // ── AUDIO ──
  function startBgm() {
    if (bgmStarted || isMuted) return;
    bgm.play().then(() => { bgmStarted = true; }).catch(() => {});
  }

  function toggleMute() {
    isMuted   = !isMuted;
    bgm.muted = isMuted;
    document.getElementById('iconSound').style.display = isMuted ? 'none'  : 'block';
    document.getElementById('iconMute').style.display  = isMuted ? 'block' : 'none';
    document.getElementById('btnMute').style.background =
      isMuted ? 'rgba(255,255,255,0.05)' : 'rgba(255,255,255,0.15)';
    if (!isMuted && !bgmStarted) startBgm();
  }

  // ── TIMER ──
  function updateTimerDisplay() {
    const m = Math.floor(timeLeft / 60);
    const s = timeLeft % 60;
    timerEl.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    timerEl.style.color = timeLeft <= 30 ? '#f87171' : (timeLeft <= 60 ? '#fcd34d' : '#fff');
  }

  function startTimer() {
    updateTimerDisplay();
    const iv = setInterval(() => {
      timeLeft--;
      updateTimerDisplay();
      if (timeLeft <= 0) {
        clearInterval(iv);
        bgm.pause();
        finishForm.submit();
      }
    }, 1000);
  }

  // ── FINISH BUTTON ──
  function updateFinishButton() {
    const all = answeredSet.size === totalQuestions;
    finishBtn.disabled      = !all;
    finishBtn.style.opacity = all ? '1'        : '0.5';
    finishBtn.style.cursor  = all ? 'pointer'  : 'not-allowed';
    finishWarning.style.display = all ? 'none' : 'block';
  }

  // ── NAVIGASI ──
  function showQuestion(n) {
    document.querySelectorAll('.question-item').forEach(el => el.style.display = 'none');
    const target = document.querySelector(`.question-item[data-question="${n}"]`);
    if (target) target.style.display = 'block';
    currentQuestion = n;
    document.getElementById('currentQuestionNumber').textContent = n;

    document.querySelectorAll('#questionGrid a').forEach(btn => {
      const isActive = parseInt(btn.dataset.question) === n;
      const answered = answeredSet.has(parseInt(btn.dataset.soalId));
      if (isActive) {
        btn.style.background = '#d4a843';
        btn.style.color      = '#fff';
        btn.style.outline    = '2px solid #fff';
      } else if (answered) {
        btn.style.background = '#fff';
        btn.style.color      = '#1a3a2e';
        btn.style.outline    = 'none';
      } else {
        btn.style.background = 'rgba(255,255,255,0.15)';
        btn.style.color      = '#fff';
        btn.style.outline    = 'none';
      }
    });

    document.getElementById('btnPrev').style.visibility = n > 1              ? 'visible' : 'hidden';
    document.getElementById('btnNext').style.visibility = n < totalQuestions ? 'visible' : 'hidden';
  }

  function nextQuestion() { if (currentQuestion < totalQuestions) showQuestion(currentQuestion + 1); }
  function prevQuestion() { if (currentQuestion > 1) showQuestion(currentQuestion - 1); }
  function goToQuestion(n) { showQuestion(n); }

  // ── SAVE ANSWER ──
  function saveAnswer(radio, soalId) {
    startBgm(); // trigger BGM di interaksi pertama jika autoplay diblokir

    const answer = radio.value;
    ['A','B','C','D'].forEach(opt => {
      const lbl = document.getElementById(`label-${soalId}-${opt}`);
      if (!lbl) return;
      lbl.style.border     = opt === answer ? '1.5px solid #1a7a5e' : '1.5px solid #e0ddd6';
      lbl.style.background = opt === answer ? '#e1f5f0' : '#fff';
    });

    fetch("{{ route('siswa.quiz.save-answer', $attempt->id) }}", {
      method : 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body   : JSON.stringify({ soal_id: soalId, answer })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        answeredSet.add(soalId);
        document.getElementById('answeredCount').textContent = answeredSet.size;
        const btn = document.querySelector(`#questionGrid a[data-soal-id="${soalId}"]`);
        if (btn && parseInt(btn.dataset.question) !== currentQuestion) {
          btn.style.background = '#fff';
          btn.style.color      = '#1a3a2e';
        }
        showQuestion(currentQuestion);
        updateFinishButton();
      }
    });
  }

  // ── SUBMIT ──
  finishForm.addEventListener('submit', function(e) {
    if (answeredSet.size < totalQuestions) {
      e.preventDefault();
      alert('Semua soal harus dijawab sebelum menyelesaikan quiz!');
      return;
    }
    bgm.pause();
  });

  // ── INIT ──
  document.addEventListener('DOMContentLoaded', () => {
    showQuestion(1);
    updateFinishButton();
    startTimer();
    // Coba autoplay — jika gagal, BGM mulai saat user pertama kali klik
    bgm.play().then(() => { bgmStarted = true; }).catch(() => {
      document.body.addEventListener('click', function onFirst() {
        startBgm();
        document.body.removeEventListener('click', onFirst);
      }, { once: true });
    });
  });
</script>
@endsection

@endsection