<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('target_hafalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->integer('target_halaman')->nullable();
            $table->decimal('target_harian', 4, 2)->nullable();
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_hafalan');
    }
};
