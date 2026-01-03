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
        Schema::create('mod_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mod_id')->constrained()->cascadeOnDelete();
            $table->string('version_number');
            $table->string('game_version')->nullable();
            $table->string('file_size')->nullable();
            $table->string('download_url');
            $table->text('changelog')->nullable();
            $table->unsignedBigInteger('downloads_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mod_versions');
    }
};
