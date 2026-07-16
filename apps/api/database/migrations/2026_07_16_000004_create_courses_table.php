<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();

            $table->string('thumbnail_url')->nullable();
            $table->unsignedInteger('duration_minutes')->default(0);
            $table->unsignedInteger('level')->default(1); // 1..N

            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);

            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

