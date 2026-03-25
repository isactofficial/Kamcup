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
        Schema::table('private_messages', function (Blueprint $table) {
            $table->foreignId('reply_message_id')->nullable()->constrained('private_messages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down()
    {
        Schema::table('private_messages', function (Blueprint $table) {
            $table->dropForeign(['reply_message_id']);
            $table->dropColumn('reply_message_id');
        });
    }
};
