<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['global', 'league', 'country', 'device_type'])->default('global');
            $table->enum('device_type', ['mobile', 'pc', 'both'])->default('both');
            $table->foreignId('category_id')->nullable()->constrained('text_categories')->onDelete('set null');
            $table->foreignId('league_id')->nullable()->constrained('leagues')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboards');
    }
};
