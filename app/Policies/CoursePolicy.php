<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Admins can do everything.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Course $course): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isProfessor();
    }

    public function update(User $user, Course $course): bool
    {
        return $user->isProfessor() && $user->id === $course->professor_id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->isProfessor() && $user->id === $course->professor_id;
    }

    public function manageStudents(User $user, Course $course): bool
    {
        return $user->isProfessor() && $user->id === $course->professor_id;
    }

    public function gradeStudents(User $user, Course $course): bool
    {
        return $user->isProfessor() && $user->id === $course->professor_id;
    }

    public function viewStudents(User $user, Course $course): bool
    {
        return $user->isProfessor() && $user->id === $course->professor_id;
    }
}
