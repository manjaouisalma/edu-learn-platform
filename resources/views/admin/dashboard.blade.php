@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>

    <span class="nav-section-label">Management</span>
    <a href="{{ route('admin.professors.index') }}" class="{{ request()->routeIs('admin.professors.*') ? 'active' : '' }}">
        <i class="bi bi-person-workspace"></i> Professors
    </a>
    <a href="{{ route('admin.courses.index') }}" class="{{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
        <i class="bi bi-collection-play-fill"></i> Courses
    </a>
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i> All Users
    </a>
@endsection

@section('content')

{{-- Stats Row --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['students'] }}</div>
                <div class="stat-label">Total Students</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon teal"><i class="bi bi-person-workspace"></i></div>
            <div>
                <div class="stat-value">{{ $stats['professors'] }}</div>
                <div class="stat-label">Professors</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-collection-play-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['courses'] }}</div>
                <div class="stat-label">Total Courses</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon cyan"><i class="bi bi-journal-check"></i></div>
            <div>
                <div class="stat-value">{{ $stats['enrollments'] }}</div>
                <div class="stat-label">Enrollments</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- Recent Users --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-plus me-2 text-primary"></i>Recent Users</span>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Role</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $user->avatar_url }}" alt="" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                                    <span class="fw-600" style="font-weight:600;font-size:14px;">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{!! $user->role_badge !!}</td>
                            <td style="font-size:13px;color:#64748b;">{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-muted">No users yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Courses --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-collection-play me-2 text-primary"></i>Recent Courses</span>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Course</th>
                            <th>Professor</th>
                            <th>Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCourses as $course)
                        <tr>
                            <td class="ps-4" style="font-size:14px;font-weight:600;max-width:180px;">
                                <div class="text-truncate">{{ $course->title }}</div>
                            </td>
                            <td style="font-size:13px;color:#64748b;">{{ $course->professor->name }}</td>
                            <td>{!! $course->level_badge !!}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-muted">No courses yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
