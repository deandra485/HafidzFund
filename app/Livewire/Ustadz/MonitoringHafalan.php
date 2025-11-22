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
    public $filterSantri = '';
    public $periodeLabel = '';

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
            'custom' => now()->subDays(7), // default
            default  => now()->subDays(7),
        };

        /*
        |--------------------------------------------------------------------------
        | STATISTIK SETORAN
        |--------------------------------------------------------------------------
        */
        $this->stat = [
            'total'         => SetoranHafalan::whereIn('santri_id', $santriIds)->where('created_at', '>=', $dateQuery)->count(),
            'lancar'        => SetoranHafalan::whereIn('santri_id', $santriIds)->where('status_setoran', 'diterima')->where('created_at', '>=', $dateQuery)->count(),
            'kurang_lancar' => SetoranHafalan::whereIn('santri_id', $santriIds)->where('status_setoran', 'ditolak')->where('created_at', '>=', $dateQuery)->count(),
            'terbata'       => SetoranHafalan::whereIn('santri_id', $santriIds)->where('status_setoran', 'pending')->where('created_at', '>=', $dateQuery)->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | GRAFIK PER SANTRI
        |--------------------------------------------------------------------------
        */
        $santriList = Santri::where('ustadz_pembimbing_id', $ustadzId)->get();

        $labels = [];
        $data = [];

        foreach ($santriList as $santri) {
            // Hitung setoran per santri
            $totalNilai = SetoranHafalan::where('santri_id', $santri->id)
                ->where('created_at', '>=', $dateQuery)
                ->sum('nilai_angka');


            // Masukkan nama santri + jumlah setoran
            $labels[] = $santri->nama_lengkap;
            $data[] = $totalNilai;
        }

        // kirim ke JS chart
        $this->dispatch('updateChart', labels: $labels, data: $data);
    }

    public function render()
    {
        $santriList = Santri::where('ustadz_pembimbing_id', Auth::id())->get();

        return view('livewire.ustadz.monitoring-hafalan', compact('santriList'));
    }
}
