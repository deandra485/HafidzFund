<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saldo_jajan', function (Blueprint $table) {
            $table->id();

            // Relasi ke santri (satu santri hanya punya satu saldo)
            $table->foreignId('santri_id')
                ->constrained('santri')
                ->onDelete('cascade')
                ->unique();

            // Informasi saldo
            $table->decimal('saldo_tersedia', 12, 2)->default(0);
            $table->decimal('total_deposit', 12, 2)->default(0);
            $table->decimal('total_pengeluaran', 12, 2)->default(0);

            // Audit log opsional
            $table->timestamp('last_updated_at')->nullable(); // ✅ cukup ini

            $table->timestamps();

            // Index tambahan
            $table->index(['santri_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saldo_jajan');
    }
};

