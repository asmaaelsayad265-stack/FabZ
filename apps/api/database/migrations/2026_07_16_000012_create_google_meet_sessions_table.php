<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('google_meet_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();

            $table->string('title')->default('Session');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();

            // Meet link could be generated or stored after creation
            $table->text('meet_link')->nullable();
            $table->string('meet_event_id')->nullable()->unique();

            $table->boolean('is_cancelled')->default(false);

            $table->timestamps();

            $table->index(['course_id', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('google_meet_sessions');
    }
};

