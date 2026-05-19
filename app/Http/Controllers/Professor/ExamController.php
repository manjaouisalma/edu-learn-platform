<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index(Course $course)
    {
        $exam = $course->exam()->with('questions')->first();
        return view('professor.exams.index', compact('course', 'exam'));
    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'pass_score'       => 'required|integer|min:1|max:100',
            'duration_minutes' => 'nullable|integer|min:1',
            'questions'        => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options'  => 'required|array|size:4',

            'questions.*.correct' => 'required|integer|min:0|max:3',
        ]);

        $exam = Exam::create([
            'course_id'        => $course->id,
            'title'            => $validated['title'],
            'pass_score'       => $validated['pass_score'],
            'duration_minutes' => $validated['duration_minutes'],
        ]);


            foreach ($validated['questions'] as $q) {
                Question::create([
                    'exam_id'  => $exam->id,
                    'question' => $q['question'],
                    'options'  => $q['options'],
                    'correct'  => (int) $q['correct'],
                ]);
            }

        return back()->with('success', 'Exam created successfully!');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return back()->with('success', 'Exam deleted.');
    }
}
