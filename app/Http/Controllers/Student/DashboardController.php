<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user();

        $enrollments = $student->enrollments()
                               ->with('course.professor')
                               ->latest()
                               ->take(5)
                               ->get();

        $stats = [
            'enrolled'  => $student->enrollments()->count(),
            'completed' => $student->enrollments()->completed()->count(),
            'graded'    => $student->grades()->count(),
            'average'   => round($student->grades()->avg('score') ?? 0, 1),
        ];
        

        return view('student.dashboard', compact('student', 'enrollments', 'stats'));
    }

    public function courses(Request $request)
    {
        $query = Course::with('professor')->published()->withCount('enrollments');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $courses     = $query->latest()->paginate(12)->withQueryString();
        $categories  = Course::published()->distinct()->pluck('category');
        $enrolledIds = Auth::user()->enrollments()->pluck('course_id')->toArray();

        return view('student.courses.index', compact('courses', 'categories', 'enrolledIds'));
    }

    public function show(Course $course)
    {
        $course->load(['professor', 'exam']);
        $isEnrolled = Auth::user()->enrollments()
                        ->where('course_id', $course->id)
                        ->exists();

        return view('student.courses.show', compact('course', 'isEnrolled'));
    }

    

    public function enroll(Course $course)
    {
        $student = Auth::user();

        if ($student->enrollments()->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        if ($course->max_students && $course->enrollments()->count() >= $course->max_students) {
            return back()->with('error', 'This course is full. No more spots available.');
        }

        Enrollment::create([
            'student_id'  => $student->id,
            'course_id'   => $course->id,
            'status'      => 'active',
            'enrolled_at' => now(),
        ]);

        return back()->with('success', "You have successfully enrolled in \"{$course->title}\"!");
    }

    public function unenroll(Course $course)
    {
        $student    = Auth::user();
        $enrollment = $student->enrollments()->where('course_id', $course->id)->first();

        if (!$enrollment) {
            return back()->with('error', 'You are not enrolled in this course.');
        }

        $enrollment->delete();

        return redirect()->route('student.courses.index')
            ->with('success', "You have unenrolled from \"{$course->title}\".");
    }

    public function myCourses()
    {
        $enrollments = Auth::user()->enrollments()
                           ->with(['course.professor', 'grade'])
                           ->latest()
                           ->paginate(10);

        return view('student.courses.my-courses', compact('enrollments'));
    }

    public function grades()
    {
        $grades  = Auth::user()->grades()
                      ->with(['course.professor'])
                      ->latest()
                      ->paginate(15);
        $average = round(Auth::user()->grades()->avg('score') ?? 0, 1);

        return view('student.grades', compact('grades', 'average'));
    }

    public function profile()
    {
        $student = Auth::user();
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'bio'      => ['nullable', 'string', 'max:500'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'bio'   => $validated['bio'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }
}
