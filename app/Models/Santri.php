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

    // Relationships
    public function ustadzPembimbing()
    {
        return $this->belongsTo(User::class, 'ustadz_pembimbing_id');
    }

    public function setoranHafalan()
    {
        return $this->hasMany(SetoranHafalan::class);
    }

    public function progressHafalan()
    {
        return $this->hasOne(ProgressHafalan::class);
    }

    public function targetHafalan()
    {
        return $this->hasMany(TargetHafalan::class);
    }

    public function jadwalSetoran()
    {
        return $this->hasMany(JadwalSetoran::class);
    }

    public function kehadiranSetoran()
    {
        return $this->hasMany(KehadiranSetoran::class);
    }

    public function ujianHafalan()
    {
        return $this->hasMany(UjianHafalan::class);
    }

    public function saldoJajan()
    {
        return $this->hasOne(SaldoJajan::class);
    }

    public function transaksiJajan()
    {
        return $this->hasMany(TransaksiJajan::class);
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