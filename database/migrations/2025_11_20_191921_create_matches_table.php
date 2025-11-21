<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * URUTAN MIGRATION:
     * 1. users (sudah ada dari Laravel default)
     * 2. tournaments
     * 3. teams
     * 4. tournament_registrations
     * 5. matches (FILE INI)
     */
    public function up(): void
    {
        // Pastikan tabel dependencies sudah ada
        if (!Schema::hasTable('tournaments')) {
            throw new \Exception('Tabel tournaments belum ada! Jalankan migration tournaments terlebih dahulu.');
        }
        
        if (!Schema::hasTable('teams')) {
            throw new \Exception('Tabel teams belum ada! Jalankan migration teams terlebih dahulu.');
        }
        
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            
            // ========================================
            // FOREIGN KEY: TOURNAMENT
            // ========================================
            $table->unsignedBigInteger('tournament_id')->comment('ID Tournament yang diikuti');
            $table->foreign('tournament_id', 'fk_matches_tournament')
                  ->references('id')
                  ->on('tournaments')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            // ========================================
            // FOREIGN KEY: TEAM 1
            // ========================================
            $table->unsignedBigInteger('team1_id')->nullable()->comment('ID Team pertama (Home/Tim 1)');
            $table->foreign('team1_id', 'fk_matches_team1')
                  ->references('id')
                  ->on('teams')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            
            // ========================================
            // FOREIGN KEY: TEAM 2
            // ========================================
            $table->unsignedBigInteger('team2_id')->nullable()->comment('ID Team kedua (Away/Tim 2)');
            $table->foreign('team2_id', 'fk_matches_team2')
                  ->references('id')
                  ->on('teams')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            
            // ========================================
            // MATCH DETAILS
            // ========================================
            $table->string('stage', 100)->nullable()->comment('Tahapan: Penyisihan, Perempat Final, Semi Final, Final');
            $table->dateTime('match_datetime')->comment('Tanggal dan waktu pertandingan');
            $table->enum('status', ['scheduled', 'in-progress', 'completed', 'cancelled'])
                  ->default('scheduled')
                  ->comment('Status pertandingan');
            $table->string('location')->nullable()->comment('Lokasi pertandingan');
            $table->string('format', 50)->nullable()->comment('Format pertandingan: Best of 3, Best of 5, dll');
            
            // ========================================
            // SCORES
            // ========================================
            $table->integer('team1_score')->nullable()->default(0)->comment('Skor Team 1');
            $table->integer('team2_score')->nullable()->default(0)->comment('Skor Team 2');
            
            // ========================================
            // FOREIGN KEY: WINNER
            // ========================================
            $table->unsignedBigInteger('winner_id')->nullable()->comment('ID Team pemenang');
            $table->foreign('winner_id', 'fk_matches_winner')
                  ->references('id')
                  ->on('teams')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            
            // ========================================
            // FOREIGN KEY: LOSER
            // ========================================
            $table->unsignedBigInteger('loser_id')->nullable()->comment('ID Team yang kalah');
            $table->foreign('loser_id', 'fk_matches_loser')
                  ->references('id')
                  ->on('teams')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            
            // ========================================
            // TIMESTAMPS
            // ========================================
            $table->timestamps();
            
            // ========================================
            // INDEXES untuk performa query
            // ========================================
            $table->index('tournament_id', 'idx_matches_tournament');
            $table->index('team1_id', 'idx_matches_team1');
            $table->index('team2_id', 'idx_matches_team2');
            $table->index('status', 'idx_matches_status');
            $table->index('match_datetime', 'idx_matches_datetime');
            $table->index(['tournament_id', 'status'], 'idx_matches_tournament_status');
        });
        
        // Log untuk memastikan migration berhasil
        \Log::info('Migration matches table created successfully');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};