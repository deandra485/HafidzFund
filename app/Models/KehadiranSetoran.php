<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranSetoran extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kehadiran_setoran';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'santri_id',
        'tanggal',
        'status_kehadiran',
        'status_setoran',
        'keterangan',
    ];

    // Relasi ke model Santri
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
