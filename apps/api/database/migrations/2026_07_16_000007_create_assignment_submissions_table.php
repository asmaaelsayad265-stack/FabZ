<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();

            $table->string('github_link')->nullable();
            $table->text('notes')->nullable();

            $table->string('file_url')->nullable(); // Cloudinary URL or signed URL
            $table->timestamps();

            $table->unique(['assignment_id', 'student_id']);

            $table->index(['student_id', 'assignment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};

