@extends('layouts.app')

@section('title', 'Grade Student')
@section('page-title', 'Grade Student')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('professor.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">My Teaching</span>
    <a href="{{ route('professor.courses.index') }}" class="active"><i class="bi bi-collection-play-fill"></i> My Courses</a>
    <a href="{{ route('professor.courses.create') }}"><i class="bi bi-plus-circle"></i> New Course</a>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">

        {{-- Student Info --}}
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <img src="{{ $enrollment->student->avatar_url }}" alt=""
                     style="width:52px;height:52px;border-radius:50%;object-fit:cover;">
                <div>
                    <div style="font-weight:700;font-size:16px;">{{ $enrollment->student->name }}</div>
                    <div style="font-size:13px;color:#64748b;">
                        {{ $enrollment->student->email }} ·
                        Enrolled {{ \Carbon\Carbon::parse($enrollment->enrolled_at)->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Grade Form --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-award me-2 text-primary"></i>
                {{ $enrollment->grade ? 'Update Grade' : 'Assign Grade' }} for
                <strong>{{ $course->title }}</strong>
            </div>
            <div class="card-body p-4">

                @if($enrollment->grade)
                <div class="alert alert-info d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-info-circle-fill"></i>
                    Current grade:
                    <strong>{{ $enrollment->grade->letter_grade }}
                    ({{ number_format($enrollment->grade->score, 1) }}/100)</strong>
                </div>
                @endif

                <form method="POST" action="{{ route('professor.courses.grade.save', [$course, $enrollment]) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Score (0 – 100) *</label>
                        <input type="number" name="score"
                               class="form-control form-control-lg @error('score') is-invalid @enderror"
                               value="{{ old('score', $enrollment->grade->score ?? '') }}"
                               min="0" max="100" step="0.5"
                               placeholder="e.g., 87.5" required>
                        @error('score')<div class="invalid-feedback">{{ $message }}</div>@enderror

                        {{-- Live letter grade preview --}}
                        <div class="mt-2 d-flex gap-2" id="grade-preview">
                            @foreach(['A'=>[90,100],'B'=>[80,89],'C'=>[70,79],'D'=>[60,69],'F'=>[0,59]] as $letter => [$min,$max])
                            <span style="background:#f1f5f9;border-radius:6px;padding:4px 10px;font-size:12px;font-weight:600;color:#64748b;">
                                {{ $letter }}: {{ $min }}–{{ $max }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Feedback <span class="text-muted">(optional)</span></label>
                        <textarea name="feedback" class="form-control" rows="4"
                                  placeholder="Leave constructive feedback for the student...">{{ old('feedback', $enrollment->grade->feedback ?? '') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i>
                            {{ $enrollment->grade ? 'Update Grade' : 'Save Grade' }}
                        </button>
                        <a href="{{ route('professor.courses.students', $course) }}"
                           class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
