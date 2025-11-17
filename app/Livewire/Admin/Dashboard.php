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

#[Layout('layouts.guest')]
class Dashboard extends Component
{
    public $totalSantri;
    public $totalHafal30Juz;
    public $pemasukanBulanIni;
    public $setoranHariIni;

    public $hafalanRange = 'Bulan Ini';
    public $keuanganYear = '2025';

    public function mount()
    {
        $this->loadStatistics();
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
    // 🔥 Data Chart Hafalan
    // ================================
    public function getHafalanData()
{
    $result = ProgressHafalan::join('santri', 'progress_hafalan.santri_id', '=', 'santri.id')
        ->selectRaw('santri.kelas as kelas, AVG(progress_hafalan.total_juz) as rata')
        ->groupBy('santri.kelas')
        ->orderBy('santri.kelas', 'asc')
        ->get();

    // Label kelas (ex: 1A, 1B, 2A...)
    $labels = $result->pluck('kelas')->toArray();

    // Rata-rata juz per kelas
    $data = $result->pluck('rata')->toArray();

    // Jika database kosong → tetap tampil garis chart
    if (empty($data)) {
        $data = [null];
        $labels = ['Belum Ada Data'];
    }

    return [
        "labels" => $labels,
        "data" => $data,
    ];
}


public function getKeuanganData()
{
    $labels = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

    // Ambil pemasukan dalam setahun (jenis_transaksi = deposit)
    $raw = TransaksiJajan::whereYear('tanggal_transaksi', $this->keuanganYear)
        ->where('jenis_transaksi', 'deposit')
        ->selectRaw('MONTH(tanggal_transaksi) as bulan, SUM(nominal) as total')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->pluck('total', 'bulan') // hasil: [1 => 25000, 3 => 10000]
        ->toArray();

    // Susun nilai sesuai urutan bulan
    $data = [];
    for ($i = 1; $i <= 12; $i++) {
        $data[] = $raw[$i] ?? null;   // null = chart tampil garis kosong
    }

    return [
        "labels" => $labels,
        "data" => $data,
    ];
}



    // ================================
    // 🔥 Trigger Chart Update JS
    // ================================
    public function updatedHafalanRange()
    {
        $this->dispatch('updateHafalanChart', $this->getHafalanData());
    }

    public function updatedKeuanganYear()
    {
        $this->dispatch('updateKeuanganChart', $this->getKeuanganData());
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'recentActivities' => LogAktivitas::with('user')->latest()->take(10)->get(),
            'topSantri'        => ProgressHafalan::with('santri')->orderByDesc('total_juz')->take(5)->get(),
        ]);
    }
}
