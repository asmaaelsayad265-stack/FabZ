<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class CourseSectionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['sometimes', 'required', 'integer', 'exists:courses,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
