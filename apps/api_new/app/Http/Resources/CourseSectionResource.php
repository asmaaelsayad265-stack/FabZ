<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseSectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'title' => $this->title,
            'position' => $this->position,
            'course' => $this->whenLoaded('course'),
            'lessons' => $this->whenLoaded('lessons'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
