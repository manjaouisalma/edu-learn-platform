<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Professor;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
         'image',  
        'slug',
        'level',
        'category',
        'price',
        'is_free',
        'is_published',
        'duration_hours',
        'start_date',
        'end_date',
        'professor_id',
        'level', 
    ];

    protected $casts = [
       'start_date'  => 'datetime',
        'end_date'    => 'datetime',
        'is_free'     => 'boolean',
        'is_published'=> 'boolean',
        'price'       => 'decimal:2',
    ];

    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title) . '-' . uniqid();
            }
        });
    }

    // ─── Accessors ───────────────────────────────
    public function getLevelBadgeAttribute(): string
    {
        return match($this->level) {
            'beginner'     => '<span class="badge badge-success">Beginner</span>',
            'intermediate' => '<span class="badge badge-warning">Intermediate</span>',
            'advanced'     => '<span class="badge badge-danger">Advanced</span>',
            default        => '<span class="badge badge-secondary">—</span>',
        };
    }


    // ─── Scopes ───────────────────────────────
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
            ->orWhere('category', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
        });
    }
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
    
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'enrollments',
            'course_id',
            'student_id'
        )
        ->withPivot('status', 'enrolled_at', 'completed_at')
        ->withTimestamps();
    }

    public function professor()
    {
        return $this->belongsTo(User::class,'professor_id');
    }


    public function exam()
    {
        return $this->hasOne(Exam::class);
    }


    public function getImageUrlAttribute()
{
    if ($this->image) {
        return asset('storage/' . $this->image);
    }
    return null;
}
}