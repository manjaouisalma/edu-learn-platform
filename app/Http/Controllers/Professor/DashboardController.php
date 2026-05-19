<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $professor = Auth::user();

        $courses = $professor->taughtCourses()->withCount('enrollments')->latest()->get();

        $stats = [
            'courses'     => $courses->count(),
            'students'    => Enrollment::whereHas('course', fn($q) => $q->where('professor_id', $professor->id))->count(),
            'graded'      => Grade::where('professor_id', $professor->id)->count(),
        ];

        return view('professor.dashboard', compact('professor', 'courses', 'stats'));
    }
}
