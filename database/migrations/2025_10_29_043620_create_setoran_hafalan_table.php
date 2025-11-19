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
        Schema::create('setoran_hafalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->foreignId('ustadz_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('tanggal_setoran');
            $table->integer('juz')->nullable();
            $table->string('surah', 50);
            $table->integer('ayat_mulai');
            $table->integer('ayat_selesai');
            $table->decimal('jumlah_halaman', 4, 2)->nullable();
            $table->enum('jenis_setoran', ['ziyadah', 'murojaah']);
            $table->enum('penilaian', ['lancar', 'kurang_lancar', 'terbata']);
            $table->integer('nilai_angka')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->enum('status_setoran', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->index(['santri_id', 'tanggal_setoran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_hafalan');
    }
};
