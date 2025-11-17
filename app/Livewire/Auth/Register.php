<?php

// app/Livewire/Auth/Register.php
namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\LogAktivitas;
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

    protected $messages = [
        'username.required' => 'Username harus diisi',
        'username.unique' => 'Username sudah digunakan',
        'username.alpha_dash' => 'Username hanya boleh huruf, angka, dash dan underscore',
        'password.required' => 'Password harus diisi',
        'password.min' => 'Password minimal 6 karakter',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
        'nama_lengkap.required' => 'Nama lengkap harus diisi',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah terdaftar',
    ];

    public function register()
    {
        $this->validate();

        try {
            $user = User::create([
            'name' => $this->nama_lengkap, // ubah ke 'name'
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'email' => $this->email,
            'no_telp' => $this->no_telp,
            'whatsapp' => $this->no_telp,
            'nama_lengkap' => $this->nama_lengkap,
            'is_active' => true,
        ]);

            session()->flash('message', 'Registrasi berhasil! Tunggu aktivasi dari admin.');
            
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