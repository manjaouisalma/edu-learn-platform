@extends('layouts.app')

@section('title', 'Exam Result')
@section('page-title', 'Exam Result')

@section('sidebar-nav')
    <span class="nav-section-label">Learning</span>
    <a href="{{ route('student.courses.index') }}"><i class="bi bi-collection-play-fill"></i> Browse Courses</a>
    <a href="{{ route('student.my-courses') }}"><i class="bi bi-journal-check"></i> My Courses</a>
    <span class="nav-section-label">Account</span>
    <a href="{{ route('student.profile') }}"><i class="bi bi-person-circle"></i> Profile</a>
@endsection

@section('content')

<div class="text-center py-5">

    @if($attempt->passed)
        <div style="width:100px;height:100px;background:#f0fdf4;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
            <i class="bi bi-patch-check-fill" style="font-size:48px;color:#22c55e;"></i>
        </div>
        <h3 style="font-weight:800;color:#16a34a;">Congratulations! 🎉</h3>
        <p class="text-muted mt-2">You passed the exam for <strong>{{ $course->title }}</strong></p>
    @else
        <div style="width:100px;height:100px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
            <i class="bi bi-x-circle-fill" style="font-size:48px;color:#ef4444;"></i>
        </div>
        <h3 style="font-weight:800;color:#ef4444;">Better luck next time!</h3>
        <p class="text-muted mt-2">You did not pass the exam for <strong>{{ $course->title }}</strong></p>
    @endif

    <div style="background:#f8fafc;border-radius:16px;padding:24px;display:inline-block;margin:24px auto;min-width:200px;">
        <div style="font-size:48px;font-weight:800;color:#1a56db;">{{ $attempt->score }}%</div>
        <div style="font-size:14px;color:#64748b;">Your Score</div>
    </div>
    <div class="d-flex gap-3 justify-content-center mt-2">
        <a href="{{ route('student.my-courses') }}" class="btn btn-outline-secondary">
            <i class="bi bi-journal-check me-1"></i> My Courses
        </a>

        @if($attempt->passed && $certificate)
            <a href="{{ route('student.certificates.download', $certificate) }}" class="btn btn-success">
                <i class="bi bi-download me-1"></i> Download Certificate
            </a>
        @endif
    </div>

</div>

@endsection