<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KehadiranSetoran extends Model
{
    protected $table = 'kehadiran_setoran';

    protected $fillable = [
        'santri_id',
        'tanggal',

        // Kehadiran setiap sesi
        'halaqoh_pagi',
        'halaqoh_siang',
        'halaqoh_sore',
        'halaqoh_malam',

        // Tambahan progress hafalan
        'juz_terakhir',
        'surah_terakhir',

        'catatan',
        'updated_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Daftar sesi kehadiran
    public static function getSesiList(): array
    {
        return [
            'halaqoh_pagi' => [
                'label' => 'Halaqoh Pagi',
                'icon' => '',
                'waktu' => '07:00',
            ],
            'halaqoh_siang' => [
                'label' => 'Halaqoh Siang',
                'icon' => '',
                'waktu' => '12:30',
            ],
            'halaqoh_sore' => [
                'label' => 'Halaqoh Sore',
                'icon' => '',
                'waktu' => '15:30',
            ],
            'halaqoh_malam' => [
                'label' => 'Halaqoh Malam/Tahfidz',
                'icon' => '',
                'waktu' => '19:00',
            ],
        ];
    }

    // Hitung persentase kehadiran harian
    public function getPersentaseKehadiranAttribute(): float
    {
        $sesiList = array_keys(self::getSesiList());
        $hadir = 0;
        
        foreach ($sesiList as $sesi) {
            if ($this->$sesi === 'hadir') {
                $hadir++;
            }
        }
        
        return round(($hadir / count($sesiList)) * 100, 1);
    }

    // Status kehadiran harian
    public function getStatusHarianAttribute(): string
    {
        $persentase = $this->persentase_kehadiran;
        
        if ($persentase >= 80) return 'Sangat Baik';
        if ($persentase >= 60) return 'Baik';
        if ($persentase >= 40) return 'Cukup';
        return 'Perlu Perhatian';
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
