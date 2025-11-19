<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Notification;

#[Layout('layouts.guest')]
class Notifikasi extends Component
{
    public $notifications = [];
    public $notifCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    // Ambil notifikasi + hitung yang belum dibaca
    public function loadNotifications()
    {
        $this->notifications = Notification::latest()->get();
        $this->notifCount = Notification::where('is_read', 0)->count();
    }

    // Konfirmasi registrasi
    public function konfirmasiRegistrasi($userId, $notifId)
    {
        User::where('id', $userId)->update(['is_active' => true]);

        Notification::where('id', $notifId)->update([
            'is_read' => 1
        ]);

        $this->loadNotifications();
    }

    // Tolak registrasi
    public function tolakRegistrasi($userId, $notifId)
    {
        User::where('id', $userId)->delete();

        Notification::where('id', $notifId)->update([
            'is_read' => 1
        ]);

        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.admin.notifikasi', [
            'notifications' => $this->notifications,
            'notifCount' => $this->notifCount
        ]);
    }
}
