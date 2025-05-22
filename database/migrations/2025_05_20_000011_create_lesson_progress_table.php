<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained('typing_lessons')->onDelete('cascade');
            $table->enum('completion_status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->decimal('highest_speed', 6, 2)->default(0.00);
            $table->decimal('highest_accuracy', 5, 2)->default(0.00);
            $table->unsignedInteger('experience_earned')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'lesson_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
    }
};
