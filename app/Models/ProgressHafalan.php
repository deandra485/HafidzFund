<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressHafalan extends Model
{
    use HasFactory;

    protected $table = 'progress_hafalan';

    protected $fillable = [
        'santri_id',
        'total_juz',
        'total_halaman',
        'persentase_hafalan',
        'last_setoran_date',
    ];

    protected $casts = [
        'total_juz' => 'decimal:2',
        'total_halaman' => 'decimal:2',
        'persentase_hafalan' => 'decimal:2',
        'last_setoran_date' => 'date',
    ];

    // Relationships
   public function santri()
    {
        $instance = $this->belongsTo(\App\Models\Santri::class, 'santri_id', 'id');
        $instance->getRelated()->setTable('santri');
        return $instance;
    }


    // Helper methods
    public function updateProgress($jumlahHalaman)
    {
        $this->total_halaman += $jumlahHalaman;
        $this->total_juz = round($this->total_halaman / 20, 2); // Asumsi 20 halaman per juz
        $this->persentase_hafalan = round(($this->total_halaman / 604) * 100, 2); // 604 total halaman Quran
        $this->last_setoran_date = now();
        $this->save();
    }
}