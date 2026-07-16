<?php

namespace App\Http\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    private function canManage(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin', 'instructor']);
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin', 'instructor', 'student']);
    }

    public function view(User $user, Course $course): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user);
    }

    public function update(User $user, Course $course): bool
    {
        return $this->canManage($user);
    }

    public function delete(User $user, Course $course): bool
    {
        return $this->canManage($user);
    }
}

