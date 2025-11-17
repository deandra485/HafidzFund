<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_lengkap')->after('role')->nullable();
            $table->string('no_telp')->nullable()->after('email');
            $table->boolean('is_active')->default(1)->after('no_telp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama_lengkap', 'no_telp', 'is_active']);
        });
    }
};

