@extends('layouts.app')

@section('title', 'Add Professor')
@section('page-title', 'Add New Professor')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">Management</span>
    <a href="{{ route('admin.professors.index') }}" class="active"><i class="bi bi-person-workspace"></i> Professors</a>
    <a href="{{ route('admin.courses.index') }}"><i class="bi bi-collection-play-fill"></i> Courses</a>
    <a href="{{ route('admin.users.index') }}"><i class="bi bi-people-fill"></i> All Users</a>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-plus me-2 text-primary"></i> Professor Information
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.professors.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Dr. John Smith" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="professor@example.com" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <!--div class="col-sm-6">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 characters" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div-->
                        <div class="col-sm-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone') }}" placeholder="+1-555-0000">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="3"
                                      placeholder="Brief biography or academic background...">{{ old('bio') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Create Professor
                        </button>
                        <a href="{{ route('admin.professors.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
