<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetoranHafalan extends Model
{
    use HasFactory;

    protected $table = 'setoran_hafalan';

    protected $fillable = [
        'santri_id',
        'ustadz_id',
        'tanggal_setoran',
        'juz',
        'surah',
        'ayat_mulai',
        'ayat_selesai',
        'jumlah_halaman',
        'jenis_setoran',
        'penilaian',
        'nilai_angka',
        'catatan',
    ];

    protected $casts = [
        'tanggal_setoran' => 'datetime',
        'jumlah_halaman' => 'decimal:2',
        'nilai_angka' => 'integer',
    ];

    // Relationships
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function ustadz()
    {
        return $this->belongsTo(User::class, 'ustadz_id');
    }

    // Scopes
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_setoran', today());
    }

    public function scopeZiyadah($query)
    {
        return $query->where('jenis_setoran', 'ziyadah');
    }

    public function scopeMurojaah($query)
    {
        return $query->where('jenis_setoran', 'muroja\'ah');
    }

    public function scopeLancar($query)
    {
        return $query->where('penilaian', 'lancar');
    }
}