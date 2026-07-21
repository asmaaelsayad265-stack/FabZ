<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseRepository implements CourseRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Course::query()->with(['category', 'instructor'])->orderBy('id')->paginate($perPage);
    }

    public function create(array $data): Course
    {
        return Course::query()->create($data);
    }

    public function update(Course $course, array $data): Course
    {
        $course->fill($data);
        $course->save();

        return $course->refresh();
    }

    public function delete(Course $course): void
    {
        $course->delete();
    }
}
