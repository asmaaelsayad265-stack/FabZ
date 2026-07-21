<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\CourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseService
{
    public function __construct(private readonly CourseRepositoryInterface $courseRepository)
    {
    }

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return $this->courseRepository->paginate($perPage);
    }

    public function create(array $data): Course
    {
        return $this->courseRepository->create($data);
    }

    public function update(Course $course, array $data): Course
    {
        return $this->courseRepository->update($course, $data);
    }

    public function delete(Course $course): void
    {
        $this->courseRepository->delete($course);
    }
}
