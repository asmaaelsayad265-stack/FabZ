<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function enroll(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'enrolled_at' => ['nullable', 'date'],
        ]);

        $enrollment = Enrollment::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'course_id' => (int) $validated['course_id'],
            ],
            [
                'enrolled_at' => $validated['enrolled_at'] ?? now(),
                'completed_at' => null,
            ]
        );

        return response()->json([
            'message' => 'Enrolled',
            'data' => $enrollment->load(['course']),
        ], 201);
    }

    public function unenroll(Course $course, Request $request): JsonResponse
    {
        Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->delete();

        return response()->json(['message' => 'Unenrolled']);
    }

    public function myCourses(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);

        $query = Course::query()
            ->whereHas('enrollments', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            });

        return response()->json([
            'data' => $query->paginate($perPage),
        ]);
    }
}
