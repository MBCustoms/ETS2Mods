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
        Schema::table('follows', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('followable_id')->after('user_id');
            $table->string('followable_type')->after('followable_id');
            
            // Index for polymorphic relationship queries
            $table->index(['followable_id', 'followable_type']);
            $table->index('user_id');
            
            // Prevent duplicate follows
            $table->unique(['user_id', 'followable_id', 'followable_type'], 'unique_follow');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follows', function (Blueprint $table) {
            $table->dropUnique('unique_follow');
            $table->dropIndex(['followable_id', 'followable_type']);
            $table->dropIndex(['user_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'followable_id', 'followable_type']);
        });
    }
};
