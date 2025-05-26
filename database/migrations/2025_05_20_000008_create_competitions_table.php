<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->enum('status', ['upcoming', 'active', 'completed'])->default('upcoming');
            $table->enum('device_type', ['mobile', 'pc', 'both'])->default('both');
            $table->foreignId('text_id')->constrained('typing_texts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
