<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tournament_host_requests')) {
            Schema::create('tournament_host_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('responsible_name', 255);
                $table->string('email', 255);
                $table->string('phone', 255);
                $table->string('tournament_title', 255);
                $table->string('venue_name', 255);
                $table->text('venue_address');
                $table->integer('estimated_capacity');
                $table->date('proposed_date');
                $table->text('available_facilities');
                $table->text('notes')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('rejection_reason')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_host_requests');
    }
};