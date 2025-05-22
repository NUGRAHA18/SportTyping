<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_experience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('amount');
            $table->enum('source_type', ['competition', 'lesson', 'practice', 'achievement']);
            $table->unsignedBigInteger('source_id')->comment('Polymorphic ID referencing the source');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_experience');
    }
};
