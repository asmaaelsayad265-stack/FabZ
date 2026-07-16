<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CategoryStoreRequest;
use App\Http\Requests\Api\V1\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoriesRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoriesRepository $categories)
    {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $q = $request->query('q');

        $query = $q ? $this->categories->search($q) : Category::query()->orderByDesc('id');

        $paginator = $query->paginate($perPage);
        return CategoryResource::collection($paginator);
    }

    public function show(int $categoryId)
    {
        $category = $this->categories->findOrFail($categoryId);
        return new CategoryResource($category);
    }

    public function store(CategoryStoreRequest $request)
    {
        $category = $this->categories->create($request->validated());
        return new CategoryResource($category);
    }

    public function update(CategoryUpdateRequest $request, int $categoryId)
    {
        $category = $this->categories->findOrFail($categoryId);
        $category = $this->categories->update($category, $request->validated());
        return new CategoryResource($category);
    }

    public function destroy(int $categoryId)
    {
        $category = $this->categories->findOrFail($categoryId);
        $this->categories->delete($category);
        return response()->json(['message' => 'Deleted']);
    }
}

