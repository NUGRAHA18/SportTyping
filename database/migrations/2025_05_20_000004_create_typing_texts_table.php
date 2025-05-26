<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typing_texts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->unsignedInteger('word_count');
            $table->foreignId('category_id')->constrained('text_categories')->onDelete('cascade');
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('beginner');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('typing_texts');
    }
};
