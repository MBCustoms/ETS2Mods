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
        Schema::table('mod_comments', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating')->nullable()->after('content'); // 1-5, only for top-level comments
            $table->string('title')->nullable()->after('rating'); // Optional review title
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mod_comments', function (Blueprint $table) {
            $table->dropColumn(['rating', 'title']);
        });
    }
};
