<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'whatsapp',
        'foto',
        'alamat',
        'nama_lengkap',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        // Relationships
    public function santriBinaan()
    {
        return $this->hasMany(Santri::class, 'ustadz_pembimbing_id');
    }

    public function setoranHafalan()
    {
        return $this->hasMany(SetoranHafalan::class, 'ustadz_id');
    }

    public function jadwalSetoran()
    {
        return $this->hasMany(JadwalSetoran::class, 'ustadz_id');
    }

    // Scopes
    public function scopeUstadz($query)
    {
        return $query->where('role', 'ustadz');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUstadz()
    {
        return $this->role === 'ustadz';
    }
}


    // 1. Cara pertama generate data menggunakan booted
    // protected static function booted()
    // {
    //     static::created(function ($user) {
    //         if (empty($user->username)) {

    //             $base = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $user->name));
    //             $username = $base . mt_rand(1, 999);

    //             while (User::where('username', $username)->exists()) {
    //                 $username = $base . mt_rand(1, 999);
    //             }

    //             $user->username = $username;
    //             $user->save();
    //         }
    //     });
    // }

    // 2. Cara kedua generate data menggunakan mutator
    // public function username(): Attribute
    // {
    //     return Attribute::make(
    //         set: function ($value) {
    //             $base = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $value));
    //             $username = $base . "-IDN-" . mt_rand(1, 999);

    //             while (User::where('username', $username)->exists()) {
    //                 $username = $base . mt_rand(1, 999);
    //             }

    //             return $username;
    //         }
    //     );
    // }
    

