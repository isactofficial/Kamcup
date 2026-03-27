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
        // No longer needed - meet_date is now DATETIME in previous migration
        Schema::table('feeds', function (Blueprint $table) {
            // Migration is now empty as functionality moved to previous migration
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feeds', function (Blueprint $table) {
            // Drop DATETIME and recreate as DATE
            $table->dropColumn('meet_date');
            $table->date('meet_date')->nullable()->after('meet_location'); // adjust position as needed
        });
    }
};
