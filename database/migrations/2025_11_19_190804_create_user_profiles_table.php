<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_profiles')) {
            Schema::create('user_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('profile_photo', 255)->nullable();
                $table->string('name', 255)->nullable();
                $table->string('email', 255)->nullable();
                $table->date('birthdate')->nullable();
                $table->enum('gender', ['male', 'female'])->nullable();
                $table->string('phone_number', 255)->nullable();
                $table->string('social_media', 255)->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};