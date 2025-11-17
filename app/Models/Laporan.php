<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'tanggal',
        'gambar',
        'status', 
        'respon',
    ];

    protected function gambar(): Attribute
    {
        return Attribute::make(
            get: fn ($gambar) => $gambar ? asset('/storage/laporan/' . $gambar) : asset('/images/no-image.png'),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
