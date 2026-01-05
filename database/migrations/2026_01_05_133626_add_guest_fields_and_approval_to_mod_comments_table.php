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
            // Drop foreign key constraint first
            $table->dropForeign(['user_id']);
            
            // Make user_id nullable (for guest comments)
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // Add guest fields
            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('guest_email')->nullable()->after('guest_name');
            
            // Add approval status
            $table->boolean('is_approved')->default(false)->after('is_pinned');
            
            // Re-add foreign key constraint (nullable)
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mod_comments', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['user_id']);
            
            // Remove guest fields
            $table->dropColumn(['guest_name', 'guest_email', 'is_approved']);
            
            // Make user_id required again
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            
            // Re-add foreign key constraint (required)
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
