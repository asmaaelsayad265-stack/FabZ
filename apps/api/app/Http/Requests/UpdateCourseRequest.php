<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Course $course */
        $course = $this->route('course');

        return $this->user()?->can('update', $course) ?? false;
    }

    public function rules(): array
    {
        /** @var Course $course */
        $course = $this->route('course');

        return [
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', Rule::unique('courses', 'slug')->ignore($course->id)],
            'short_description' => ['nullable', 'string'],
            'full_description' => ['nullable', 'string'],
            'thumbnail_url' => ['nullable', 'url', 'max:2048'],
            'duration_minutes' => ['sometimes', 'integer', 'min:0'],
            'level' => ['sometimes', 'integer', 'min:1'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'instructor_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
