<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->enum('requirement_type', ['experience', 'accuracy', 'speed', 'competitions', 'wins', 'lessons']);
            $table->unsignedInteger('requirement_value');
            $table->timestamps();
        });
        
        // Insert default badges
        DB::table('badges')->insert([
            // Experience badges
            ['name' => 'Rookie', 'description' => 'Earned 500 experience points', 'icon' => 'rookie.png', 'requirement_type' => 'experience', 'requirement_value' => 500, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Explorer', 'description' => 'Earned 2,000 experience points', 'icon' => 'explorer.png', 'requirement_type' => 'experience', 'requirement_value' => 2000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Veteran', 'description' => 'Earned 10,000 experience points', 'icon' => 'veteran.png', 'requirement_type' => 'experience', 'requirement_value' => 10000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Champion', 'description' => 'Earned 25,000 experience points', 'icon' => 'champion.png', 'requirement_type' => 'experience', 'requirement_value' => 25000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Legend', 'description' => 'Earned 50,000 experience points', 'icon' => 'legend.png', 'requirement_type' => 'experience', 'requirement_value' => 50000, 'created_at' => now(), 'updated_at' => now()],
            
            // Speed badges
            ['name' => 'Swift Fingers', 'description' => 'Achieved 40 WPM typing speed', 'icon' => 'swift_fingers.png', 'requirement_type' => 'speed', 'requirement_value' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Speed Demon', 'description' => 'Achieved 70 WPM typing speed', 'icon' => 'speed_demon.png', 'requirement_type' => 'speed', 'requirement_value' => 70, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lightning Hands', 'description' => 'Achieved 100 WPM typing speed', 'icon' => 'lightning_hands.png', 'requirement_type' => 'speed', 'requirement_value' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sonic Typist', 'description' => 'Achieved 130 WPM typing speed', 'icon' => 'sonic_typist.png', 'requirement_type' => 'speed', 'requirement_value' => 130, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Typing God', 'description' => 'Achieved 150+ WPM typing speed', 'icon' => 'typing_god.png', 'requirement_type' => 'speed', 'requirement_value' => 150, 'created_at' => now(), 'updated_at' => now()],
            
            // Accuracy badges
            ['name' => 'Precise', 'description' => 'Achieved 90% typing accuracy', 'icon' => 'precise.png', 'requirement_type' => 'accuracy', 'requirement_value' => 90, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Perfectionist', 'description' => 'Achieved 95% typing accuracy', 'icon' => 'perfectionist.png', 'requirement_type' => 'accuracy', 'requirement_value' => 95, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Flawless', 'description' => 'Achieved 98% typing accuracy', 'icon' => 'flawless.png', 'requirement_type' => 'accuracy', 'requirement_value' => 98, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Impeccable', 'description' => 'Achieved 100% typing accuracy', 'icon' => 'impeccable.png', 'requirement_type' => 'accuracy', 'requirement_value' => 100, 'created_at' => now(), 'updated_at' => now()],
            
            // Competition badges
            ['name' => 'Competitor', 'description' => 'Participated in 10 competitions', 'icon' => 'competitor.png', 'requirement_type' => 'competitions', 'requirement_value' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Regular', 'description' => 'Participated in 50 competitions', 'icon' => 'regular.png', 'requirement_type' => 'competitions', 'requirement_value' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dedicated', 'description' => 'Participated in 100 competitions', 'icon' => 'dedicated.png', 'requirement_type' => 'competitions', 'requirement_value' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Iron Will', 'description' => 'Participated in 500 competitions', 'icon' => 'iron_will.png', 'requirement_type' => 'competitions', 'requirement_value' => 500, 'created_at' => now(), 'updated_at' => now()],
            
            // Wins badges
            ['name' => 'Winner', 'description' => 'Won 5 competitions', 'icon' => 'winner.png', 'requirement_type' => 'wins', 'requirement_value' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Champion', 'description' => 'Won 25 competitions', 'icon' => 'champion_wins.png', 'requirement_type' => 'wins', 'requirement_value' => 25, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dominant', 'description' => 'Won 50 competitions', 'icon' => 'dominant.png', 'requirement_type' => 'wins', 'requirement_value' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Unstoppable', 'description' => 'Won 100 competitions', 'icon' => 'unstoppable.png', 'requirement_type' => 'wins', 'requirement_value' => 100, 'created_at' => now(), 'updated_at' => now()],
            
            // Lesson badges
            ['name' => 'Student', 'description' => 'Completed 5 typing lessons', 'icon' => 'student.png', 'requirement_type' => 'lessons', 'requirement_value' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Scholar', 'description' => 'Completed 15 typing lessons', 'icon' => 'scholar.png', 'requirement_type' => 'lessons', 'requirement_value' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Master Student', 'description' => 'Completed 30 typing lessons', 'icon' => 'master_student.png', 'requirement_type' => 'lessons', 'requirement_value' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Professor', 'description' => 'Completed all typing lessons', 'icon' => 'professor.png', 'requirement_type' => 'lessons', 'requirement_value' => 50, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
