<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    public function definition(): array
    {
        $title = ucfirst(fake()->unique()->words(3, true));

        return [
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'short_description' => fake()->sentence(),
            'full_description' => fake()->paragraph(),
            'duration_minutes' => fake()->numberBetween(30, 360),
            'level' => fake()->numberBetween(1, 5),
            'price' => fake()->randomFloat(2, 0, 999),
            'is_active' => true,
            'instructor_id' => User::factory(),
        ];
    }
}
