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
        Schema::create('ujian_hafalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->foreignId('ustadz_penguji_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_ujian');
            $table->enum('jenis_ujian', ['munaqosyah', 'tahfidz', 'tasmi']);
            $table->text('materi_ujian');
            $table->decimal('nilai_angka', 5, 2)->nullable();
            $table->enum('status', ['lulus', 'tidak_lulus', 'mengulang']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_hafalan');
    }
};
