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
        Schema::table('donations', function (Blueprint $table) {
            // Hapus kolom lama yang tidak digunakan
            $table->dropColumn([
                'name_brand',
                'phone_whatsapp',
                'event_name',
                'donation_type',
                'sponsor_type',
                'benefits'
            ]);
        });

        Schema::table('donations', function (Blueprint $table) {
            // Tambah kolom baru setelah user_id
            $table->string('donor_name')->after('user_id');
            $table->decimal('amount', 10, 2)->after('donor_name');
            $table->string('proof_image')->nullable()->after('amount');
            $table->string('foto_donatur')->nullable()->after('proof_image');
            
            // Kolom message dan status sudah ada, tidak perlu ditambah lagi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn([
                'donor_name',
                'amount',
                'proof_image',
                'foto_donatur'
            ]);
        });

        Schema::table('donations', function (Blueprint $table) {
            // Kembalikan kolom lama
            $table->string('name_brand')->after('user_id');
            $table->string('phone_whatsapp')->after('email');
            $table->string('event_name')->after('phone_whatsapp');
            $table->string('donation_type')->after('event_name');
            $table->string('sponsor_type')->nullable()->after('donation_type');
            $table->text('benefits')->nullable()->after('sponsor_type');
        });
    }
};