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
        Schema::table('mods', function (Blueprint $table) {
            $table->decimal('reviews_avg', 3, 2)->default(0)->after('downloads_count');
            $table->unsignedInteger('reviews_count')->default(0)->after('reviews_avg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mods', function (Blueprint $table) {
            $table->dropColumn(['reviews_avg', 'reviews_count']);
        });
    }
};
