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
            $table->enum('status_kehadiran', ['hadir', 'izin', 'sakit', 'alpa']);
            $table->enum('status_setoran', ['belum', 'antri', 'proses', 'selesai'])->default('belum');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['santri_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_setoran');
    }
};
