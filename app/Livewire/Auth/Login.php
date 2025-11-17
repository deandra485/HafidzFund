<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAktivitas;

#[Layout('layouts.auth')]
class Login extends Component
{
    public $username;
    public $password;
    public $remember = false;

    protected $rules = [
        'username' => 'required',
        'password' => 'required',
    ];

    protected $messages = [
        'username.required' => 'Username harus diisi',
        'password.required' => 'Password harus diisi',
    ];

    public function login()
    {
        $this->validate();

        // hanya login kalau user aktif
        if (Auth::attempt([
            'username' => $this->username,
            'password' => $this->password,
            'is_active' => true
        ], $this->remember)) {

            session()->regenerate();
            $user = Auth::user();

            // Catat log aktivitas
            LogAktivitas::log('Login ke sistem', 'Auth');

            // Arahkan ke dashboard sesuai role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->role === 'ustadz') {
                return redirect()->intended(route('ustadz.dashboard'));
            }

            // fallback jika role tidak dikenal
            return redirect()->route('home');
        }

        $this->addError('username', 'Username atau password salah.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
