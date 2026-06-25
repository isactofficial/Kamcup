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
        if (!Schema::hasTable('feeds')) {
            return;
        }

        Schema::table('feeds', function (Blueprint $table) {
            if (!Schema::hasColumn('feeds', 'meet_date')) {
                $table->dateTime('meet_date')->nullable()->after('meet_location');
            } else {
                $table->dateTime('meet_date')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('feeds')) {
            return;
        }

        Schema::table('feeds', function (Blueprint $table) {
            if (Schema::hasColumn('feeds', 'meet_date')) {
                $table->dropColumn('meet_date');
            }
        });
    }
};

