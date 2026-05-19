@extends('layouts.app')

@section('title', 'Browse Courses')
@section('page-title', 'Browse Courses')

@section('sidebar-nav')
    <span class="nav-section-label">Learning</span>
    <a href="{{ route('student.courses.index') }}" class="active"><i class="bi bi-collection-play-fill"></i> Browse Courses</a>
    <a href="{{ route('student.my-courses') }}"><i class="bi bi-journal-check"></i> My Courses</a>
    <span class="nav-section-label">Account</span>
    <a href="{{ route('student.profile') }}"><i class="bi bi-person-circle"></i> Profile</a>
@endsection

@section('content')

{{-- Search & Filters --}}
<form method="GET" class="mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0"
                       placeholder="Search courses..."
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-auto">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="level" class="form-select">
                <option value="">All Levels</option>
                @foreach(['beginner','intermediate','advanced'] as $lvl)
                    <option value="{{ $lvl }}" {{ request('level') === $lvl ? 'selected' : '' }}>{{ ucfirst($lvl) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request()->hasAny(['search','category','level']))
                <a href="{{ route('student.courses.index') }}" class="btn btn-outline-secondary ms-1">Clear</a>
            @endif
        </div>
    </div>
</form>


<div class="mb-3 text-muted" style="font-size:14px;">
    {{ $courses->total() }} {{ Str::plural('course', $courses->total()) }} found
    @if(request('search'))
        for "<strong>{{ request('search') }}</strong>"
    @endif
</div>

<div class="row g-4">
    @forelse($courses as $course)
    <div class="col-sm-6 col-lg-4">
        <div class="course-card" style="border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);border:1px solid #f1f5f9;background:white;transition:transform 0.2s,box-shadow 0.2s;"
             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 25px rgba(0,0,0,0.12)'"
             onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 12px rgba(0,0,0,0.08)'">

            {{-- Image or Gradient Fallback --}}
            @php
                $gradients = [
                    'linear-gradient(135deg,#6366f1,#06b6d4)',
                    'linear-gradient(135deg,#f59e0b,#ef4444)',
                    'linear-gradient(135deg,#10b981,#06b6d4)',
                    'linear-gradient(135deg,#8b5cf6,#ec4899)',
                    'linear-gradient(135deg,#f97316,#eab308)',
                    'linear-gradient(135deg,#06b6d4,#6366f1)',
                ];
                $icons = [
                    'bi-laptop-fill',
                    'bi-calculator-fill',
                    'bi-graph-up-arrow',
                    'bi-book-fill',
                    'bi-code-slash',
                    'bi-palette-fill'
                ];
                $idx = $course->id % count($gradients);
                $gradient = $gradients[$idx];
                $icon = $icons[$idx];
            @endphp

            <div style="height:180px;position:relative;overflow:hidden;">
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}"
                         alt="{{ $course->title }}"
                         style="width:100%;height:100%;object-fit:cover;">
                 
                    <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,0.1),rgba(0,0,0,0.4));"></div>
                @else
                    <div style="width:100%;height:100%;background:{{ $gradient }};display:flex;align-items:center;justify-content:center;">
                        <i class="bi {{ $icon }}" style="font-size:52px;color:rgba(255,255,255,0.85);"></i>
                    </div>
                @endif
                <div style="position:absolute;top:12px;right:12px;">
                    {!! $course->level_badge !!}
                </div>

                
                <div style="position:absolute;bottom:12px;left:12px;">
                    <span style="background:rgba(255,255,255,0.2);backdrop-filter:blur(8px);color:white;font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:.5px;border:1px solid rgba(255,255,255,0.3);">
                        {{ $course->category }}
                    </span>
                </div>
            </div>
            <div style="padding:16px;">
                <div class="course-card-title" style="font-weight:700;font-size:15px;color:#0f172a;margin-bottom:6px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    {{ $course->title }}
                </div>
                <div style="font-size:13px;color:#64748b;margin-bottom:12px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    {{ $course->description }}
                </div>

                <div class="d-flex align-items-center gap-2 mb-3" style="font-size:12px;color:#64748b;">
                    <img src="{{ $course->professor->avatar_url }}" alt=""
                         style="width:22px;height:22px;border-radius:50%;object-fit:cover;">
                    <span style="font-weight:500;color:#475569;">{{ $course->professor->name }}</span>
                    <span>·</span>
                    <i class="bi bi-clock"></i> {{ $course->duration_hours }}h
                    <span>·</span>
                    <i class="bi bi-people"></i> {{ $course->enrollments_count }}
                </div>

                <div style="height:1px;background:#f1f5f9;margin-bottom:12px;"></div>

                <div class="d-flex align-items-center justify-content-between">
                    <div style="font-size:16px;font-weight:700;color:#0f172a;">
                        @if($course->is_free)
                            <span style="color:#10b981;">
                                <i class="bi bi-tag-fill me-1" style="font-size:13px;"></i>Free
                            </span>
                        @else
                            ${{ number_format($course->price, 2) }}
                        @endif
                    </div>
                    <a href="{{ route('student.courses.show', $course) }}"
                       class="btn btn-sm px-3"
                       style="background:linear-gradient(135deg,#6366f1,#06b6d4);color:white;border:none;border-radius:8px;font-size:13px;font-weight:600;">
                        View Course
                    </a>
                </div>
            </div>

        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted">
            <i class="bi bi-search" style="font-size:48px;display:block;margin-bottom:16px;opacity:.3;"></i>
            <h5>No courses found</h5>
            <p>Try adjusting your search or filters.</p>
        </div>
    </div>
    @endforelse
</div>

@if($courses->hasPages())
<div class="mt-4 d-flex justify-content-center">
    {{ $courses->links() }}
</div>
@endif

@endsection