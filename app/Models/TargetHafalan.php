<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetHafalan extends Model
{
    use HasFactory;

    protected $table = 'target_hafalan';

    protected $fillable = [
        'santri_id',
        'periode_mulai',
        'periode_selesai',
        'target_halaman',
        'target_harian',
        'status',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'target_harian' => 'decimal:2',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
