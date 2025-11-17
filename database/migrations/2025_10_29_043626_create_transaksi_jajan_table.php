<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_jajan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');

            $table->dateTime('tanggal_transaksi')->useCurrent();
            $table->enum('jenis_transaksi', ['deposit', 'penarikan', 'pembelian']);
            $table->decimal('nominal', 12, 2);

            // ✅ Perbaikan di sini
            $table->decimal('saldo_sebelum', 12, 2)->default(0);
            $table->decimal('saldo_sesudah', 12, 2)->default(0);

            $table->text('keterangan')->nullable();
            $table->string('no_bukti', 50)->unique()->nullable();
            $table->timestamps();

            $table->index(['santri_id', 'tanggal_transaksi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_jajan');
    }
};
