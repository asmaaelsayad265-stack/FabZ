<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();

            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('github_link')->nullable();

            $table->string('image_url')->nullable();
            $table->string('video_url')->nullable();

            $table->timestamps();

            $table->index(['student_id']);
            $table->index(['course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

