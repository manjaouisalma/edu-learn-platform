@extends('layouts.app')

@section('title', 'Courses')
@section('page-title', 'Manage Courses')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">Management</span>
    <a href="{{ route('admin.professors.index') }}"><i class="bi bi-person-workspace"></i> Professors</a>
    <a href="{{ route('admin.courses.index') }}" class="active"><i class="bi bi-collection-play-fill"></i> Courses</a>
    <a href="{{ route('admin.users.index') }}"><i class="bi bi-people-fill"></i> All Users</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    {{-- Search --}}
    <form method="GET">
        <div class="input-group" style="max-width:400px;">
            <span class="input-group-text" style="background:#fff;border-color:#e2e8f0;">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" name="search" class="form-control border-start-0"
                   placeholder="Search courses..." value="{{ request('search') }}"
                   style="border-color:#e2e8f0;box-shadow:none;">
            @if(request('search'))
            <a href="{{ route('admin.courses.index') }}" 
               class="btn btn-outline-secondary" style="border-color:#e2e8f0;">Clear</a>
            @endif
        </div>
    </form>

    {{-- Button --}}
    <a href="{{ route('admin.courses.create') }}" 
       class="btn btn-primary d-flex align-items-center gap-2"
       style="font-weight:600;font-size:14px;padding:9px 20px;border-radius:8px;">
        <i class="bi bi-plus-lg"></i>
        New Course
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Course</th>
                    <th>Professor</th>
                    <th>Category</th>
                    <th>Level</th>
                    <!--th>Start Date</th>
                    <th>End Date</th-->
                    <th>Students</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                <tr>
                    <td class="ps-4" style="max-width:220px;">
                        <div class="fw-bold text-truncate" style="font-size:14px;">{{ $course->title }}</div>
                        <div style="font-size:12px;color:#94a3b8;">
                            {{ $course->duration_hours }}h •
                            {{ $course->is_free ? 'Free' : '$' . number_format($course->price, 2) }}
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $course->professor->name }}</td>
                    <td style="font-size:13px;color:#64748b;">{{ $course->category }}</td>
                    <td>{!! $course->level_badge !!}</td>
                    <!--td style="font-size:13px;color:#64748b;">
                        {{ $course->start_date ? $course->start_date->format('d/m/Y') : '—' }}
                    </td>
                    <td style="font-size:13px;color:#64748b;">
                        {{ $course->end_date ? $course->end_date->format('d/m/Y') : '—' }}
                    </td-->
                    <td>
                        <span class="badge badge-info">{{ $course->enrollments_count }}</span>
                    </td>
                    <td>
                        @if($course->is_published)
                            <span class="badge badge-success">Published</span>
                        @else
                            <span class="badge badge-secondary">Draft</span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <!--form method="POST" action="{{ route('admin.courses.destroy', $course) }}"
                                  onsubmit="return confirm('Delete this course?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form-->
                            {{-- B3D (hada li tzido) --}}
                        <form id="delete-form-{{ $course->id }}"
                            method="POST"
                            action="{{ route('admin.courses.destroy', $course) }}"
                            class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            onclick="openDeleteModal({{ $course->id }}, '{{ addslashes($course->title) }}')">
                        <i class="bi bi-trash"></i>
                        </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-collection-play" style="font-size:36px;display:block;margin-bottom:8px;"></i>
                        No courses found. <a href="{{ route('admin.courses.create') }}">Create one</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($courses->hasPages())
    <div class="card-footer bg-white border-top px-4 py-3">
        {{ $courses->links() }}
    </div>
    @endif
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content" style="border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.12);">
            <div class="modal-body p-4 text-center">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;
                            display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <i class="bi bi-collection-play-fill" style="font-size:26px;color:#ef4444;"></i>
                </div>
                <h5 style="font-weight:700;font-size:17px;color:#0f172a;margin-bottom:8px;">Delete Course</h5>
                <p style="font-size:14px;color:#64748b;margin-bottom:6px;">Are you sure you want to delete</p>
                <p style="font-size:15px;font-weight:700;color:#1e293b;margin-bottom:6px;" id="modal-course-title">—</p>
                <!--p style="font-size:13px;color:#94a3b8;margin-bottom:24px;">
                    This will permanently remove the course and all its enrollments.
                </p-->
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
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    let targetFormId = null;

    function openDeleteModal(courseId, courseTitle) {
        targetFormId = 'delete-form-' + courseId;
        document.getElementById('modal-course-title').textContent = courseTitle;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    document.getElementById('confirm-delete-btn').addEventListener('click', function () {
        if (targetFormId) document.getElementById(targetFormId).submit();
    });
</script>
@endpush
@endsection
