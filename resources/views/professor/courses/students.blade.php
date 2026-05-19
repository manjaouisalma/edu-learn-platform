@extends('layouts.app')

@section('title', 'Students')
@section('page-title', 'Course Students')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('professor.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">My Teaching</span>
    <a href="{{ route('professor.courses.index') }}" class="active"><i class="bi bi-collection-play-fill"></i> My Courses</a>
    <a href="{{ route('professor.courses.create') }}"><i class="bi bi-plus-circle"></i> New Course</a>
    <a href="{{ route('professor.students.index') }}" class="active"><i class="bi bi-people-fill"></i> My Students</a>
@endsection

@section('content')

<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="{{ route('professor.courses.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to My Courses
    </a>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
        <i class="bi bi-person-plus me-1"></i> Add Student
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card mb-4" style="border-left:4px solid #4f46e5;">
    <div class="card-body py-3 d-flex align-items-center gap-3">
        <div style="width:48px;height:48px;background:linear-gradient(135deg,#4f46e5,#06b6d4);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;">
            <i class="bi bi-collection-play"></i>
        </div>
        <div>
            <div style="font-weight:700;font-size:16px;">{{ $course->title }}</div>
            <div style="font-size:13px;color:#64748b;">
                {{ $course->category }} · {{ ucfirst($course->level) }} · {{ $enrollments->total() }} students enrolled
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-people me-2 text-primary"></i> Enrolled Students
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Student</th>
                    <th>Enrolled</th>
                    <th>Status</th>
                    <th>Grade</th>
                    <th>Certificate</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $enrollment->student->avatar_url }}" alt=""
                                 style="width:34px;height:34px;border-radius:50%;object-fit:cover;">
                            <div>
                                <div style="font-weight:600;font-size:14px;">{{ $enrollment->student->name }}</div>
                                <div style="font-size:12px;color:#94a3b8;">{{ $enrollment->student->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:#64748b;">
                        {{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('M d, Y') }}
                    </td>
                    <td>
                        @php
                            $statusColors = ['active'=>'badge-info','completed'=>'badge-success','dropped'=>'badge-danger'];
                        @endphp
                        <span class="badge {{ $statusColors[$enrollment->status] ?? 'badge-secondary' }}">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </td>
                    <td>
                        @if($enrollment->grade)
                            <span class="badge badge-{{ $enrollment->grade->grade_color }}">
                                {{ $enrollment->grade->letter_grade }}
                                ({{ number_format($enrollment->grade->score, 1) }})
                            </span>
                        @else
                            <span style="font-size:12px;color:#94a3b8;">Not graded</span>
                        @endif
                    </td>
                    <td>
                        @if(isset($certificateStudentIds[$enrollment->student_id]))
                            <span class="badge badge-success">
                                <i class="bi bi-patch-check-fill me-1"></i> Issued
                            </span>
                        @else
                            <form method="POST" action="{{ route('professor.courses.certificate.issue', [$course, $enrollment]) }}"
                                  onsubmit="return confirm('Issue certificate for {{ $enrollment->student->name }}?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-award me-1"></i> Issue
                                </button>
                            </form>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <a href="{{ route('professor.courses.grade.form', [$course, $enrollment]) }}"
                            class="btn btn-sm btn-primary">
                            <i class="bi bi-award me-1"></i>
                            {{ $enrollment->grade ? 'Update Grade' : 'Grade' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-people" style="font-size:36px;display:block;margin-bottom:8px;"></i>
                        No students enrolled in this course yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($enrollments->hasPages())
    <div class="card-footer bg-white border-top px-4 py-3">
        {{ $enrollments->links() }}
    </div>
    @endif
</div>

{{-- Modal Add Student --}}
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus me-2 text-primary"></i>Add Student to Course
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('professor.students.store') }}">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Ex: Mohammed Alaoui" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="student@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 6 characters" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Birth Date</label>
                        <input type="date" name="birth_date" class="form-control">
                    </div>
                    <div class="alert alert-info py-2 px-3 mb-0" style="font-size:13px;">
                        <i class="bi bi-info-circle me-1"></i>
                        A new account will be created and enrolled in <strong>{{ $course->title }}</strong>.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-check me-1"></i> Create & Enroll
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection