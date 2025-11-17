<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoJajan extends Model
{
    use HasFactory;

    protected $table = 'saldo_jajan';

    protected $fillable = [
        'santri_id',
        'saldo_tersedia',
        'total_deposit',
        'total_pengeluaran',
        'last_updated_at',
    ];

    protected $casts = [
        'saldo_tersedia' => 'decimal:2',
        'total_deposit' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'last_updated_at' => 'datetime',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
