<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class CoursesRepository
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Course::query()->with(['category']);

        if (!empty($filters['q'])) {
            $q = (string) $filters['q'];

            $query->where(function (Builder $qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', (int) $filters['category_id']);
        }

        return $query->orderByDesc('id')->paginate($perPage);
    }

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->paginate($filters, $perPage);
    }

    public function findOrFail(int $id): Course
    {
        return Course::query()->with(['category'])->findOrFail($id);
    }

    public function create(array $data): Course
    {
        return Course::query()->create($data)->load(['category']);
    }

    public function update(Course $course, array $data): Course
    {
        $course->fill($data);
        $course->save();

        return $course->load(['category']);
    }

    public function delete(Course $course): void
    {
        $course->delete();
    }

    public function existsBySlug(string $slug): bool
    {
        return Course::query()->where('slug', $slug)->exists();
    }

    public function getCategoryOrFail(int $categoryId): Category
    {
        return Category::query()->findOrFail($categoryId);
    }
}
