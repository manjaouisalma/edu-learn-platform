<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ExamAttempt;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

class ExamController extends Controller
{
    public function show(Course $course)
    {
        $exam = $course->exam()->with('questions')->firstOrFail();

        $attempt = ExamAttempt::where('exam_id', $exam->id)
                              ->where('student_id', Auth::id())
                              ->latest()
                              ->first();

        return view('student.exams.show', compact('exam', 'course', 'attempt'));
    }

    public function submit(Request $request, Course $course)
    {
        $exam = $course->exam()->with('questions')->firstOrFail();

        $answers  = $request->input('answers', []);
        $correct  = 0;
        $total    = $exam->questions->count();

        foreach ($exam->questions as $question) {
            if (isset($answers[$question->id]) && $answers[$question->id] === $question->correct) {
                $correct++;
            }
        }

        $score  = $total > 0 ? round(($correct / $total) * 100, 2) : 0;
        $passed = $score >= $exam->pass_score;

        $attempt = ExamAttempt::create([
            'exam_id'      => $exam->id,
            'student_id'   => Auth::id(),
            'answers'      => $answers,
            'score'        => $score,
            'passed'       => $passed,
            'completed_at' => now(),
        ]);

        if ($passed) {
            Certificate::create([
                'student_id'       => Auth::id(),
                'course_id'        => $course->id,
                'exam_attempt_id'  => $attempt->id,
                'score'            => $score,
                'certificate_number' => 'CERT-' . strtoupper(Str::random(10)),
                'issued_at'        => now(),
            ]);
        }

        return redirect()->route('student.exams.result', ['course' => $course->id, 'attempt' => $attempt->id]);
    }

    
    public function result(Course $course, ExamAttempt $attempt)
    {
        $certificate = $attempt->certificate;
        return view('student.exams.result', compact('course', 'attempt', 'certificate'));
    }



public function downloadCertificate(Certificate $certificate)
{
    
    abort_if($certificate->student_id !== Auth::id(), 403);

   
    $certificate->load([
        'student',
        'course.professor'
    ]);

    
    $html = view('student.exams.certificate', compact('certificate'))->render();

    
    $browsershot = Browsershot::html($html)
        ->format('A4')
        ->landscape()
        ->margins(0, 0, 0, 0)          
        ->showBackground()            
        ->waitUntilNetworkIdle()      
        ->timeout(60);                 

  
    if (app()->environment('local')) {
        $browsershot->setChromePath(
            'C:\Program Files\Google\Chrome\Application\chrome.exe'
        );
    }

    //Generate PDF
    $pdf = $browsershot->pdf();

    // Return download
    return response($pdf)
        ->header('Content-Type', 'application/pdf')
        ->header(
            'Content-Disposition',
            'attachment; filename="certificate-' . $certificate->certificate_number . '.pdf"'
        );
}
}
