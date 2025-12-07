<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santri';

    protected $fillable = [
        'nis',
        'nama_lengkap',
        'jenis_kelamin',
        'alamat',
        'kelas',
        'angkatan',
        'status',
        'ustadz_pembimbing_id',
        'no_telp_wali',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    protected static function booted()
    {
        static::created(function ($santri) {
            // Cek dulu apakah progress sudah ada
            if (!$santri->progressHafalan()->exists()) {
                $santri->progressHafalan()->create([
                    'total_juz' => 0,
                    'total_halaman' => 0,
                    'persentase_hafalan' => 0,
                    'last_setoran_date' => null,
                ]);
            }
        });
    }

    // Relationships
    public function ustadzPembimbing()
    {
        return $this->belongsTo(User::class, 'ustadz_pembimbing_id');
    }

    public function setoranHafalan()
    {
        return $this->hasMany(SetoranHafalan::class, 'santri_id');
    }

    public function progressHafalan()
    {
        return $this->hasOne(ProgressHafalan::class, 'santri_id');
    }

    public function jadwalSetoran()
    {
        return $this->hasMany(JadwalSetoran::class);
    }


    public function saldoJajan()
    {
        return $this->hasOne(SaldoJajan::class);
    }

    public function transaksiJajan()
    {
        return $this->hasMany(TransaksiJajan::class);
    }

    public function kehadiranSetoran()
    {
        return $this->hasMany(KehadiranSetoran::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByKelas($query, $kelas)
    {
        return $query->where('kelas', $kelas);
    }

    public function scopeByAngkatan($query, $angkatan)
    {
        return $query->where('angkatan', $angkatan);
    }

    // Generate otomatis NIS
    public static function generateNIS()
    {
        $last = self::latest('id')->first();
        $next = $last ? $last->id + 1 : 1;

        return '2025' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    // Accessors
    public function getUmurAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }

    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->nama_lengkap);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }
}