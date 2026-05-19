@extends('layouts.app')

@section('title', 'My Courses')
@section('page-title', 'My Courses')

@section('sidebar-nav')
    <span class="nav-section-label">Learning</span>
    <a href="{{ route('student.courses.index') }}"><i class="bi bi-collection-play-fill"></i> Browse Courses</a>
    <a href="{{ route('student.my-courses') }}" class="active"><i class="bi bi-journal-check"></i> My Courses</a>
    <span class="nav-section-label">Account</span>
    <a href="{{ route('student.profile') }}"><i class="bi bi-person-circle"></i> Profile</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">My Enrolled Courses</h5>
    <a href="{{ route('student.courses.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-search me-1"></i> Browse More Courses
    </a>
</div>

@if($enrollments->isEmpty())
<div class="text-center py-5 text-muted">
    <i class="bi bi-mortarboard" style="font-size:48px;display:block;margin-bottom:16px;opacity:.3;"></i>
    <h5>No courses yet</h5>
    <p>Start your learning journey today!</p>
    <a href="{{ route('student.courses.index') }}" class="btn btn-primary">Browse Courses</a>
</div>
@else
<div class="row g-4">
    @foreach($enrollments as $enrollment)
    @php $course = $enrollment->course; @endphp

    <div class="col-md-6 col-xl-4">
        <div style="border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);border:1px solid #f1f5f9;background:white;transition:transform 0.2s,box-shadow 0.2s;"
             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 25px rgba(0,0,0,0.12)'"
             onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 12px rgba(0,0,0,0.08)'">

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
            @endphp

            <div style="height:140px;position:relative;overflow:hidden;">
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}"
                         alt="{{ $course->title }}"
                         style="width:100%;height:100%;object-fit:cover;display:block;">
                    <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,0.1),rgba(0,0,0,0.5));"></div>
                @else
                    <div style="width:100%;height:100%;background:{{ $gradients[$idx] }};display:flex;align-items:center;justify-content:center;">
                        <i class="bi {{ $icons[$idx] }}" style="font-size:42px;color:rgba(255,255,255,0.85);"></i>
                    </div>
                @endif

                @php
                    $statusStyles = [
                        'active'    => 'background:#dcfce7;color:#16a34a;',
                        'completed' => 'background:#dbeafe;color:#1d4ed8;',
                        'dropped'   => 'background:#fee2e2;color:#dc2626;'
                    ];
                @endphp
                <div style="position:absolute;top:10px;right:10px;">
                    <span style="{{ $statusStyles[$enrollment->status] ?? 'background:#f1f5f9;color:#475569;' }}font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                </div>

                <div style="position:absolute;bottom:10px;left:10px;">
                    <span style="background:rgba(255,255,255,0.2);backdrop-filter:blur(8px);color:white;font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;border:1px solid rgba(255,255,255,0.3);">
                        {{ $course->category }}
                    </span>
                </div>
            </div>
            <div style="padding:16px;">

                <div style="font-weight:700;font-size:15px;color:#0f172a;margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    {{ $course->title }}
                </div>

                <div style="font-size:12px;color:#64748b;margin-bottom:12px;">
                    <i class="bi bi-person me-1"></i> {{ $course->professor->name }}
                    · <i class="bi bi-clock me-1"></i> {{ $course->duration_hours }}h
                </div>

                @if($enrollment->grade)
                <div style="background:#f8fafc;border-radius:8px;padding:10px;margin-bottom:12px;display:flex;align-items:center;gap:10px;">
                    <div style="
                        background:{{ ['A'=>'#d1fae5','B'=>'#dbeafe','C'=>'#fef3c7','D'=>'#f1f5f9','F'=>'#fee2e2'][$enrollment->grade->letter_grade] ?? '#f1f5f9' }};
                        color:{{ ['A'=>'#065f46','B'=>'#1e40af','C'=>'#92400e','D'=>'#475569','F'=>'#991b1b'][$enrollment->grade->letter_grade] ?? '#475569' }};
                        width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;flex-shrink:0;">
                        {{ $enrollment->grade->letter_grade }}
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:700;">{{ number_format($enrollment->grade->score, 1) }}/100</div>
                        <div style="font-size:11px;color:#94a3b8;">Grade received</div>
                    </div>
                </div>
                @endif

                <div style="height:1px;background:#f1f5f9;margin-bottom:12px;"></div>

                <div class="d-flex gap-2">
                    <a href="{{ route('student.courses.show', $course) }}"
                       class="btn btn-sm flex-grow-1"
                       style="background:linear-gradient(135deg,#6366f1,#06b6d4);color:white;border:none;border-radius:8px;font-size:13px;font-weight:600;">
                        <i class="bi bi-arrow-right me-1"></i> View Course
                    </a>
                    @if($enrollment->status === 'active')
                    <button type="button"
                            onclick="openUnenrollModal({{ $course->id }}, '{{ addslashes($course->title) }}')"
                            class="btn btn-sm btn-outline-danger"
                            style="border-radius:8px;"
                            title="Unenroll">
                        <i class="bi bi-x-lg"></i>
                    </button>

                    <form id="unenroll-form-{{ $course->id }}"
                          method="POST"
                          action="{{ route('student.courses.unenroll', $course) }}"
                          style="display:none;">
                        @csrf @method('DELETE')
                    </form>
                    @endif
                </div>

            </div>
        </div>
    </div>

    @endforeach
</div>

@if($enrollments->hasPages())
<div class="mt-4 d-flex justify-content-center">
    {{ $enrollments->links() }}
</div>
@endif
@endif

<div id="unenroll-modal"
     style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);">
    <div style="background:white;border-radius:20px;padding:32px;max-width:420px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.3);animation:popIn 0.3s ease;">


        <div style="width:70px;height:70px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <i class="bi bi-exclamation-triangle-fill" style="font-size:30px;color:#ef4444;"></i>
        </div>
        <h5 style="font-weight:700;margin-bottom:8px;color:#0f172a;">Unenroll from Course?</h5>

        <p style="color:#64748b;font-size:14px;margin-bottom:8px;">
            You are about to unenroll from:
        </p>
        <p id="modal-course-name"
           style="font-weight:700;color:#6366f1;font-size:15px;margin-bottom:20px;padding:10px 16px;background:#f5f3ff;border-radius:10px;">
        </p>

        <p style="color:#94a3b8;font-size:13px;margin-bottom:24px;">
            <i class="bi bi-info-circle me-1"></i>
            Your progress may be lost. Are you sure?
        </p>
        <div class="d-flex gap-3 justify-content-center">
            <button onclick="closeUnenrollModal()"
                    style="padding:10px 28px;border-radius:10px;border:2px solid #e2e8f0;background:white;color:#475569;font-weight:600;font-size:14px;cursor:pointer;transition:all 0.2s;"
                    onmouseover="this.style.background='#f8fafc'"
                    onmouseout="this.style.background='white'">
                <i class="bi bi-arrow-left me-1"></i> Cancel
            </button>
            <button onclick="confirmUnenroll()"
                    style="padding:10px 28px;border-radius:10px;border:none;background:linear-gradient(135deg,#ef4444,#dc2626);color:white;font-weight:600;font-size:14px;cursor:pointer;transition:all 0.2s;"
                    onmouseover="this.style.opacity='0.9'"
                    onmouseout="this.style.opacity='1'">
                <i class="bi bi-x-circle me-1"></i> Yes, Unenroll
            </button>
        </div>

    </div>
</div>

<style>
@keyframes popIn {
    from { transform: scale(0.85); opacity: 0; }
    to   { transform: scale(1);    opacity: 1; }
}
</style>

<script>
let currentCourseId = null;

function openUnenrollModal(courseId, courseName) {
    currentCourseId = courseId;
    document.getElementById('modal-course-name').textContent = courseName;
    const modal = document.getElementById('unenroll-modal');
    modal.style.display = 'flex';
}

function closeUnenrollModal() {
    document.getElementById('unenroll-modal').style.display = 'none';
    currentCourseId = null;
}

function confirmUnenroll() {
    if (currentCourseId) {
        document.getElementById('unenroll-form-' + currentCourseId).submit();
    }
}


document.getElementById('unenroll-modal').addEventListener('click', function(e) {
    if (e.target === this) closeUnenrollModal();
});
</script>

@endsection