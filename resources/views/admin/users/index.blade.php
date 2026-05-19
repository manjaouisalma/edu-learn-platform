@extends('layouts.app')

@section('title', 'All Users')
@section('page-title', 'All Users')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">Management</span>
    <a href="{{ route('admin.professors.index') }}"><i class="bi bi-person-workspace"></i> Professors</a>
    <a href="{{ route('admin.courses.index') }}"><i class="bi bi-collection-play-fill"></i> Courses</a>
    <a href="{{ route('admin.users.index') }}" class="active"><i class="bi bi-people-fill"></i> All Users</a>
@endsection

@section('content')
<!--div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">All Users <span class="badge bg-light text-dark ms-1">{{ $users->total() }}</span></h5>
</div-->

{{-- Filters --}}
<form method="GET" class="mb-4">
    <div class="row g-2">
        <div class="col-auto">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0"
                       placeholder="Search name or email..." value="{{ request('search') }}" style="min-width:220px;">
            </div>
        </div>
        <div class="col-auto">
            <select name="role" class="form-select">
                <option value="">All Roles</option>
                <!--option value="admin"     {{ request('role') === 'admin'     ? 'selected' : '' }}>Admin</option-->
                <option value="professor" {{ request('role') === 'professor' ? 'selected' : '' }}>Professor</option>
                <option value="student"   {{ request('role') === 'student'   ? 'selected' : '' }}>Student</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
            @if(request()->hasAny(['search','role']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary ms-1">Clear</a>
            @endif
        </div>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $user->avatar_url }}" alt=""
                                 style="width:34px;height:34px;border-radius:50%;object-fit:cover;">
                            <span style="font-weight:600;font-size:14px;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:13px;color:#64748b;">{{ $user->email }}</td>
                    <td>{!! $user->role_badge !!}</td>
                    <td style="font-size:13px;color:#64748b;">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="text-end pe-4">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white border-top px-4 py-3">
       {{ $users->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
