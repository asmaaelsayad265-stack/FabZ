<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\Course\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    public function __construct(private readonly CourseService $courseService)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Course::class);

        return CourseResource::collection($this->courseService->list());
    }

    public function store(StoreCourseRequest $request): CourseResource
    {
        $course = $this->courseService->create($request->validated());

        return new CourseResource($course);
    }

    public function show(Course $course): CourseResource
    {
        $this->authorize('view', $course);

        return new CourseResource($course);
    }

    public function update(UpdateCourseRequest $request, Course $course): CourseResource
    {
        $course = $this->courseService->update($course, $request->validated());

        return new CourseResource($course);
    }

    public function destroy(Course $course): Response
    {
        $this->authorize('delete', $course);

        $this->courseService->delete($course);

        return response()->noContent();
    }
}
