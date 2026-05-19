@extends('layouts.app')

@section('title', 'Professor Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('professor.dashboard') }}" class="active"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">My Teaching</span>
    <a href="{{ route('professor.courses.index') }}"><i class="bi bi-collection-play-fill"></i> My Courses</a>
    <a href="{{ route('professor.courses.create') }}"><i class="bi bi-plus-circle"></i> New Course</a>
    <a href="{{ route('professor.students.index') }}"><i class="bi bi-people-fill"></i> My Students</a>
@endsection

@section('content')


<div style="background:linear-gradient(135deg,#4f46e5,#06b6d4);border-radius:16px;padding:28px 32px;color:#fff;margin-bottom:28px;display:flex;align-items:center;gap:20px;">
    <img src="{{ $professor->avatar_url }}" alt=""
         style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.4);">
    <div>
        <div style="font-size:22px;font-weight:800;">Welcome back, {{ explode(' ', $professor->name)[0] }}! 👋</div>
        <div style="opacity:.85;font-size:14px;margin-top:4px;">
            You have {{ $stats['courses'] }} {{ Str::plural('course', $stats['courses']) }} and
            {{ $stats['students'] }} enrolled {{ Str::plural('student', $stats['students']) }}.
        </div>
    </div>
</div>


<div class="row g-4 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-collection-play-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['courses'] }}</div>
                <div class="stat-label">My Courses</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['students'] }}</div>
                <div class="stat-label">Enrolled Students</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon teal"><i class="bi bi-patch-check-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['graded'] }}</div>
                <div class="stat-label">Grades Given</div>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-collection-play me-2 text-primary"></i>My Courses</span>
        <a href="{{ route('professor.courses.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i> New Course
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Course</th>
                    <th>Level</th>
                    <th>Students</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                <tr>
                    <td class="ps-4">
                        <div style="font-weight:600;font-size:14px;">{{ $course->title }}</div>
                        <div style="font-size:12px;color:#94a3b8;">{{ $course->category }} · {{ $course->duration_hours }}h</div>
                    </td>
                    <td>{!! $course->level_badge !!}</td>
                    <td>
                        <span class="badge badge-info">{{ $course->enrollments_count }} students</span>
                    </td>
                    <td>
                        @if($course->is_published)
                            <span class="badge badge-success">Published</span>
                        @else
                            <span class="badge badge-secondary">Draft</span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('professor.courses.students', $course) }}"
                               class="btn btn-sm btn-outline-primary" title="View Students">
                                <i class="bi bi-people"></i>
                            </a>
                            <a href="{{ route('professor.courses.edit', $course) }}"
                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a href="{{ route('professor.exams.index', $course) }}"
                               class="btn btn-sm btn-outline-warning" title="Exam">
                                <i class="bi bi-file-earmark-text"></i>
                            </a>
                            <form method="POST" action="{{ route('professor.courses.destroy', $course) }}"
                                  onsubmit="return confirm('Delete this course?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-collection-play" style="font-size:36px;display:block;margin-bottom:8px;"></i>
                        No courses yet.
                        <a href="{{ route('professor.courses.create') }}">Create your first course</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
