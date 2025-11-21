<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sponsor_tournament')) {
            Schema::create('sponsor_tournament', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
                $table->foreignId('sponsor_id')->constrained('sponsors')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsor_tournament');
    }
};