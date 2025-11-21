<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tournaments')) {
            Schema::create('tournaments', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255);
                $table->string('slug', 255)->nullable();
                $table->string('thumbnail', 255);
                $table->date('registration_start');
                $table->date('registration_end');
                $table->enum('gender_category', ['male', 'female', 'mixed']);
                $table->string('location', 255);
                $table->decimal('registration_fee', 10, 2);
                $table->decimal('prize_total', 10, 2);
                $table->string('contact_person', 255);
                $table->unsignedInteger('max_participants')->nullable();
                $table->enum('status', ['registration', 'ongoing', 'completed']);
                $table->dateTime('event_start')->nullable();
                $table->dateTime('event_end')->nullable();
                $table->enum('visibility_status', ['Draft', 'Published'])->default('Draft');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};