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
        Schema::create('mod_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mod_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->string('title')->nullable();
            $table->text('review')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'mod_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mod_ratings');
    }
};
