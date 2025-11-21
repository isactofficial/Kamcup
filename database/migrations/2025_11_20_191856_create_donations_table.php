<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('donations')) {
            Schema::create('donations', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->nullable();
                $table->string('donor_name', 255)->comment('Nama pengirim donasi');
                $table->decimal('amount', 10, 2)->comment('Jumlah donasi');
                $table->string('proof_image', 255)->nullable()->comment('Bukti transfer/foto');
                $table->string('foto_donatur', 255)->nullable();
                $table->string('email', 255)->nullable();
                $table->text('message')->nullable()->comment('Pesan opsional');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};