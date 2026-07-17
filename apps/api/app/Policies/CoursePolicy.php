<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin', 'instructor', 'student']);
    }

    public function view(User $user, Course $course): bool
    {
        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return true;
        }

        if ($user->hasRole('instructor')) {
            return (int) $course->instructor_id === (int) $user->id;
        }

        return $user->hasRole('student') && $course->is_active;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin', 'instructor']);
    }

    public function update(User $user, Course $course): bool
    {
        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return true;
        }

        return $user->hasRole('instructor') && (int) $course->instructor_id === (int) $user->id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin']) || ($user->hasRole('instructor') && (int) $course->instructor_id === (int) $user->id);
    }
}
