<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sponsors')) {
            Schema::create('sponsors', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255);
                $table->string('logo', 255);
                $table->enum('sponsor_size', ['xxl', 'xl', 'l', 'm', 's']);
                $table->unsignedInteger('order_number')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsors');
    }
};