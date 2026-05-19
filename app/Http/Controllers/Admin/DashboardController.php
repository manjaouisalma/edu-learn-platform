<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'students'    => User::where('role', 'student')->count(),
            'professors'  => User::where('role', 'professor')->count(),
            'courses'     => Course::count(),
            'enrollments' => Enrollment::count(),
        ];

        $recentUsers    = User::latest()->take(5)->get();
        $recentCourses  = Course::with('professor')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentCourses'));
    }
}
