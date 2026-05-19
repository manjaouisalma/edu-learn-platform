@extends('layouts.app')
@section('title', 'Manage Exam')
@section('page-title', 'Manage Exam')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('professor.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">My Teaching</span>
    <a href="{{ route('professor.courses.index') }}" class="active"><i class="bi bi-collection-play-fill"></i> My Courses</a>
    <a href="{{ route('professor.courses.create') }}"><i class="bi bi-plus-circle"></i> New Course</a>
    <a href="{{ route('professor.students.index') }}"><i class="bi bi-people-fill"></i> My Students</a>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($exam)
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-earmark-text me-2"></i> {{ $exam->title }}</span>
        <form method="POST" action="{{ route('professor.exams.destroy', $exam) }}">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i> Delete Exam
            </button>
        </form>
    </div>
    <div class="card-body">
        <p style="font-size:13px;color:#64748b;">
            <i class="bi bi-trophy me-1"></i> Pass Score: <strong>{{ $exam->pass_score }}%</strong>
            &nbsp;|&nbsp;
            <i class="bi bi-question-circle me-1"></i> Questions: <strong>{{ $exam->questions->count() }}</strong>
        </p>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Correct Answer</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exam->questions as $i => $q)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $q->question }}</td>
                    <td><span class="badge badge-success">{{ $q->options[$q->correct] ?? $q->correct }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@else
<div class="card">
    <div class="card-header">
        <i class="bi bi-plus-circle me-2"></i> Create Exam for "{{ $course->title }}"
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('professor.exams.store', $course) }}" id="exam-form">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Exam Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Pass Score (%)</label>
                    <input type="number" name="pass_score" class="form-control" value="50" min="1" max="100" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Duration (min)</label>
                    <input type="number" name="duration_minutes" class="form-control" placeholder="Optional">
                </div>
            </div>

            <hr>
            <h6 class="fw-bold mb-3">Questions</h6>

            <div id="questions-container"></div>

            <button type="button" class="btn btn-outline-primary mt-2" onclick="addQuestion()">
                <i class="bi bi-plus-lg me-1"></i> Add Question
            </button>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bi bi-save me-1"></i> Save Exam
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
let questionCount = 0;

function addQuestion() {
    questionCount++;
    const i = questionCount - 1;
    const container = document.getElementById('questions-container');

    const div = document.createElement('div');
    div.className = 'card mb-3';
    div.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <strong>Question ${questionCount}</strong>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.card').remove()">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="mb-3">
                <input type="text" name="questions[${i}][question]" class="form-control" placeholder="Question text" required>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" name="questions[${i}][options][0]" class="form-control" placeholder="Option A" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="questions[${i}][options][1]" class="form-control" placeholder="Option B" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="questions[${i}][options][2]" class="form-control" placeholder="Option C" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="questions[${i}][options][3]" class="form-control" placeholder="Option D" required>
                </div>
            </div>
            <div>
                <label class="form-label fw-semibold">Correct Answer</label>
                <select name="questions[${i}][correct]" class="form-select" required>
                    <option value="0">Option A</option>
                    <option value="1">Option B</option>
                    <option value="2">Option C</option>
                    <option value="3">Option D</option>
                </select>
            </div>
        </div>
    `;
    container.appendChild(div);
}

addQuestion();
</script>
@endpush