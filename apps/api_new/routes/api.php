<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EmailVerificationController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\PasswordResetController;
use App\Http\Controllers\Api\V1\RoleProtectedController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', HealthController::class);

    // -------------------------
    // Course Management (protected)
    // -------------------------
    Route::apiResource('/categories', \App\Http\Controllers\Api\V1\CategoryController::class)
        ->middleware(['auth:sanctum', 'ensure.role:super_admin']);

    // -------------------------
    // Courses (protected)
    // -------------------------
    Route::middleware(['auth:sanctum', 'ensure.role:super_admin,admin,instructor'])
        ->group(function () {
            Route::apiResource('/courses', \App\Http\Controllers\Api\V1\CourseController::class);
            Route::post('/courses/{course}/search', [\App\Http\Controllers\Api\V1\CourseController::class, 'search']);
        });

    Route::middleware(['auth:sanctum', 'ensure.role:instructor,admin,super_admin,student'])
        ->group(function () {
            Route::get('/courses', [\App\Http\Controllers\Api\V1\CourseController::class, 'index']);
            Route::get('/courses/{course}', [\App\Http\Controllers\Api\V1\CourseController::class, 'show']);
        });

    // -------------------------
    // Sections (protected)
    // -------------------------
    Route::middleware(['auth:sanctum', 'ensure.role:super_admin', 'ensure.role:admin', 'ensure.role:instructor'])
        ->group(function () {
            Route::apiResource('/sections', \App\Http\Controllers\Api\V1\CourseSectionController::class);
        });

    // -------------------------
    // Lessons (protected)
    // -------------------------
    Route::middleware(['auth:sanctum', 'ensure.role:super_admin', 'ensure.role:admin', 'ensure.role:instructor'])
        ->group(function () {
            Route::apiResource('/lessons', \App\Http\Controllers\Api\V1\LessonController::class);
        });

    // -------------------------
    // Media (protected)
    // -------------------------
    Route::middleware(['auth:sanctum', 'ensure.role:super_admin', 'ensure.role:admin', 'ensure.role:instructor'])
        ->group(function () {
            Route::apiResource('/media', \App\Http\Controllers\Api\V1\CourseMediaController::class)->except(['index']);
            Route::get('/media/{media}', [\App\Http\Controllers\Api\V1\CourseMediaController::class, 'show']);
            Route::post('/courses/{course}/media', [\App\Http\Controllers\Api\V1\CourseMediaController::class, 'upload']);
        });

    Route::middleware(['auth:sanctum', 'ensure.role:super_admin', 'ensure.role:admin', 'ensure.role:instructor', 'ensure.role:student'])
        ->group(function () {
            Route::get('/courses/{course}/media', [\App\Http\Controllers\Api\V1\CourseMediaController::class, 'indexByCourse']);
        });

    // -------------------------
    // Enrollments (protected)
    // -------------------------
    Route::middleware(['auth:sanctum', 'ensure.role:super_admin', 'ensure.role:admin', 'ensure.role:student'])
        ->group(function () {
            Route::post('/enrollments', [\App\Http\Controllers\Api\V1\EnrollmentController::class, 'enroll']);
            Route::delete('/enrollments/{course}', [\App\Http\Controllers\Api\V1\EnrollmentController::class, 'unenroll']);
            Route::get('/enrollments/me', [\App\Http\Controllers\Api\V1\EnrollmentController::class, 'myCourses']);
        });

    // -------------------------
    // Progress (protected)
    // -------------------------
    Route::middleware(['auth:sanctum', 'ensure.role:super_admin', 'ensure.role:admin', 'ensure.role:student'])
        ->group(function () {
            Route::put('/progress', [\App\Http\Controllers\Api\V1\CourseProgressController::class, 'update']);
            Route::get('/progress/{course}', [\App\Http\Controllers\Api\V1\CourseProgressController::class, 'show']);
            Route::get('/progress/{course}/completion', [\App\Http\Controllers\Api\V1\CourseProgressController::class, 'completionStatus']);
        });

    // -------------------------
    // Authentication (public)
    // -------------------------
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum');
    Route::post('/auth/refresh', [AuthController::class, 'refresh'])
        ->middleware('auth:sanctum');
    Route::get('/auth/me', [AuthController::class, 'me'])
        ->middleware('auth:sanctum');

    // -------------------------
    // Password reset (public)
    // -------------------------
    Route::post('/password/forgot', [PasswordResetController::class, 'forgot']);
    Route::post('/password/reset', [PasswordResetController::class, 'reset']);

    // -------------------------
    // Email verification (protected)
    // -------------------------
    Route::post('/email/verify', [EmailVerificationController::class, 'verify'])
        ->middleware('auth:sanctum');
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])
        ->middleware('auth:sanctum');

    // -------------------------
    // RBAC examples (protected)
    // -------------------------
    Route::middleware(['auth:sanctum', 'ensure.role:super_admin'])->group(function () {
        Route::get('/rbac/super-admin', [RoleProtectedController::class, 'index']);
    });

    Route::middleware(['auth:sanctum', 'ensure.role:admin'])->group(function () {
        Route::get('/rbac/admin', [RoleProtectedController::class, 'index']);
    });

    Route::middleware(['auth:sanctum', 'ensure.role:instructor'])->group(function () {
        Route::get('/rbac/instructor', [RoleProtectedController::class, 'index']);
    });

    Route::middleware(['auth:sanctum', 'ensure.role:student'])->group(function () {
        Route::get('/rbac/student', [RoleProtectedController::class, 'index']);
    });
});

