<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->index('total_experience');
        });
        
        Schema::table('typing_texts', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('difficulty_level');
        });
        
        Schema::table('competitions', function (Blueprint $table) {
            $table->index('status');
            $table->index('device_type');
        });
        
        Schema::table('user_experience', function (Blueprint $table) {
            $table->index(['source_type', 'source_id']);
        });
        
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->index('completion_status');
        });
        
        Schema::table('user_practices', function (Blueprint $table) {
            $table->index(['user_id', 'created_at']);
        });
        
        Schema::table('badges', function (Blueprint $table) {
            $table->index(['requirement_type', 'requirement_value']);
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropIndex(['total_experience']);
        });
        
        Schema::table('typing_texts', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['difficulty_level']);
        });
        
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['device_type']);
        });
        
        Schema::table('user_experience', function (Blueprint $table) {
            $table->dropIndex(['source_type', 'source_id']);
        });
        
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropIndex(['completion_status']);
        });
        
        Schema::table('user_practices', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
        });
        
        Schema::table('badges', function (Blueprint $table) {
            $table->dropIndex(['requirement_type', 'requirement_value']);
        });
    }
};