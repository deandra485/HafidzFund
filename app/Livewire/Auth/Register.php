<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.auth')]
class Register extends Component
{
    public $username;
    public $password;
    public $password_confirmation;
    public $role = 'ustadz';
    public $nama_lengkap;
    public $email;
    public $no_telp;

    protected $rules = [
        'username' => 'required|min:5|max:50|unique:users,username|alpha_dash',
        'password' => 'required|min:6|confirmed',
        'role' => 'required|in:admin,ustadz',
        'nama_lengkap' => 'required|max:100',
        'email' => 'required|email|unique:users,email',
        'no_telp' => 'nullable|max:20',
    ];

    public function register()
    {
        $this->validate();

        try {

            // BUAT USER BARU — status pending
            $user = User::create([
                'name' => $this->nama_lengkap,
                'username' => $this->username,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'email' => $this->email,
                'no_telp' => $this->no_telp,
                'whatsapp' => $this->no_telp,
                'nama_lengkap' => $this->nama_lengkap,
                'is_active' => false, // pengguna harus dikonfirmasi admin
            ]);

            // ==============================
            // 🔔 BUAT NOTIFIKASI UNTUK ADMIN
            // ==============================

            Notification::create([
                'user_id' => 1, // ID admin (ubah jika perlu)
                'title' => 'Pengguna baru menunggu konfirmasi: ' . $user->name,
                'type' => 'registrasi',
                'related_id' => $user->id,
                'is_read' => false,
            ]);

            session()->flash('message', 'Registrasi berhasil! Tunggu konfirmasi dari admin.');

            return redirect()->route('auth.login');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
