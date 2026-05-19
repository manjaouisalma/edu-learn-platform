@extends('layouts.app')

@section('title', 'My Grades')
@section('page-title', 'My Grades')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('student.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">Learning</span>
    <a href="{{ route('student.courses.index') }}"><i class="bi bi-collection-play-fill"></i> Browse Courses</a>
    <a href="{{ route('student.my-courses') }}"><i class="bi bi-journal-check"></i> My Courses</a>
    <a href="{{ route('student.grades') }}" class="active"><i class="bi bi-award"></i> My Grades</a>
    <span class="nav-section-label">Account</span>
    <a href="{{ route('student.profile') }}"><i class="bi bi-person-circle"></i> Profile</a>
@endsection

@section('content')

@if($grades->isNotEmpty())
@php $avg = $grades->avg('score'); @endphp
<div style="background:linear-gradient(135deg,#4f46e5,#06b6d4);border-radius:16px;padding:24px 32px;color:#fff;margin-bottom:28px;display:flex;align-items:center;gap:24px;">
    <div style="text-align:center;">
        <div style="font-size:48px;font-weight:900;line-height:1;">{{ number_format($avg, 1) }}</div>
        <div style="opacity:.8;font-size:13px;">Overall Average</div>
    </div>
    <div style="width:1px;height:60px;background:rgba(255,255,255,.2);"></div>
    <div>
        <div style="font-size:18px;font-weight:700;">
            Grade: {{ \App\Models\Grade::calculateLetterGrade($avg) }}
        </div>
        <div style="opacity:.8;font-size:14px;">Based on {{ $grades->total() }} graded {{ Str::plural('course', $grades->total()) }}</div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <i class="bi bi-award me-2 text-primary"></i> All Grades
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Course</th>
                    <th>Professor</th>
                    <th>Score</th>
                    <th>Grade</th>
                    <th>Feedback</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $grade)
                <tr>
                    <td class="ps-4" style="max-width:200px;">
                        <div style="font-weight:600;font-size:14px;" class="text-truncate">{{ $grade->course->title }}</div>
                        <div style="font-size:12px;color:#94a3b8;">{{ $grade->course->category }}</div>
                    </td>
                    <td style="font-size:13px;color:#64748b;">{{ $grade->course->professor->name }}</td>
                    <td>
                        <div style="font-weight:700;font-size:15px;">{{ number_format($grade->score, 1) }}<span style="font-size:11px;color:#94a3b8;font-weight:400;">/100</span></div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $grade->grade_color }}" style="font-size:14px;padding:5px 12px;">
                            {{ $grade->letter_grade }}
                        </span>
                    </td>
                    <td style="max-width:220px;">
                        @if($grade->feedback)
                            <span style="font-size:13px;color:#64748b;" class="text-truncate d-block">
                                {{ Str::limit($grade->feedback, 60) }}
                            </span>
                        @else
                            <span style="font-size:12px;color:#cbd5e1;">No feedback</span>
                        @endif
                    </td>
                    <td style="font-size:13px;color:#64748b;">{{ $grade->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-award" style="font-size:36px;display:block;margin-bottom:8px;opacity:.3;"></i>
                        No grades yet. Complete your courses to receive grades.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($grades->hasPages())
    <div class="card-footer bg-white border-top px-4 py-3">
        {{ $grades->links() }}
    </div>
    @endif
</div>
@endsection
