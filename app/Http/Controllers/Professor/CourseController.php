<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Certificate;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $professor = Auth::user();

        //$query = $professor->courses()->withCount('enrollments');
        $query = $professor->taughtCourses()->withCount('enrollments');


        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $courses = $query->latest()->paginate(10)->withQueryString();

        return view('professor.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('professor.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['required', 'string'],
            'category'       => ['required', 'string', 'max:100'],
            'level'          => ['required', 'in:beginner,intermediate,advanced'],
            'duration_hours' => ['required', 'integer', 'min:1'],
            'price'          => ['required', 'numeric', 'min:0'],
            'is_published'   => ['boolean'],
            'start_date'     => ['nullable', 'date'],           // ← زيد
            'end_date'       => ['nullable', 'date', 'after_or_equal:start_date'],
            'max_students'   => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['professor_id'] = Auth::id();
        $validated['is_published'] = $request->boolean('is_published');

        Course::create($validated);

        return redirect()->route('professor.courses.index')
            ->with('success', 'Course created successfully!');
    }

    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        return view('professor.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['required', 'string'],
            'category'       => ['required', 'string', 'max:100'],
            'level'          => ['required', 'in:beginner,intermediate,advanced'],
            'duration_hours' => ['required', 'integer', 'min:1'],
            'price'          => ['required', 'numeric', 'min:0'],
            'is_published'   => ['boolean'],
            'start_date'     => ['nullable', 'date'],           // ← زيد
            'end_date'       => ['nullable', 'date', 'after_or_equal:start_date'],
            'max_students'   => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        $course->update($validated);

        return redirect()->route('professor.courses.index')
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        $course->delete();

        return redirect()->route('professor.courses.index')
            ->with('success', 'Course deleted.');
    }

    // ─── Students Management ─────────────────────────────────────────────────

    public function students(Course $course)
{
    $enrollments = $course->enrollments()
        ->with(['student', 'grade'])
        ->latest()
        ->paginate(15);

    $certificateStudentIds = Certificate::where('course_id', $course->id)
        ->pluck('student_id')
        ->flip()
        ->toArray();

    return view('professor.courses.students', compact('course', 'enrollments', 'certificateStudentIds'));
}

    // ─── Grades Management ───────────────────────────────────────────────────

    public function gradeForm(Course $course, Enrollment $enrollment)
    {
        $this->authorize('gradeStudents', $course);

        $enrollment->load('student', 'grade');

        return view('professor.courses.grade', compact('course', 'enrollment'));
    }

    public function saveGrade(Request $request, Course $course, Enrollment $enrollment)
    {
        $this->authorize('gradeStudents', $course);

        $validated = $request->validate([
            'score'    => ['required', 'numeric', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        Grade::updateOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'student_id'    => $enrollment->student_id,
                'course_id'     => $course->id,
            ],
            [
                'professor_id' => Auth::id(),
                'score'        => $validated['score'],
                'feedback'     => $validated['feedback'] ?? null,
            ]
        );


        if ($validated['score'] >= 60) {
            $enrollment->update([
                'status'       => 'completed',
                'completed_at' => now(),
            ]);
        }

        return redirect()->route('professor.courses.students', $course)
            ->with('success', 'Grade saved for ' . $enrollment->student->name . '.');
    }







    public function issueCertificate(Course $course, Enrollment $enrollment)
{
    $exists = Certificate::where('course_id', $course->id)
                         ->where('student_id', $enrollment->student_id)
                         ->exists();

    if ($exists) {
        return back()->with('error', 'Certificate already issued for this student.');
    }

    Certificate::create([
        'student_id'         => $enrollment->student_id,
        'course_id'          => $course->id,
        'exam_attempt_id'    => null,
        'score'              => $enrollment->grade?->score ?? 0,
        'certificate_number' => 'CERT-' . strtoupper(Str::random(10)),
        'issued_at'          => now(),
    ]);

    return back()->with('success', 'Certificate issued successfully.');
}
}
