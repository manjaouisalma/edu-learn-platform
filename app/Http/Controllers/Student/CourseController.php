<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /** Browse all available/published courses */
    public function index(Request $request)
    {
        $query = Course::with('professor')
                       ->withCount('enrollments')
                       ->published();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $courses    = $query->latest()->paginate(12)->withQueryString();
        $categories = Course::published()->distinct()->pluck('category')->sort();

        return view('student.courses.index', compact('courses', 'categories'));
    }

    /** Show a single course detail */
    public function show(Course $course)
    {
        $course->load('professor');

        $isEnrolled = Auth::user()->enrollments()
                         ->where('course_id', $course->id)
                         ->exists();

        return view('student.courses.show', compact('course', 'isEnrolled'));
    }

    /** Enroll student in a course */
    public function enroll(Course $course)
    {
        $student = Auth::user();

        // Check if already enrolled
        if ($student->enrollments()->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        // Check max students limit
        if ($course->max_students && $course->enrollments()->count() >= $course->max_students) {
            return back()->with('error', 'This course has reached its maximum capacity.');
        }

        Enrollment::create([
            'student_id'  => $student->id,
            'course_id'   => $course->id,
            'status'      => 'active',
            'enrolled_at' => now(),
        ]);

        return redirect()->route('student.my-courses')
            ->with('success', "You've successfully enrolled in \"{$course->title}\"!");
    }

    /** Drop/unenroll from a course */
    public function unenroll(Course $course)
    {
        $enrollment = Auth::user()->enrollments()
                         ->where('course_id', $course->id)
                         ->firstOrFail();

        $enrollment->delete();

        return redirect()->route('student.my-courses')
            ->with('success', "You have been unenrolled from \"{$course->title}\".");
    }

    /** Student's enrolled courses */
    public function myCourses()
    {
        $enrollments = Auth::user()
                          ->enrollments()
                          ->with(['course.professor', 'grade'])
                          ->latest()
                          ->paginate(10);

        return view('student.courses.my-courses', compact('enrollments'));
    }

    /** Student's grades across all courses */
    public function grades()
    {
        $grades = Auth::user()
                     ->grades()
                     ->with('course.professor')
                     ->latest()
                     ->paginate(15);

        return view('student.grades', compact('grades'));
    }

    /** Student profile */
    public function profile()
    {
        $student = Auth::user();
        return view('student.profile', compact('student'));
    }

    /** Update student profile */
    public function updateProfile(Request $request)
    {
        $student = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $student->id],
            'bio'   => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $student->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
