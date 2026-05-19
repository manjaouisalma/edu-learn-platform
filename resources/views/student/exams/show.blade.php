@extends('layouts.app')

@section('title', 'Exam')
@section('page-title', 'Exam')

@section('sidebar-nav')
    <span class="nav-section-label">Learning</span>
    <a href="{{ route('student.courses.index') }}"><i class="bi bi-collection-play-fill"></i> Browse Courses</a>
    <a href="{{ route('student.my-courses') }}"><i class="bi bi-journal-check"></i> My Courses</a>
    <span class="nav-section-label">Account</span>
    <a href="{{ route('student.profile') }}"><i class="bi bi-person-circle"></i> Profile</a>
@endsection

@section('content')

@if($attempt)
    <div class="text-center py-5">
        <div style="width:80px;height:80px;background:#fef9c3;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <i class="bi bi-patch-check-fill" style="font-size:36px;color:#eab308;"></i>
        </div>
        <h4 style="font-weight:700;">You already took this exam</h4>
        <p class="text-muted">Your score: <strong>{{ $attempt->score }}%</strong></p>
        <a href="{{ route('student.exams.result', ['course' => $course->id, 'attempt' => $attempt->id]) }}"
           class="btn btn-primary mt-3">
            <i class="bi bi-eye me-1"></i> View Result
        </a>
    </div>

@else
    <div class="card mb-4" style="border:none;background:linear-gradient(135deg,#6366f1,#06b6d4);color:white;border-radius:16px;">
        <div class="card-body p-4">
            <h4 style="font-weight:700;margin-bottom:8px;">{{ $exam->title }}</h4>
            <div class="d-flex gap-3 flex-wrap" style="font-size:13px;opacity:0.9;">
                <span><i class="bi bi-question-circle me-1"></i> {{ $exam->questions->count() }} Questions</span>
                <span><i class="bi bi-trophy me-1"></i> Pass Score: {{ $exam->pass_score }}%</span>
                @if($exam->duration_minutes)
                    <span><i class="bi bi-clock me-1"></i>
                        <span id="timer">{{ sprintf('%02d:%02d', $exam->duration_minutes, 0) }}</span>
                    </span>
                @endif
            </div>

            <div class="mt-3">
                <div style="font-size:12px;margin-bottom:6px;opacity:0.85;">
                    Question <span id="current-num">1</span> of {{ $exam->questions->count() }}
                </div>
                <div style="background:rgba(255,255,255,0.25);border-radius:20px;height:6px;">
                    <div id="progress-bar"
                         style="background:white;height:6px;border-radius:20px;width:{{ round(100/$exam->questions->count()) }}%;transition:width 0.4s ease;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 mb-4" id="dots-nav">
        @foreach($exam->questions as $i => $q)
        <button type="button"
                onclick="goToQuestion({{ $i }})"
                id="dot-{{ $i }}"
                style="width:36px;height:36px;border-radius:50%;border:2px solid #6366f1;background:white;color:#6366f1;font-weight:600;font-size:13px;cursor:pointer;transition:all 0.2s;">
            {{ $i + 1 }}
        </button>
        @endforeach
    </div>

    <form method="POST" action="{{ route('student.exams.submit', $course) }}" id="exam-form">
        @csrf

        @foreach($exam->questions as $index => $question)
        <div class="question-card card mb-3"
             id="question-{{ $index }}"
             style="display:{{ $index === 0 ? 'block' : 'none' }};border-radius:16px;border:1px solid #e2e8f0;">
            <div class="card-body p-4">

                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;background:linear-gradient(135deg,#6366f1,#06b6d4);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;flex-shrink:0;">
                        {{ $index + 1 }}
                    </div>
                    <p style="font-weight:600;margin:0;font-size:16px;color:#1e293b;">
                        {{ $question->question }}
                    </p>
                </div>

                <div class="options-list mt-3">
                    @foreach($question->options as $key => $option)
                    <label for="q{{ $question->id }}_{{ $key }}"
                           class="option-label d-flex align-items-center gap-3 p-3 mb-2"
                           style="border:2px solid #e2e8f0;border-radius:12px;cursor:pointer;transition:all 0.2s;background:white;">
                        <input class="d-none" type="radio"
                               name="answers[{{ $question->id }}]"
                               value="{{ $key }}"
                               id="q{{ $question->id }}_{{ $key }}"
                               onchange="onAnswer({{ $index }}, this)">
                        <div class="option-circle"
                             style="width:28px;height:28px;border-radius:50%;border:2px solid #cbd5e1;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-weight:700;font-size:13px;color:#94a3b8;transition:all 0.2s;">
                            {{ strtoupper(chr(65 + $key)) }}
                        </div>
                        <span style="font-size:15px;color:#334155;">{{ $option }}</span>
                    </label>
                    @endforeach
                </div>

            </div>
        </div>
        @endforeach
        <div class="d-flex justify-content-between align-items-center mt-4">
            <button type="button" onclick="prevQuestion()"
                    id="btn-prev"
                    style="display:none;"
                    class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-left me-1"></i> Previous
            </button>

            <div style="flex:1;"></div>

            <button type="button" onclick="nextQuestion()"
                    id="btn-next"
                    class="btn px-4"
                    style="background:linear-gradient(135deg,#6366f1,#06b6d4);color:white;border:none;">
                Next <i class="bi bi-arrow-right ms-1"></i>
            </button>

            <button type="button" onclick="confirmSubmit()"
                    id="btn-submit"
                    style="display:none;background:linear-gradient(135deg,#6366f1,#06b6d4);color:white;border:none;"
                    class="btn px-5">
                <i class="bi bi-send me-1"></i> Submit Exam
            </button>
        </div>

    </form>

    <div id="confirm-modal"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
        <div style="background:white;border-radius:20px;padding:32px;max-width:420px;width:90%;text-align:center;">
            <div style="width:64px;height:64px;background:linear-gradient(135deg,#ede9fe,#cffafe);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="bi bi-patch-question-fill" style="font-size:28px;color:#6366f1;"></i>
            </div>
            <h5 style="font-weight:700;margin-bottom:8px;">Submit Exam?</h5>
            <p class="text-muted" style="font-size:14px;" id="modal-msg">Are you sure you want to submit?</p>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <button onclick="closeModal()" class="btn btn-outline-secondary px-4">Cancel</button>
                <button onclick="document.getElementById('exam-form').submit()"
                        class="btn px-4"
                        style="background:linear-gradient(135deg,#6366f1,#06b6d4);color:white;border:none;">
                    Yes, Submit
                </button>
            </div>
        </div>
    </div>

@endif

@endsection

@push('scripts')
<script>
    const totalQuestions = {{ $exam->questions->count() }};
    let currentIndex = 0;
    let answers = {};

    function goToQuestion(index) {
        document.getElementById('question-' + currentIndex).style.display = 'none';
        currentIndex = index;
        document.getElementById('question-' + currentIndex).style.display = 'block';
        updateUI();
    }

    function nextQuestion() {
        if (currentIndex < totalQuestions - 1) {
            goToQuestion(currentIndex + 1);
        }
    }

    function prevQuestion() {
        if (currentIndex > 0) {
            goToQuestion(currentIndex - 1);
        }
    }

    function updateUI() {
        document.getElementById('current-num').textContent = currentIndex + 1;

        const pct = Math.round(((currentIndex + 1) / totalQuestions) * 100);
        document.getElementById('progress-bar').style.width = pct + '%';

        document.getElementById('btn-prev').style.display = currentIndex === 0 ? 'none' : 'inline-flex';
        document.getElementById('btn-next').style.display = currentIndex === totalQuestions - 1 ? 'none' : 'inline-flex';
        document.getElementById('btn-submit').style.display = currentIndex === totalQuestions - 1 ? 'inline-flex' : 'none';

        for (let i = 0; i < totalQuestions; i++) {
            const dot = document.getElementById('dot-' + i);
            if (i === currentIndex) {
                dot.style.background = 'linear-gradient(135deg,#6366f1,#06b6d4)';
                dot.style.color = 'white';
                dot.style.borderColor = '#6366f1';
            } else if (answers[i]) {
                dot.style.background = '#dcfce7';
                dot.style.color = '#16a34a';
                dot.style.borderColor = '#16a34a';
            } else {
                dot.style.background = 'white';
                dot.style.color = '#6366f1';
                dot.style.borderColor = '#6366f1';
            }
        }
    }

    function onAnswer(questionIndex, input) {
        answers[questionIndex] = true;

        const card = document.getElementById('question-' + questionIndex);
        const labels = card.querySelectorAll('.option-label');
        labels.forEach(label => {
            label.style.borderColor = '#e2e8f0';
            label.style.background = 'white';
            const circle = label.querySelector('.option-circle');
            circle.style.background = 'white';
            circle.style.borderColor = '#cbd5e1';
            circle.style.color = '#94a3b8';
        });

        const selectedLabel = input.closest('.option-label');
        selectedLabel.style.borderColor = '#6366f1';
        selectedLabel.style.background = 'linear-gradient(135deg,#ede9fe,#cffafe)';
        const circle = selectedLabel.querySelector('.option-circle');
        circle.style.background = 'linear-gradient(135deg,#6366f1,#06b6d4)';
        circle.style.borderColor = '#6366f1';
        circle.style.color = 'white';

        updateUI();
    }

    function confirmSubmit() {
        const answered = Object.keys(answers).length;
        const unanswered = totalQuestions - answered;
        const msg = unanswered > 0
            ? `You have ${unanswered} unanswered question(s). Still want to submit?`
            : `You answered all ${totalQuestions} questions. Ready to submit?`;
        document.getElementById('modal-msg').textContent = msg;
        document.getElementById('confirm-modal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('confirm-modal').style.display = 'none';
    }

    @if($exam->duration_minutes)
    let timeLeft = {{ $exam->duration_minutes * 60 }};
    const timerEl = document.getElementById('timer');

    const timerInterval = setInterval(() => {
        timeLeft--;
        const m = Math.floor(timeLeft / 60);
        const s = timeLeft % 60;
        timerEl.textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');

        if (timeLeft <= 60) timerEl.style.color = '#fca5a5';
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            document.getElementById('exam-form').submit();
        }
    }, 1000);
    @endif

    updateUI();
</script>
@endpush