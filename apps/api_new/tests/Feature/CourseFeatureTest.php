<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CourseFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function authUser(string $role): User
    {
        // Ensure the role exists with 'web' guard
        \Spatie\Permission\Models\Role::findOrCreate($role, 'web');
        
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    public function test_guest_cannot_access_courses(): void
    {
        $this->getJson('/api/v1/courses')
            ->assertStatus(401);
    }

    public function test_instructor_can_list_courses_with_pagination(): void
    {
        $user = $this->authUser('instructor');
        $category = Category::factory()->create();

        Course::factory()->count(3)->create(['category_id' => $category->id]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/courses?per_page=2')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'data',
                    'current_page',
                    'per_page',
                    'total',
                ],
            ]);
    }

    public function test_instructor_can_filter_courses_by_category(): void
    {
        $user = $this->authUser('instructor');

        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Course::factory()->count(2)->create(['category_id' => $category1->id]);
        Course::factory()->count(1)->create(['category_id' => $category2->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/courses?category_id=' . $category1->id)
            ->assertOk()
            ->json();

        $this->assertNotEmpty($response['data']['data']);
    }

    public function test_instructor_can_search_courses(): void
    {
        $user = $this->authUser('instructor');

        $category = Category::factory()->create();

        $course = Course::factory()->create([
            'category_id' => $category->id,
            'title' => 'Laravel Testing',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/courses?q=' . urlencode('Laravel'))
            ->assertOk()
            ->json();

        $ids = array_map(static fn ($item) => $item['id'], $response['data']['data']);
        $this->assertContains($course->id, $ids);
    }

    public function test_authorized_can_create_update_show_and_delete_course(): void
    {
        $user = $this->authUser('admin');
        $category = Category::factory()->create();

        $payload = [
            'category_id' => $category->id,
            'title' => 'Course 1',
            'slug' => 'course-1',
            'description' => 'desc',
            'duration_minutes' => 60,
            'price' => 10.50,
            'language' => 'en',
        ];

        $created = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/courses', $payload)
            ->assertCreated()
            ->json('data');

        $courseId = $created['id'];

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/courses/' . $courseId)
            ->assertOk()
            ->assertJsonStructure(['id', 'title', 'slug']);

        $updatePayload = [
            'title' => 'Course 1 Updated',
        ];

        $updated = $this->actingAs($user, 'sanctum')
            ->putJson('/api/v1/courses/' . $courseId, $updatePayload)
            ->assertOk()
            ->json('data');

        $this->assertSame('Course 1 Updated', $updated['title']);

        $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/v1/courses/' . $courseId)
            ->assertOk()
            ->assertJson(['message' => 'Deleted']);
    }

    public function test_guest_cannot_create_course(): void
    {
        $payload = [
            'category_id' => Category::factory()->create()->id,
            'title' => 'Course 1',
            'slug' => 'course-1',
        ];

        $this->postJson('/api/v1/courses', $payload)
            ->assertStatus(401);
    }

    public function test_validation_missing_required_fields(): void
    {
        $user = $this->authUser('instructor');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/courses', [
                'category_id' => null,
                'title' => '',
                'slug' => '',
            ])
            ->assertStatus(422);
    }
}
