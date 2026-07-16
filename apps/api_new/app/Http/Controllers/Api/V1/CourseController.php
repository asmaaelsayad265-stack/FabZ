<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CourseStoreRequest;
use App\Http\Requests\Api\V1\CourseUpdateRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly CourseService $courses)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Course::class);

        $filters = $request->only(['q', 'category_id']);
        $perPage = (int) $request->query('per_page', 15);

        $paginator = $this->courses->list($filters, $perPage);

        return CourseResource::collection($paginator);
    }

    public function store(CourseStoreRequest $request)
    {
        $this->authorize('create', Course::class);

        $course = $this->courses->create($request->validated());

        return (new CourseResource($course))->response()->setStatusCode(201);
    }

    public function show(int $courseId)
    {
        $this->authorize('view', Course::class);

        $course = $this->courses->findOrFail($courseId);

        return new CourseResource($course);
    }

    public function update(CourseUpdateRequest $request, int $courseId)
    {
        $course = $this->courses->findOrFail($courseId);
        $this->authorize('update', $course);

        $course = $this->courses->update($course, $request->validated());

        return new CourseResource($course);
    }

    public function destroy(int $courseId)
    {
        $course = $this->courses->findOrFail($courseId);
        $this->authorize('delete', $course);

        $this->courses->delete($course);

        return response()->json(['message' => 'Deleted']);
    }

    public function search(Request $request)
    {
        $this->authorize('viewAny', Course::class);

        $filters = $request->only(['q', 'category_id']);
        $perPage = (int) $request->query('per_page', 15);

        $paginator = $this->courses->search($filters, $perPage);

        return CourseResource::collection($paginator);
    }
}

