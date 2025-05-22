<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('text_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // Insert default categories
        DB::table('text_categories')->insert([
            ['name' => 'Programming', 'description' => 'Typing practice with code snippets and programming concepts', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Literature', 'description' => 'Excerpts from famous books and stories', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Science', 'description' => 'Scientific texts and explanations', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Technology', 'description' => 'Texts about technology and digital world', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Business', 'description' => 'Business-related content and professional terminology', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Random', 'description' => 'Miscellaneous texts for varied practice', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('text_categories');
    }
};
