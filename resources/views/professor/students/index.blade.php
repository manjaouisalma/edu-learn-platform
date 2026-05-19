{{-- resources/views/professor/students/index.blade.php --}}

@extends('layouts.app')

@section('title', 'My Students')
@section('page-title', 'My Students')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('professor.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">My Teaching</span>
    <a href="{{ route('professor.courses.index') }}"><i class="bi bi-collection-play-fill"></i> My Courses</a>
    <a href="{{ route('professor.courses.create') }}"><i class="bi bi-plus-circle"></i> New Course</a>
    <a href="{{ route('professor.students.index') }}" class="active"><i class="bi bi-people-fill"></i> My Students</a>
@endsection

@section('content')

{{-- Header + Search + Filter --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <form method="GET" class="d-flex gap-2">
        <div class="input-group" style="max-width:380px;">
            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
            <input type="text" name="search" class="form-control border-start-0"
                   placeholder="Search students..." value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('professor.students.index') }}" class="btn btn-outline-secondary">Clear</a>
            @endif
        </div>

        <select name="filter" class="form-select" style="max-width:160px;" onchange="this.form.submit()">
            <option value="">All Students</option>
            <option value="active"   {{ request('filter') === 'active'   ? 'selected' : '' }}>Active</option>
            <option value="blocked"  {{ request('filter') === 'blocked'  ? 'selected' : '' }}>Blocked</option>
        </select>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-3" style="border-radius:14px;border:1px solid #e2e8f0;">
            <div class="d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;background:#eff6ff;border-radius:12px;
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-people-fill" style="font-size:22px;color:#3b82f6;"></i>
                </div>
                <div>
                    <div style="font-size:26px;font-weight:700;color:#0f172a;line-height:1;">
                        {{ $stats['total'] }}
                    </div>
                    <div style="font-size:13px;color:#64748b;margin-top:2px;">Total Students</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3" style="border-radius:14px;border:1px solid #e2e8f0;">
            <div class="d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;background:#f0fdf4;border-radius:12px;
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-person-check-fill" style="font-size:22px;color:#22c55e;"></i>
                </div>
                <div>
                    <div style="font-size:26px;font-weight:700;color:#0f172a;line-height:1;">
                        {{ $stats['active'] }}
                    </div>
                    <div style="font-size:13px;color:#64748b;margin-top:2px;">Active Students</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3" style="border-radius:14px;border:1px solid #e2e8f0;">
            <div class="d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;background:#fef2f2;border-radius:12px;
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-person-slash" style="font-size:22px;color:#ef4444;"></i>
                </div>
                <div>
                    <div style="font-size:26px;font-weight:700;color:#0f172a;line-height:1;">
                        {{ $stats['blocked'] }}
                    </div>
                    <div style="font-size:13px;color:#64748b;margin-top:2px;">Blocked Students</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Student</th>
                    <th>Date of birth</th>
                    <th>Course</th>
                    <th>Enrolled At</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                <tr>
                    <td class="ps-4">
                        <div style="font-weight:600;font-size:14px;">
                            {{ $enrollment->student->name }}
                        </div>
                        <div style="font-size:12px;color:#94a3b8;">
                            {{ $enrollment->student->email }}
                        </div>
                    </td>
                    <td style="font-size:13px;color:#64748b;">
                        @if($enrollment->student->birth_date)
                            {{ \Carbon\Carbon::parse($enrollment->student->birth_date)->format('d/m/Y')}} 
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td style="font-size:13px;color:#64748b;">
                        {{ $enrollment->course->title }}
                    </td>

                    <td style="font-size:13px;color:#64748b;">
                        {{ $enrollment->created_at->format('d/m/Y') }}
                    </td>

                    <td>
                        @if($enrollment->is_blocked)
                            <span class="badge badge-danger">Blocked</span>
                        @else
                            <span class="badge badge-success">Active</span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-1">

                            <form method="POST" 
                                  action="{{ route('professor.students.toggleBlock', $enrollment) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $enrollment->is_blocked ? 'btn-outline-success' : 'btn-outline-warning' }}"
                                        title="{{ $enrollment->is_blocked ? 'Unblock' : 'Block' }}">
                                    <i class="bi bi-{{ $enrollment->is_blocked ? 'unlock' : 'lock' }}"></i>
                                </button>
                            </form>
                            <form id="delete-enrollment-{{ $enrollment->id }}" 
                                  method="POST"
                                  action="{{ route('professor.students.destroy', $enrollment) }}" 
                                  class="d-none">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger"
                                    title="Remove"
                                    onclick="openDeleteModal({{ $enrollment->id }}, '{{ addslashes($enrollment->student->name) }}')">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-people" style="font-size:36px;display:block;margin-bottom:8px;"></i>
                        No students found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content" style="border:1px solid #e2e8f0;border-radius:16px;">
            <div class="modal-body p-4">
                <h5 style="font-weight:700;font-size:17px;color:#0f172a;margin-bottom:20px;">
                    <i class="bi bi-person-plus me-2"></i> Add New Student
                </h5>
                <form method="POST" action="{{ route('professor.students.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control"
                               placeholder="Student name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control"
                               placeholder="student@email.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-calendar3 me-1 text-muted"></i> Date de Naissance
                        </label>
                        <input type="date" name="birth_date" class="form-control"
                               max="{{ now()->subYears(5)->format('Y-m-d') }}">
                        <div class="form-text text-muted">Optionnel</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Min 6 characters" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Course</label>
                        <select name="course_id" class="form-select" required>
                            <option value="">Select a course...</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="height:1px;background:#f1f5f9;margin-bottom:20px;"></div>
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" data-bs-dismiss="modal"
                                class="btn btn-outline-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Add Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content" style="border:1px solid #e2e8f0;border-radius:16px;">
            <div class="modal-body p-4">
                <h5 style="font-weight:700;font-size:17px;color:#0f172a;margin-bottom:20px;">
                    <i class="bi bi-pencil-square me-2"></i> Edit Student
                </h5>
                <form method="POST" id="edit-student-form" action="">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" id="edit-name"
                               class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="edit-email"
                               class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-calendar3 me-1 text-muted"></i> Date de Naissance
                        </label>
                        <input type="date" name="birth_date" id="edit-birth-date"
                               class="form-control"
                               max="{{ now()->subYears(5)->format('Y-m-d') }}">
                    </div>

                    <div style="height:1px;background:#f1f5f9;margin-bottom:20px;"></div>
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" data-bs-dismiss="modal"
                                class="btn btn-outline-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content" style="border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.12);">
            <div class="modal-body p-4 text-center">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;
                            display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <i class="bi bi-person-x-fill" style="font-size:26px;color:#ef4444;"></i>
                </div>
                <h5 style="font-weight:700;font-size:17px;color:#0f172a;margin-bottom:8px;">
                    Remove Student
                </h5>
                <p style="font-size:14px;color:#64748b;margin-bottom:6px;">
                    Are you sure you want to remove
                </p>
                <p style="font-size:15px;font-weight:700;color:#1e293b;margin-bottom:6px;" 
                   id="modal-student-name">—</p>
                <p style="font-size:13px;color:#94a3b8;margin-bottom:24px;">
                    from this course?
                </p>
                <div style="height:1px;background:#f1f5f9;margin-bottom:20px;"></div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" data-bs-dismiss="modal"
                            style="padding:9px 24px;border-radius:8px;border:1px solid #e2e8f0;
                                   background:#fff;font-size:14px;font-weight:600;color:#475569;cursor:pointer;">
                        Cancel
                    </button>
                    <button type="button" id="confirm-delete-btn"
                            style="padding:9px 24px;border-radius:8px;border:none;
                                   background:#ef4444;font-size:14px;font-weight:600;color:#fff;cursor:pointer;">
                        <i class="bi bi-trash me-1"></i> Remove
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>

    let targetFormId = null;

    function openDeleteModal(enrollmentId, studentName) {
        targetFormId = 'delete-enrollment-' + enrollmentId;
        document.getElementById('modal-student-name').textContent = studentName;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    document.getElementById('confirm-delete-btn').addEventListener('click', function () {
        if (targetFormId) document.getElementById(targetFormId).submit();
    });

    function openEditModal(studentId, studentName, studentEmail, birthDate) {
    document.getElementById('edit-name').value        = studentName;
    document.getElementById('edit-email').value       = studentEmail;
    document.getElementById('edit-birth-date').value  = birthDate ?? ''; // ✅
    document.getElementById('edit-student-form').action = '/professor/students/' + studentId;
    new bootstrap.Modal(document.getElementById('editStudentModal')).show();
}
</script>
@endpush

@endsection