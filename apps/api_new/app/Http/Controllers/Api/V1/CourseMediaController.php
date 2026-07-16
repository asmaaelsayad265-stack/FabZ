<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CourseMediaController extends Controller
{
    public function indexByCourse(Request $request, Course $course): JsonResponse
    {
        $query = $course->media()->newQuery();

        $type = $request->query('type');
        if (is_string($type) && $type !== '') {
            $query->where('type', $type);
        }

        $perPage = (int) $request->query('per_page', 15);

        return response()->json([
            'data' => $query->paginate($perPage),
        ]);
    }

    public function show(CourseMedia $media): JsonResponse
    {
        return response()->json([
            'data' => $media,
        ]);
    }

    public function upload(Request $request, Course $course): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:50'],
            'file' => ['required', 'file', 'max:10240'],
            'title' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);

        /** @var UploadedFile $file */
        $file = $validated['file'];

        $path = $file->store('course-media', 'public');

        $media = CourseMedia::create([
            'course_id' => $course->id,
            'type' => $validated['type'],
            'url' => url('/') . '/storage/' . $path,
            'title' => $validated['title'] ?? null,
            'position' => $validated['position'] ?? null,
        ]);

        return response()->json(['data' => $media], 201);
    }

    public function update(Request $request, CourseMedia $media): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['sometimes', 'required', 'string', 'max:50'],
            'file' => ['sometimes', 'required', 'file', 'max:10240'],
            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'position' => ['sometimes', 'nullable', 'integer', 'min:1'],
        ]);

        if (array_key_exists('type', $validated)) {
            $media->type = $validated['type'];
        }
        if (array_key_exists('title', $validated)) {
            $media->title = $validated['title'];
        }
        if (array_key_exists('position', $validated)) {
            $media->position = $validated['position'];
        }

        if ($request->hasFile('file')) {
            /** @var UploadedFile $file */
            $file = $validated['file'];
            $path = $file->store('course-media', 'public');
            $media->url = url('/') . '/storage/' . $path;
        }

        $media->save();

        return response()->json(['data' => $media]);
    }

    public function destroy(CourseMedia $media): JsonResponse
    {
        $media->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
