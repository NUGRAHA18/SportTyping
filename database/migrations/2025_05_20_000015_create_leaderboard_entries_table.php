<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaderboard_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leaderboard_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('rank');
            $table->decimal('score', 8, 2);
            $table->timestamps();
            $table->unique(['leaderboard_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboard_entries');
    }
};
