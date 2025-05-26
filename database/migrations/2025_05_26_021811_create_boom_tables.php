<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Users table (for authentication and wallet)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->decimal('wallet', 8, 2)->nullable();
            $table->decimal('wallet_balance', 8, 2)->default(500.00);
            $table->timestamps();
        });

        // Videos table (for short-form and long-form videos)
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('video_type', ['short_form', 'long_form']);
            $table->string('video_path')->nullable();
            $table->string('video_url')->nullable();
            $table->decimal('price', 8, 2)->default(0.00);
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });

        // Purchases table (to track paid video access)
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->timestamps();
        });

        // Comments table (for video comments)
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->text('comment');
            $table->timestamps();
        });

        // Gifts table (to log gift actions)
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gifts');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('users');
    }
};