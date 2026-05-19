@extends('layouts.app')

@section('title', $user->name)
@section('page-title', 'User Profile')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">Management</span>
    <a href="{{ route('admin.professors.index') }}"><i class="bi bi-person-workspace"></i> Professors</a>
    <a href="{{ route('admin.courses.index') }}"><i class="bi bi-collection-play-fill"></i> Courses</a>
    <a href="{{ route('admin.users.index') }}" class="active"><i class="bi bi-people-fill"></i> All Users</a>
@endsection

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Users
    </a>
</div>

<div class="row g-4">
    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body py-4">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom:12px;">
                <h5 class="mb-1 fw-bold">{{ $user->name }}</h5>
                {!! $user->role_badge !!}
                <div class="text-muted mt-2" style="font-size:13px;">{{ $user->email }}</div>
                @if($user->birth_date)
                <div class="text-muted" style="font-size:13px;">
                    {{ \Carbon\Carbon::parse($user->birth_date)->format('M d, Y') }}
                </div>
                @endif
                @if($user->phone)
                <div class="text-muted" style="font-size:13px;">{{ $user->phone }}</div>
                @endif
                @if($user->bio)
                <p class="mt-3 text-muted" style="font-size:13px;">{{ $user->bio }}</p>
                @endif
                <div class="text-muted mt-2" style="font-size:12px;">
                    Member since {{ $user->created_at->format('M d, Y') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">

        {{-- Professor Courses --}}
        @if($user->isProfessor())
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-collection-play me-2 text-primary"></i>Their Courses</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Title</th>
                            <th>Level</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->taughtCourses as $course)
                        <tr>
                            <td class="ps-4" style="font-size:14px;font-weight:600;">{{ $course->title }}</td>
                            <td>{!! $course->level_badge !!}</td>
                            <td style="font-size:13px;">
                                {{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('M d, Y') : '—' }}
                            </td>
                            <td style="font-size:13px;">
                                {{ $course->end_date ? \Carbon\Carbon::parse($course->end_date)->format('M d, Y') : '—' }}
                            </td>
                            <td>
                                @if($course->is_published)
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">No courses yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Student Enrollments --}}
        @if($user->isStudent())
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-journal-check me-2 text-primary"></i>Enrollments</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Course</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->enrollments as $enroll)
                        <tr>
                            <td class="ps-4" style="font-size:14px;font-weight:600;">{{ $enroll->course->title }}</td>
                            <td style="font-size:13px;">
                                {{ $enroll->course->start_date ? \Carbon\Carbon::parse($enroll->course->start_date)->format('M d, Y') : '—' }}
                            </td>
                            <td style="font-size:13px;">
                                {{ $enroll->course->end_date ? \Carbon\Carbon::parse($enroll->course->end_date)->format('M d, Y') : '—' }}
                            </td>
                            <td>
                                <span class="badge {{ $enroll->status === 'completed' ? 'badge-success' : 'badge-info' }}">
                                    {{ ucfirst($enroll->status) }}
                                </span>
                            </td>
                            <td>
                                @if($enroll->grade)
                                    <span class="badge badge-{{ $enroll->grade->grade_color }}">
                                        {{ $enroll->grade->letter_grade }} ({{ $enroll->grade->score }})
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size:12px;">Not graded</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">No enrollments yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection