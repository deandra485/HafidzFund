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
        Schema::create('progress_hafalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->unique()->constrained('santri')->onDelete('cascade');
            $table->decimal('total_juz', 4, 2)->default(0);
            $table->decimal('total_halaman', 6, 2)->default(0);
            $table->integer('juz_terakhir')->nullable();
            $table->string('surah_terakhir', 50)->nullable();
            $table->decimal('persentase_hafalan', 5, 2)->default(0);
            $table->date('last_setoran_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_hafalan');
    }
};
