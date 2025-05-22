<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_bot')->default(false);
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            $table->unique(['competition_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_participants');
    }
};
