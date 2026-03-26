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
            // Add all recurring fields that are missing
            $table->boolean('is_recurring')->default(false)->nullable();
            $table->string('recurrence_pattern', 20)->nullable();
            $table->string('recurrence_days')->nullable(); // Comma-separated days for weekly
            $table->date('recurrence_end_date')->nullable();
            $table->integer('recurrence_day_of_month')->nullable(); // Day of month for monthly
            $table->integer('recurrence_count')->default(0)->nullable();
            $table->time('meet_time')->nullable(); // Time for recurring agendas
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
            $table->dropColumn([
                'is_recurring', 
                'recurrence_pattern', 
                'recurrence_days', 
                'recurrence_end_date',
                'recurrence_day_of_month', 
                'recurrence_count',
                'meet_time'
            ]);
        });
    }
};
