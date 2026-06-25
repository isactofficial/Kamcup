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
        Schema::table('feeds', function (Blueprint $table) {
            if (Schema::hasColumn('feeds', 'meet_date')) {
                $table->dateTime('meet_date')->nullable()->change();
            } else {
                $table->dateTime('meet_date')->nullable()->after('meet_location');
            }

            $table->decimal('meet_duration', 5, 2)->nullable()->after('meet_date');
            $table->decimal('meet_fee', 10, 2)->default(0)->nullable()->after('meet_duration');
            $table->string('meet_gender', 10)->default('all')->nullable()->after('meet_fee');
            $table->string('meet_age_category', 10)->default('all')->nullable()->after('meet_gender');
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
            $table->dropColumn(['meet_duration', 'meet_fee', 'meet_gender', 'meet_age_category']);
        });
    }
};
