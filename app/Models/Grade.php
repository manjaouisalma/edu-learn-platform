<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Enrollment;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'student_id',
        'course_id',
        'professor_id',
        'score',
        'feedback',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }


    // Grade.php

public function getLetterGradeAttribute()
{
    return match(true) {
        $this->score >= 90 => 'A',
        $this->score >= 80 => 'B',
        $this->score >= 70 => 'C',
        $this->score >= 60 => 'D',
        default            => 'F',
    };
}

public function getGradeColorAttribute()
{
    return match(true) {
        $this->score >= 90 => 'success',
        $this->score >= 80 => 'primary',
        $this->score >= 70 => 'warning',
        $this->score >= 60 => 'secondary',
        default            => 'danger',
    };
}
}