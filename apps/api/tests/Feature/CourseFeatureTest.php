<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function test_instructor_can_create_course(): void
    {
        $instructor = User::factory()->create();
        $instructor->assignRole('instructor');
        Sanctum::actingAs($instructor, guard: 'sanctum');

        $category = Category::factory()->create();

        $response = $this->postJson('/api/courses', [
            'category_id' => $category->id,
            'title' => 'Intro to Robotics',
            'slug' => 'intro-to-robotics',
            'short_description' => 'Basics',
            'duration_minutes' => 120,
            'level' => 1,
            'price' => 100,
            'is_active' => true,
            'instructor_id' => $instructor->id,
        ]);

        $response->assertOk()->assertJsonPath('data.slug', 'intro-to-robotics');
        $this->assertDatabaseHas('courses', ['slug' => 'intro-to-robotics']);
    }

    public function test_student_cannot_create_course(): void
    {
        $student = User::factory()->create();
        $student->assignRole('student');
        Sanctum::actingAs($student, guard: 'sanctum');

        $category = Category::factory()->create();

        $this->postJson('/api/courses', [
            'category_id' => $category->id,
            'title' => 'Unauthorized Course',
            'slug' => 'unauthorized-course',
        ])->assertForbidden();
    }

    public function test_route_binding_returns_not_found_for_missing_course(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Sanctum::actingAs($admin, guard: 'sanctum');

        $this->getJson('/api/courses/99999')->assertNotFound();
    }

    public function test_instructor_can_update_own_course(): void
    {
        $instructor = User::factory()->create();
        $instructor->assignRole('instructor');

        $course = Course::factory()->for($instructor, 'instructor')->create();

        Sanctum::actingAs($instructor, guard: 'sanctum');

        $this->putJson('/api/courses/'.$course->id, [
            'title' => 'Updated Title',
            'slug' => 'updated-title',
        ])->assertOk()->assertJsonPath('data.title', 'Updated Title');
    }
}
