<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->enum('device_type', ['mobile', 'pc']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_sessions');
    }
};
