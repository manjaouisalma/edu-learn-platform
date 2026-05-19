<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage; 
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'bio',
    'phone',
    'avatar',
    'birth_date',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
// ─── Accessor ────────────────────────────────

    public function getAvatarUrlAttribute(): string
{
    $colors = [
        '2563eb', 
        '16a34a', 
        'dc2626', 
        'ea580c', 
        '9333ea', 
        '0891b2', 
        'db2777', 
    ];

    $color = $colors[$this->id % count($colors)];

    return $this->avatar && Storage::exists($this->avatar)
        ? Storage::url($this->avatar)
        : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=' . $color . '&color=fff&size=80';
}

//ROLE 
public function getRoleBadgeAttribute(): string
{
    return match($this->role) {
        'admin'     => '<span class="badge badge-admin">Admin</span>',
        'professor' => '<span class="badge badge-professor">Professor</span>',
        'student'   => '<span class="badge badge-student">Student</span>',
        default     => '<span class="badge badge-secondary">' . $this->role . '</span>',
    };
}

// ─── Role Helpers ───────────────────────────────

public function isAdmin(): bool
{
    return $this->role === 'admin';
}

public function isProfessor(): bool
{
    return $this->role === 'professor';
}

public function isStudent(): bool
{
    return $this->role === 'student';
}

    // ─── Relations ───────────────────────────────

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    
    public function grades()
    {
        return $this->hasManyThrough(
            Grade::class,
            Enrollment::class,
            'student_id',    
            'enrollment_id', 
            'id',            
            'id'             
        );
    }

   
    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'enrollments',   
            'student_id',    
            'course_id'      
        )
        ->withPivot('status', 'enrolled_at', 'completed_at')
        ->withTimestamps();
    }

    
        public function taughtCourses()
        {
            return $this->hasMany(Course::class, 'professor_id');
        }
}
