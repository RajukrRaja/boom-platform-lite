// database/migrations/2025_05_26_153000_create_likes_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'video_id']); // Ensure one like per user per video
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};