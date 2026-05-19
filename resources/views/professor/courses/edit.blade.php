@extends('layouts.app')

@section('title', 'Edit Course')
@section('page-title', 'Edit Course')

@section('sidebar-nav')
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('professor.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <span class="nav-section-label">My Teaching</span>
    <a href="{{ route('professor.courses.index') }}" class="active"><i class="bi bi-collection-play-fill"></i> My Courses</a>
    <a href="{{ route('professor.courses.create') }}"><i class="bi bi-plus-circle"></i> New Course</a>
    <a href="{{ route('professor.students.index') }}"><i class="bi bi-people-fill"></i> My Students</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2 text-primary"></i> Editing: <strong>{{ $course->title }}</strong>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('professor.courses.update', $course) }}">
                    @csrf @method('PUT')
                    @include('professor.courses._form')
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                        <a href="{{ route('professor.courses.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
