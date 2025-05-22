<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_practices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('text_id')->constrained('typing_texts')->onDelete('cascade');
            $table->decimal('typing_speed', 6, 2)->comment('Words per minute');
            $table->decimal('typing_accuracy', 5, 2)->comment('Percentage');
            $table->unsignedInteger('completion_time')->nullable()->comment('Time in seconds');
            $table->unsignedInteger('experience_earned')->default(0);
            $table->foreignId('device_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_practices');
    }
};
