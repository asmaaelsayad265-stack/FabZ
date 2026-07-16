<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();

            $table->string('provider'); // paymob, fawry
            $table->string('reference')->unique();

            $table->string('currency', 3)->default('EGP');
            $table->decimal('amount', 10, 2)->default(0);

            $table->string('status')->default('initiated');
            $table->json('raw_payload')->nullable();

            // Subscription ready (Stripe architecture later)
            $table->string('subscription_id')->nullable();

            $table->timestamps();

            $table->index(['student_id', 'provider']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_records');
    }
};

