<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('typing_speed', 6, 2)->comment('Words per minute');
            $table->decimal('typing_accuracy', 5, 2)->comment('Percentage');
            $table->unsignedInteger('completion_time')->nullable()->comment('Time in seconds');
            $table->unsignedInteger('position')->nullable()->comment('Ranking in competition');
            $table->unsignedInteger('experience_earned')->default(0);
            $table->timestamps();
            $table->unique(['competition_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_results');
    }
};
