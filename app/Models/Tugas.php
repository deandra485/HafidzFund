<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    /** @use HasFactory<\Database\Factories\TugasFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tugas',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
