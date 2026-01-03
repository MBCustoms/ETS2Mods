<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('game_version')->nullable();
            $table->string('file_size')->nullable();
            $table->string('download_url')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('downloads_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['status', 'published_at']);
            $table->index('category_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mods');
    }
};
