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
        if (!Schema::hasTable('galleries')) {
            Schema::create('galleries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('author', 255)->nullable();
                $table->string('title', 255);
                $table->string('slug', 255)->nullable();
                $table->string('tournament_name', 255)->nullable();
                $table->string('thumbnail', 255)->nullable();
                $table->string('video_link', 255)->nullable();
                $table->enum('status', ['Draft', 'Published'])->default('Draft');
                $table->unsignedBigInteger('views')->default(0);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};