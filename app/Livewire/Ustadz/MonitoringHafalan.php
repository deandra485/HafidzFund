<?php

namespace App\Livewire\Ustadz;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Santri;
use App\Models\SetoranHafalan;

#[Layout('layouts.guest-user')]
class MonitoringHafalan extends Component
{
    public $periode = 'minggu';
    public $filterSantri = '';   // menyesuaikan blade
    public $periodeLabel = '';

    // Chart Data
    public $labels = [];
    public $data = [];

    // Statistik
    public $stat = [];

    public function mount()
    {
        $this->generateData();
    }

    public function applyFilter()
    {
        $this->generateData();
    }

    public function updatedPeriode()
    {
        $this->generateData();
    }

    public function updatedFilterSantri()
    {
        $this->generateData();
    }

    public function generateData()
    {
        $ustadzId = Auth::id();

        // Ambil santri dibimbing ustadz
        $santriQuery = Santri::where('ustadz_pembimbing_id', $ustadzId);

        if ($this->filterSantri !== '') {
            $santriQuery->where('id', $this->filterSantri);
        }

        $santriIds = $santriQuery->pluck('id');

        // Periode Label
        $this->periodeLabel = match ($this->periode) {
            'minggu' => '7 Hari Terakhir',
            'bulan'  => '30 Hari Terakhir',
            'custom' => 'Periode Custom',
            default  => '7 Hari Terakhir',
        };

        // Periode Query
        $dateQuery = match ($this->periode) {
            'minggu' => now()->subDays(7),
            'bulan'  => now()->subDays(30),
            'custom' => now()->subDays(7), // aman dulu
            default  => now()->subDays(7),
        };

        // Statistik
        $statTotal = SetoranHafalan::whereIn('santri_id', $santriIds)
            ->where('created_at', '>=', $dateQuery)
            ->count();

        $statLancar = SetoranHafalan::whereIn('santri_id', $santriIds)
            ->where('status_setoran', 'diterima')
            ->where('created_at', '>=', $dateQuery)
            ->count();

        $statKurang = SetoranHafalan::whereIn('santri_id', $santriIds)
            ->where('status_setoran', 'ditolak')
            ->where('created_at', '>=', $dateQuery)
            ->count();

        $statTerbata = SetoranHafalan::whereIn('santri_id', $santriIds)
            ->where('status_setoran', 'pending')
            ->where('created_at', '>=', $dateQuery)
            ->count();

        // Masukkan ke array untuk Blade
        $this->stat = [
            'total'         => $statTotal,
            'lancar'        => $statLancar,
            'kurang_lancar' => $statKurang,
            'terbata'       => $statTerbata,
        ];

        // Grafik
        $records = SetoranHafalan::selectRaw('santri_id, COUNT(*) as total')
            ->whereIn('santri_id', $santriIds)
            ->where('created_at', '>=', $dateQuery)
            ->groupBy('santri_id')
            ->get();

        $this->labels = $records->map(fn ($r) => Santri::find($r->santri_id)->nama_lengkap)->toArray();
        $this->data   = $records->pluck('total')->toArray();

        // kirim event ke JS
        $this->dispatch('updateChart', labels: $this->labels, data: $this->data);
    }

    public function render()
    {
        $santriList = Santri::where('ustadz_pembimbing_id', Auth::id())->get();

        return view('livewire.ustadz.monitoring-hafalan', compact('santriList'));
    }
}
