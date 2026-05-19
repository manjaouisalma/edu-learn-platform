@extends('layouts.app')

@section('title', 'Professors')
@section('page-title', 'Manage Professors')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">Management</span>
    <a href="{{ route('admin.professors.index') }}" class="active"><i class="bi bi-person-workspace"></i> Professors</a>
    <a href="{{ route('admin.courses.index') }}"><i class="bi bi-collection-play-fill"></i> Courses</a>
    <a href="{{ route('admin.users.index') }}"><i class="bi bi-people-fill"></i> All Users</a>
@endsection

@section('content')
<div class="d-flex justify-content-end align-items-center mb-4">
    <a href="{{ route('admin.professors.create') }}"
       class="btn btn-primary d-flex align-items-center gap-2"
       style="font-weight:600;font-size:14px;padding:9px 20px;border-radius:8px;">
        <i class="bi bi-person-plus-fill"></i>
        Add Professor
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Professor</th>
                    <th>Email</th>
                    <th>Courses</th>
                    <th>Joined</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($professors as $professor)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $professor->avatar_url }}" alt=""
                                 style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                            <div>
                                <div style="font-weight:600;font-size:14px;">{{ $professor->name }}</div>
                                @if($professor->bio)
                                <div style="font-size:12px;color:#94a3b8;max-width:200px;" class="text-truncate">
                                    {{ $professor->bio }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:#64748b;">{{ $professor->email }}</td>
                    <td>
                        <span class="badge badge-info">{{ $professor->taught_courses_count }} courses</span>
                    </td>
                    <td style="font-size:13px;color:#64748b;">{{ $professor->created_at->format('M d, Y') }}</td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <!--a href="{{ route('admin.professors.edit', $professor) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a-->
                            {{-- Hidden form for delete --}}
                            <form id="delete-form-{{ $professor->id }}"
                                  method="POST"
                                  action="{{ route('admin.professors.destroy', $professor) }}"
                                  class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="openDeleteModal({{ $professor->id }}, '{{ addslashes($professor->name) }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-person-x" style="font-size:36px;display:block;margin-bottom:8px;"></i>
                        No professors yet. <a href="{{ route('admin.professors.create') }}">Add the first one</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($professors->hasPages())
    <div class="card-footer bg-white border-top px-4 py-3">
        {{ $professors->links() }}
    </div>
    @endif
</div>

{{-- ── Delete Confirmation Modal ── --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content" style="border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.12);">
            <div class="modal-body p-4 text-center">

                {{-- Icon --}}
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;
                            display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <i class="bi bi-person-x-fill" style="font-size:26px;color:#ef4444;"></i>
                </div>

                {{-- Title --}}
                <h5 style="font-weight:700;font-size:17px;color:#0f172a;margin-bottom:8px;">
                    Delete Professor
                </h5>

                {{-- Message --}}
                <p style="font-size:14px;color:#64748b;margin-bottom:6px;">
                    Are you sure you want to delete
                </p>
                <p style="font-size:15px;font-weight:700;color:#1e293b;margin-bottom:6px;" id="modal-professor-name">
                    —
                </p>
                <!--p style="font-size:13px;color:#94a3b8;margin-bottom:24px;">
                    This will permanently remove the professor and all their courses.
                </p-->

                {{-- Divider --}}
                <div style="height:1px;background:#f1f5f9;margin-bottom:20px;"></div>

                {{-- Buttons --}}
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button"
                            data-bs-dismiss="modal"
                            style="padding:9px 24px;border-radius:8px;border:1px solid #e2e8f0;
                                   background:#fff;font-size:14px;font-weight:600;color:#475569;cursor:pointer;">
                        Cancel
                    </button>
                    <button type="button"
                            id="confirm-delete-btn"
                            style="padding:9px 24px;border-radius:8px;border:none;
                                   background:#ef4444;font-size:14px;font-weight:600;color:#fff;cursor:pointer;">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let targetFormId = null;

    function openDeleteModal(professorId, professorName) {
        targetFormId = 'delete-form-' + professorId;
        document.getElementById('modal-professor-name').textContent = professorName;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    document.getElementById('confirm-delete-btn').addEventListener('click', function () {
        if (targetFormId) {
            document.getElementById(targetFormId).submit();
        }
    });
</script>
@endpush