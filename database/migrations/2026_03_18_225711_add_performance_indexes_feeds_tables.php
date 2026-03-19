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
        Schema::table('feed_likes', function (Blueprint $table) {
            $table->index(['user_id'], 'idx_feed_likes_user_id');
        });

        Schema::table('feed_user_joins', function (Blueprint $table) {
            $table->index(['user_id', 'feed_id'], 'idx_feed_user_joins_user_feed');
        });

        Schema::table('feeds', function (Blueprint $table) {
            $table->index('meet_date', 'idx_feeds_meet_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feed_likes', function (Blueprint $table) {
            $table->dropIndex('idx_feed_likes_user_id');
        });

        Schema::table('feed_user_joins', function (Blueprint $table) {
            $table->dropIndex('idx_feed_user_joins_user_feed');
        });

        Schema::table('feeds', function (Blueprint $table) {
            $table->dropIndex('idx_feeds_meet_date');
        });
    }
};
