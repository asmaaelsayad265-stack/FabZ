<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseMedia extends Model
{
    use HasFactory;

    protected $table = 'course_media';

    protected $fillable = [
        'course_id',
        'type',
        'url',
        'title',
        'position',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}

