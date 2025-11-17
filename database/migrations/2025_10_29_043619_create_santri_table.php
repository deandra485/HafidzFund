<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('santri', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 20)->unique();
            $table->string('nama_lengkap', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat')->nullable();
            $table->string('kelas', 20)->nullable();
            $table->string('angkatan', 10)->nullable();
            $table->enum('status', ['aktif', 'alumni', 'keluar'])->default('aktif');
            $table->foreignId('ustadz_pembimbing_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('no_telp_wali', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santri');
    }
};
