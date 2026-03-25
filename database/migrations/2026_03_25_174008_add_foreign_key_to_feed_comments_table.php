<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feed_comments', function (Blueprint $table) {
            // Drop existing foreign key if exists
            $table->dropForeign(['parent_id']);
            
            // Add foreign key with cascade delete
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('feed_comments')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feed_comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            
            // Re-add foreign key without cascade (or leave it without foreign key)
            // For simplicity, we'll just drop it in rollback
        });
    }
};
