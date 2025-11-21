<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('gallery_contents')) {
            Schema::create('gallery_contents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('gallery_subtitle_id')->constrained('gallery_subtitles')->onDelete('cascade');
                $table->integer('order_number')->default(1);
                $table->text('content');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_contents');
    }
};