<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseMedia;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackendRegressionTest extends TestCase
{
    use RefreshDatabase;

    private function authUser(string $role): User
    {
        \Spatie\Permission\Models\Role::findOrCreate($role, 'web');

        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    public function test_login_route_stays_public(): void
    {
        $this->postJson('/api/v1/auth/login', [])
            ->assertStatus(422);
    }

    public function test_course_media_remains_scoped_to_the_requested_course(): void
    {
        $user = $this->authUser('instructor');
        $category = Category::factory()->create();
        $courseA = Course::factory()->create(['category_id' => $category->id]);
        $courseB = Course::factory()->create(['category_id' => $category->id]);

        CourseMedia::create([
            'course_id' => $courseA->id,
            'type' => 'video',
            'url' => 'https://example.com/a.mp4',
            'title' => 'A',
            'position' => 1,
        ]);

        CourseMedia::create([
            'course_id' => $courseB->id,
            'type' => 'video',
            'url' => 'https://example.com/b.mp4',
            'title' => 'B',
            'position' => 1,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/courses/' . $courseA->id . '/media')
            ->assertOk()
            ->json();

        $this->assertCount(1, $response['data']['data']);
        $this->assertSame($courseA->id, $response['data']['data'][0]['course_id']);
    }

    public function test_lessons_index_is_usable(): void
    {
        $user = $this->authUser('instructor');
        $category = Category::factory()->create();
        $course = Course::factory()->create(['category_id' => $category->id]);
        $section = CourseSection::create([
            'course_id' => $course->id,
            'title' => 'Section 1',
            'position' => 1,
        ]);

        Lesson::create([
            'course_section_id' => $section->id,
            'title' => 'Lesson 1',
            'content' => 'content',
            'position' => 1,
        ]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/lessons')
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);
    }
}