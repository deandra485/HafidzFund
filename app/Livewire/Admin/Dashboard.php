<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Models\Santri;
use App\Models\SetoranHafalan;
use App\Models\ProgressHafalan;
use App\Models\LogAktivitas;
use App\Models\TransaksiJajan;
use App\Models\Notification; // ⬅️ Tambah ini
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.guest')]
class Dashboard extends Component
{
    public $totalSantri;
    public $totalHafal30Juz;
    public $pemasukanBulanIni;
    public $setoranHariIni;

    public $hafalanRange = 'Bulan Ini';
    public $keuanganYear = '2025';

    public $unreadNotifikasi = 0; // ⬅️ Tambah ini

    public function mount()
    {
        $this->loadStatistics();
        $this->loadUnreadNotifications(); // ⬅️ Tambah ini

        // Chart hafalan
        $this->dispatch('updateHafalanChart', data: $this->getHafalanData());

        // Chart keuangan
        $this->dispatch('updateKeuanganChart', data: $this->getKeuanganData());
    }

    // ================================
    // 🔥 JUMLAH NOTIFIKASI BELUM DIBACA
    // ================================
    public function loadUnreadNotifications()
    {
        $this->unreadNotifikasi = Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->count();
    }

    #[On('refresh-notification')]
    public function refreshNotifications()
    {
        $this->loadUnreadNotifications();

        // Jika mau update badge realtime via JS:
        $this->dispatch('updateNotificationBadge', count: $this->unreadNotifikasi);
    }

    #[On('refresh-stats')]
    public function loadStatistics()
    {
        $this->totalSantri = Santri::where('status', 'aktif')->count();
        $this->totalHafal30Juz = ProgressHafalan::where('total_juz', '>=', 30)->count();

        $this->pemasukanBulanIni = TransaksiJajan::whereMonth('tanggal_transaksi', now()->month)
            ->where('jenis_transaksi', 'deposit')
            ->sum('nominal');

        $this->setoranHariIni = SetoranHafalan::whereDate('created_at', today())->count();
    }

    // ================================
    // DATA CHART HAFALAN
    // ================================
    public function getHafalanData()
    {
        $result = ProgressHafalan::join('santri', 'progress_hafalan.santri_id', '=', 'santri.id')
            ->selectRaw('santri.kelas as kelas, AVG(progress_hafalan.total_juz) as rata')
            ->groupBy('santri.kelas')
            ->orderBy('santri.kelas', 'asc')
            ->get();

        $labels = $result->pluck('kelas')->toArray();
        $data   = $result->pluck('rata')->map(fn ($v) => round($v, 2))->toArray();

        if (empty($data)) {
            $labels = ['Belum Ada Data'];
            $data   = [0];
        }

        return [
            'labels' => $labels,
            'data'   => $data,
        ];
    }

    // ================================
    // DATA CHART KEUANGAN
    // ================================
    public function getKeuanganData()
    {
        $labels = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

        $raw = TransaksiJajan::whereYear('tanggal_transaksi', $this->keuanganYear)
            ->where('jenis_transaksi', 'deposit')
            ->selectRaw('MONTH(tanggal_transaksi) as bulan, SUM(nominal) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = $raw[$i] ?? null;
        }

        return [
            'labels' => $labels,
            'data'   => $data,
        ];
    }

    public function updatedHafalanRange()
    {
        $this->dispatch('updateHafalanChart', data: $this->getHafalanData());
    }

    public function updatedKeuanganYear()
    {
        $this->dispatch('updateKeuanganChart', data: $this->getKeuanganData());
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'recentActivities' => LogAktivitas::with('user')->latest()->take(10)->get(),
            'topSantri'        => ProgressHafalan::with('santri')->orderByDesc('total_juz')->take(5)->get(),
        ]);
    }
}
