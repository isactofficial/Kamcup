<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('visits')) {
            Schema::create('visits', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address', 255);
                $table->string('user_agent', 255)->nullable();
                $table->text('url')->nullable();
                $table->timestamp('visited_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};