@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('sidebar-nav')
    <span class="nav-section-label">Learning</span>
    <a href="{{ route('student.courses.index') }}"><i class="bi bi-collection-play-fill"></i> Browse Courses</a>
    <a href="{{ route('student.my-courses') }}"><i class="bi bi-journal-check"></i> My Courses</a>
    <span class="nav-section-label">Account</span>
    <a href="{{ route('student.profile') }}" class="active"><i class="bi bi-person-circle"></i> Profile</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card mb-4 text-center">
            <div class="card-body py-4">
                <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}"
                     style="width:88px;height:88px;border-radius:50%;object-fit:cover;margin-bottom:12px;border:4px solid #eef2ff;">
                <h5 class="fw-bold mb-1">{{ $student->name }}</h5>
                <span class="badge badge-student">Student</span>
                @if($student->bio)
                <p class="text-muted mt-2" style="font-size:14px;">{{ $student->bio }}</p>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2 text-primary"></i> Edit Profile
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('student.profile.update') }}">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $student->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $student->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone"
                                   class="form-control"
                                   value="{{ old('phone', $student->phone) }}"
                                   placeholder="+1-555-0000">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="3"
                                      placeholder="Tell us a bit about yourself...">{{ old('bio', $student->bio) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
