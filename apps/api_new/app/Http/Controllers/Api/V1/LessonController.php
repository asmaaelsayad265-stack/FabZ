<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LessonStoreRequest;
use App\Http\Requests\Api\V1\LessonUpdateRequest;
use App\Http\Resources\LessonCollection;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Request $request): LessonCollection
    {
        return new LessonCollection(Lesson::query()->paginate((int) $request->query('per_page', 15)));
    }

    public function store(LessonStoreRequest $request): LessonResource
    {
        $lesson = Lesson::create($request->validated());

        return new LessonResource($lesson);
    }

    public function show(Lesson $lesson): LessonResource
    {
        return new LessonResource($lesson);
    }

    public function update(LessonUpdateRequest $request, Lesson $lesson): LessonResource
    {
        $lesson->update($request->validated());

        return new LessonResource($lesson);
    }

    public function destroy(Lesson $lesson): JsonResponse
    {
        $lesson->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
