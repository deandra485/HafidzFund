<?php

namespace App\Livewire\Ustadz;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.guest-user')]
class ProfileUstadz extends Component
{
    use WithFileUploads;

    public $name;
    public $username;
    public $email;
    public $whatsapp;
    public $alamat;
    public $nama_lengkap;
    public $foto;
    public $new_foto;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public $isEditing = false;
    public $isChangingPassword = false;

    // RULE UTAMA
    protected $rules = [
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255',
        'email' => 'required|email',
        'whatsapp' => 'nullable|string|max:20',
        'alamat' => 'nullable|string',
        'nama_lengkap' => 'nullable|string|max:255',
        'new_foto' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->whatsapp = $user->whatsapp;
        $this->alamat = $user->alamat;
        $this->nama_lengkap = $user->nama_lengkap;
        $this->foto = $user->foto;
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;

        if (!$this->isEditing) {
            $this->mount();  
            $this->new_foto = null;
            $this->resetValidation();
        }
    }

    public function togglePasswordChange()
    {
        $this->isChangingPassword = !$this->isChangingPassword;

        if (!$this->isChangingPassword) {
            $this->reset(['current_password','new_password','new_password_confirmation']);
            $this->resetValidation();
        }
    }

    public function updateProfile()
    {
        $user = Auth::user();

        // VALIDASI UNIK
        $this->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
        ] + $this->rules);

        // Upload foto baru jika ada
        if ($this->new_foto) {

            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $path = $this->new_foto->store('profil', 'public');
            $user->foto = $path;
            $this->foto = $path;
        }

        $user->update([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'whatsapp' => $this->whatsapp,
            'alamat' => $this->alamat,
            'nama_lengkap' => $this->nama_lengkap,
            'foto' => $user->foto,
        ]);

        $this->isEditing = false;

        session()->flash('message', 'Profil berhasil diperbarui!');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini tidak sesuai.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password','new_password','new_password_confirmation']);
        $this->isChangingPassword = false;

        session()->flash('message', 'Password berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.ustadz.profile-ustadz');
    }
}
