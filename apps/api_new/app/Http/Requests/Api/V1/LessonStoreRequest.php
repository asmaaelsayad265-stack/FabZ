<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class LessonStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_section_id' => ['required', 'integer', 'exists:course_sections,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'position' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
