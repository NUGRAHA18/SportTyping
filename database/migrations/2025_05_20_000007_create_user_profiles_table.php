<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->decimal('typing_speed_avg', 6, 2)->default(0.00);
            $table->decimal('typing_accuracy_avg', 5, 2)->default(0.00);
            $table->unsignedInteger('total_competitions')->default(0);
            $table->unsignedInteger('total_wins')->default(0);
            $table->foreignId('current_league_id')->nullable()->constrained('leagues')->onDelete('set null');
            $table->unsignedInteger('total_experience')->default(0);
            $table->enum('device_preference', ['mobile', 'pc', 'both'])->default('both');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
