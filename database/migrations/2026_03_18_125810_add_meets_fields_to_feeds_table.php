<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('feeds', function (Blueprint $table) {
            $table->date('meet_date')->nullable()->after('image');
            $table->string('meet_location')->nullable()->after('meet_date');
            $table->integer('meet_max_people')->nullable()->after('meet_location');
            $table->text('meet_description')->nullable()->after('meet_max_people');
        });
    }

    public function down()
    {
        Schema::table('feeds', function (Blueprint $table) {
            $table->dropColumn(['meet_date', 'meet_location', 'meet_max_people', 'meet_description']);
        });
    }
};

