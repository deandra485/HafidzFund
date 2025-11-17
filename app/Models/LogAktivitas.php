<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'aktivitas',
        'module',
        'detail',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($aktivitas, $module = null, $detail = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'aktivitas' => $aktivitas,
            'module' => $module,
            'detail' => $detail,
        ]);
    }
}