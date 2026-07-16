<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoriesRepository
{
    public function paginate(int $perPage = 15)
    {
        return Category::query()->orderByDesc('id')->paginate($perPage);
    }

    public function search(?string $q)
    {
        $query = Category::query();
        if ($q) {
            $query->where('name', 'like', "%{$q}%")->orWhere('slug', 'like', "%{$q}%");
        }
        return $query;
    }

    public function findOrFail(int $id): Category
    {
        return Category::query()->findOrFail($id);
    }

    public function create(array $data): Category
    {
        return Category::query()->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->fill($data);
        $category->save();
        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}

