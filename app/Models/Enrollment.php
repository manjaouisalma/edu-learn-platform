<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Certificate;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'status',
        'is_blocked',
        'enrolled_at',
        'completed_at',
    ];

    protected $casts = [
        'enrolled_at'  => 'datetime',
        'completed_at' => 'datetime',
        'is_blocked' => 'boolean',
    ];

    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

   
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    
    public function grade()
    {
        return $this->hasOne(Grade::class, 'enrollment_id');
    }

    public function certificate()
{
    return $this->hasOne(Certificate::class, 'student_id', 'student_id')
                ->where('course_id', $this->course_id);
}

    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}