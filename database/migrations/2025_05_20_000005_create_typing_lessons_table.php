<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typing_lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('beginner');
            $table->unsignedInteger('order_number');
            $table->longText('content');
            $table->unsignedInteger('estimated_completion_time')->nullable()->comment('Time in minutes');
            $table->unsignedInteger('experience_reward')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('typing_lessons');
    }
};
