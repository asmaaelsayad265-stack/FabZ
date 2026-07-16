<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('community_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();

            $table->string('title')->nullable();
            $table->longText('content');
            $table->timestamps();

            $table->index(['author_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_posts');
    }
};

