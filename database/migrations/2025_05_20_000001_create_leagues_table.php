<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('min_experience')->default(0);
            $table->unsignedInteger('max_experience')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });
        
        // Insert default leagues
        DB::table('leagues')->insert([
            ['name' => 'Novice', 'description' => 'Beginning your typing journey', 'min_experience' => 0, 'max_experience' => 499, 'icon' => 'novice.png', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Apprentice', 'description' => 'Developing your typing skills', 'min_experience' => 500, 'max_experience' => 1999, 'icon' => 'apprentice.png', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Journeyman', 'description' => 'Consistent typing skill', 'min_experience' => 2000, 'max_experience' => 4999, 'icon' => 'journeyman.png', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Expert', 'description' => 'Advanced typing skill', 'min_experience' => 5000, 'max_experience' => 9999, 'icon' => 'expert.png', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Master', 'description' => 'Superior typing skill', 'min_experience' => 10000, 'max_experience' => 19999, 'icon' => 'master.png', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Grandmaster', 'description' => 'Exceptional typing skill', 'min_experience' => 20000, 'max_experience' => 49999, 'icon' => 'grandmaster.png', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Legend', 'description' => 'Legendary typing skill', 'min_experience' => 50000, 'max_experience' => null, 'icon' => 'legend.png', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('leagues');
    }
};
