<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalSetoran extends Model
{
    use HasFactory;

    protected $table = 'jadwal_setoran';

    protected $fillable = [
        'santri_id',
        'ustadz_id',
        'tanggal',
        'hari_setoran',
        'waktu_mulai',
        'is_active',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function ustadz()
    {
        return $this->belongsTo(User::class, 'ustadz_id');
    }

    public function scopeHariIni($query)
    {
        $hari = strtolower(now()->locale('id')->dayName);
        return $query->where('hari_setoran', $hari);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
