<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\CoursesRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseService
{
    public function __construct(private readonly CoursesRepository $courses)
    {
    }

    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->courses->paginate($filters, $perPage);
    }

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->courses->search($filters, $perPage);
    }

    public function findOrFail(int $id): Course
    {
        return $this->courses->findOrFail($id);
    }

    public function create(array $data): Course
    {
        return $this->courses->create($data);
    }

    public function update(Course $course, array $data): Course
    {
        return $this->courses->update($course, $data);
    }

    public function delete(Course $course): void
    {
        $this->courses->delete($course);
    }

    public function getSlug(string $slug): bool
    {
        return $this->courses->existsBySlug($slug);
    }
}
