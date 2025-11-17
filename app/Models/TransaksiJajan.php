<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiJajan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_jajan';

    protected $fillable = [
        'santri_id',
        'admin_id',
        'tanggal_transaksi',
        'jenis_transaksi',
        'nominal',
        'saldo_sebelum',
        'saldo_sesudah',
        'keterangan',
        'no_bukti',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'nominal' => 'decimal:2',
        'saldo_sebelum' => 'decimal:2',
        'saldo_sesudah' => 'decimal:2',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            // Auto-generate no_bukti
            $transaksi->no_bukti = 'TRX-' . now()->format('YmdHis') . '-' . rand(1000, 9999);

            // Ambil saldo terakhir santri (jika ada)
            if (is_null($transaksi->saldo_sebelum)) {
                $saldoTerakhir = \App\Models\SaldoJajan::where('santri_id', $transaksi->santri_id)
                    ->value('saldo_tersedia');
                $transaksi->saldo_sebelum = $saldoTerakhir ?? 0;
            }

            // Hitung saldo sesudah otomatis
            if (is_null($transaksi->saldo_sesudah)) {
                $transaksi->saldo_sesudah = $transaksi->jenis_transaksi === 'deposit'
                    ? $transaksi->saldo_sebelum + $transaksi->nominal
                    : $transaksi->saldo_sebelum - $transaksi->nominal;
            }
        });
    }
}
