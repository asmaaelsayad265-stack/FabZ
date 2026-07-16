<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseProgressController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'progress_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'last_lesson_id' => ['nullable', 'integer', 'exists:lessons,id'],
        ]);

        $progress = CourseProgress::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'course_id' => (int) $validated['course_id'],
            ],
            [
                'progress_percent' => (int) $validated['progress_percent'],
                'last_lesson_id' => $validated['last_lesson_id'] ?? null,
            ]
        );

        return response()->json([
            'message' => 'Progress updated',
            'data' => $progress,
        ]);
    }

    public function show(Course $course, Request $request): JsonResponse
    {
        $progress = CourseProgress::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->first();

        return response()->json([
            'data' => $progress,
        ]);
    }

    public function completionStatus(Course $course, Request $request): JsonResponse
    {
        $progress = CourseProgress::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->first();

        $completed = $progress?->progress_percent === 100;

        return response()->json([
            'completed' => (bool) $completed,
            'progress_percent' => $progress?->progress_percent ?? 0,
        ]);
    }
}
