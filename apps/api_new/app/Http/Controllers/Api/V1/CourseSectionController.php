<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CourseSectionStoreRequest;
use App\Http\Requests\Api\V1\CourseSectionUpdateRequest;
use App\Http\Resources\CourseSectionResource;
use App\Models\CourseSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseSectionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = CourseSection::query()->with(['course', 'lessons']);

        if ($request->filled('course_id')) {
            $query->where('course_id', (int) $request->query('course_id'));
        }

        $perPage = (int) $request->query('per_page', 15);

        return response()->json($query->paginate($perPage));
    }

    public function store(CourseSectionStoreRequest $request): CourseSectionResource
    {
        $section = CourseSection::create($request->validated());

        return new CourseSectionResource($section->load(['course', 'lessons']));
    }

    public function show(CourseSection $section): CourseSectionResource
    {
        return new CourseSectionResource($section->load(['course', 'lessons']));
    }

    public function update(CourseSectionUpdateRequest $request, CourseSection $section): CourseSectionResource
    {
        $section->update($request->validated());

        return new CourseSectionResource($section->load(['course', 'lessons']));
    }

    public function destroy(CourseSection $section): JsonResponse
    {
        $section->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
