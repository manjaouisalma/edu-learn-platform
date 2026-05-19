<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = ['exam_id', 'student_id', 'answers', 'score', 'passed', 'completed_at'];

    protected $casts = ['answers' => 'array', 'passed' => 'boolean'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
}