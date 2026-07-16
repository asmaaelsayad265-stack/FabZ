<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToThrough;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_section_id',
        'title',
        'content',
        'position',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    // convenience relation (via section)
    public function course()
    {
        return $this->belongsToThrough(Course::class, CourseSection::class, 'id', 'id', 'course_section_id', 'course_id');
    }
}

