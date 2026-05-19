@extends('layouts.app')

@section('title', 'My Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('student.dashboard') }}" class="active"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">Learning</span>
    <a href="{{ route('student.courses.index') }}"><i class="bi bi-collection-play-fill"></i> Browse Courses</a>
    <a href="{{ route('student.my-courses') }}"><i class="bi bi-journal-check"></i> My Courses</a>
    <a href="{{ route('student.grades') }}"><i class="bi bi-award"></i> My Grades</a>
    <span class="nav-section-label">Account</span>
    <a href="{{ route('student.profile') }}"><i class="bi bi-person-circle"></i> Profile</a>
@endsection

@section('content')

<div style="background:linear-gradient(135deg,#4f46e5,#06b6d4);border-radius:16px;padding:28px 32px;color:#fff;margin-bottom:28px;display:flex;align-items:center;gap:20px;">
    <img src="{{ $student->avatar_url }}" alt=""
         style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.4);">
    <div>
        <div style="font-size:22px;font-weight:800;">
            Hello, {{ explode(' ', $student->name)[0] }}! 🎓
        </div>
        <div style="opacity:.85;font-size:14px;margin-top:4px;">
            Keep learning — you're enrolled in {{ $stats['enrolled'] }} {{ Str::plural('course', $stats['enrolled']) }}.
        </div>
    </div>
    <div class="ms-auto">
        <a href="{{ route('student.courses.index') }}"
           style="background:rgba(255,255,255,.2);color:#fff;padding:10px 20px;border-radius:10px;text-decoration:none;font-weight:600;font-size:14px;backdrop-filter:blur(4px);">
            <i class="bi bi-search me-1"></i> Explore Courses
        </a>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-journal-check"></i></div>
            <div>
                <div class="stat-value">{{ $stats['enrolled'] }}</div>
                <div class="stat-label">Enrolled</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon teal"><i class="bi bi-patch-check-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['completed'] }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-award-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['graded'] }}</div>
                <div class="stat-label">Graded</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon cyan"><i class="bi bi-graph-up"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['average'], 1) }}</div>
                <div class="stat-label">Avg. Score</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-journal-check me-2 text-primary"></i>My Recent Courses</span>
        <a href="{{ route('student.my-courses') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Course</th>
                    <th>Professor</th>
                    <th>Status</th>
                    <th>Enrolled</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                <tr>
                    <td class="ps-4">
                        <div style="font-weight:600;font-size:14px;">{{ $enrollment->course->title }}</div>
                        <div style="font-size:12px;color:#94a3b8;">{{ $enrollment->course->category }}</div>
                    </td>
                    <td style="font-size:13px;color:#64748b;">{{ $enrollment->course->professor->name }}</td>
                    <td>
                        @php
                            $statusColors = ['active'=>'badge-info','completed'=>'badge-success','dropped'=>'badge-danger'];
                        @endphp
                        <span class="badge {{ $statusColors[$enrollment->status] ?? 'badge-secondary' }}">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </td>
                    <td style="font-size:13px;color:#64748b;">
                        {{ \Carbon\Carbon::parse($enrollment->enrolled_at)->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="bi bi-mortarboard" style="font-size:36px;display:block;margin-bottom:8px;"></i>
                        You haven't enrolled in any courses yet.
                        <a href="{{ route('student.courses.index') }}">Browse courses</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
