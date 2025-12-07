<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadiran_setoran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->date('tanggal');

            $table->integer('juz_terakhir')->nullable();
            $table->integer('surah_terakhir')->nullable();
            
            // Multi-sesi kehadiran
            $table->enum('halaqoh_pagi', ['hadir', 'izin', 'sakit', 'alpa', 'belum'])->default('belum');
            $table->enum('halaqoh_siang', ['hadir', 'izin', 'sakit', 'alpa', 'belum'])->default('belum');
            $table->enum('halaqoh_sore', ['hadir', 'izin', 'sakit', 'alpa', 'belum'])->default('belum');
            $table->enum('halaqoh_malam', ['hadir', 'izin', 'sakit', 'alpa', 'belum'])->default('belum');
            
            $table->text('catatan')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['santri_id', 'tanggal']);
            $table->unique(['santri_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::table('kehadiran_setoran', function (Blueprint $table) {
            $table->dropColumn(['juz_terakhir', 'surah_terakhir']);
        });
    }
};