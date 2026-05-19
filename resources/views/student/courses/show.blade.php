@extends('layouts.app')

@section('title', $course->title)
@section('page-title', 'Course Details')

@section('sidebar-nav')
    <a href="{{ route('student.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a-->
    <span class="nav-section-label">Learning</span>
    <a href="{{ route('student.courses.index') }}" class="active"><i class="bi bi-collection-play-fill"></i> Browse Courses</a>
    <a href="{{ route('student.my-courses') }}"><i class="bi bi-journal-check"></i> My Courses</a>
    <span class="nav-section-label">Account</span>
    <a href="{{ route('student.profile') }}"><i class="bi bi-person-circle"></i> Profile</a>
@endsection

@section('content')
<div class="mb-4">
    <a href="{{ route('student.courses.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Courses
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
<div style="border-radius:16px;overflow:hidden;margin-bottom:24px;position:relative;">

    @if($course->image)
        <img src="{{ asset('storage/' . $course->image) }}"
             alt="{{ $course->title }}"
             style="width:100%;height:280px;object-fit:cover;display:block;">
        <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,0.15),rgba(0,0,0,0.75));"></div>
    @else
        <div style="width:100%;height:280px;background:linear-gradient(135deg,#4f46e5,#06b6d4);"></div>
        <div style="position:absolute;inset:0;"></div>
    @endif
    <div style="position:absolute;bottom:0;left:0;right:0;padding:32px;color:#fff;">
        <div class="d-flex gap-2 mb-3">
            {!! $course->level_badge !!}
            <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;">
                {{ $course->category }}
            </span>
        </div>
        <h2 style="font-weight:800;margin-bottom:12px;">{{ $course->title }}</h2>
        <p style="opacity:.9;font-size:15px;line-height:1.6;">{{ $course->description }}</p>
        <div class="d-flex align-items-center gap-3 mt-3" style="font-size:14px;opacity:.85;">
            <span><i class="bi bi-clock me-1"></i>{{ $course->duration_hours }} hours</span>
            <span><i class="bi bi-people me-1"></i>{{ $course->enrollments_count }} students</span>
            @if($course->max_students)
                <span><i class="bi bi-person-check me-1"></i>{{ $course->max_students }} max</span>
            @endif
        </div>
    </div>

</div>
        <div class="card">
            <div class="card-header">About This Course</div>
            <div class="card-body" style="line-height:1.7;color:#374151;">
                {{ $course->description }}
            </div>
        </div>
    </div>
    <div class="col-lg-4">

        <div class="card mb-4 sticky-top" style="top:80px;">
            <div class="card-body p-4 text-center">
                <div style="font-size:28px;font-weight:800;color:#0f172a;margin-bottom:4px;">
                    @if($course->is_free)
                        <span style="color:#10b981;">Free</span>
                    @else
                        ${{ number_format($course->price, 2) }}
                    @endif
                </div>

                @if($isEnrolled)
                    <div class="alert alert-success py-2 px-3 mb-3" style="font-size:13px;">
                        <i class="bi bi-check-circle-fill me-1"></i> You're enrolled
                    </div>
                    <form method="POST" action="{{ route('student.courses.unenroll', $course) }}"
                          onsubmit="return confirm('Are you sure you want to unenroll?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-1"></i> Unenroll
                        </button>
                    </form>

                    @if($course->exam)
                        <a href="{{ route('student.exams.show', $course) }}"
                           class="btn btn-warning w-100 mt-3"
                           style="font-size:15px;padding:12px;">
                            <i class="bi bi-pencil-square me-1"></i> Take Exam
                        </a>
                    @endif

                @else
                    <p style="font-size:13px;color:#64748b;margin-bottom:16px;">
                        Join {{ $course->enrollments_count }} students already learning.
                    </p>
                    <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                        @csrf
                        <button class="btn btn-primary w-100" style="font-size:15px;padding:12px;">
                            <i class="bi bi-mortarboard me-1"></i> Enroll Now
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">Instructor</div>
            <div class="card-body d-flex align-items-center gap-3">
                <img src="{{ $course->professor->avatar_url }}" alt=""
                     style="width:52px;height:52px;border-radius:50%;object-fit:cover;">
                <div>
                    <div style="font-weight:700;font-size:15px;">{{ $course->professor->name }}</div>
                    @if($course->professor->bio)
                        <div style="font-size:12px;color:#64748b;margin-top:4px;">{{ Str::limit($course->professor->bio, 80) }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection