<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\SetoranHafalan;
use App\Models\Santri;
use App\Models\User;
use App\Models\ProgressHafalan;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;

#[Layout('layouts.guest')]
class LaporanHafalan extends Component
{
    public $periode = 'bulan_ini';
    public $bulan;
    public $tahun;
    public $ustadz_id = '';
    public $kelas = '';
    
    public $barChartLabels = [];
    public $barChartData = [];
    public $pieChartLabels = ['Lancar', 'Kurang Lancar', 'Terbata'];
    public $pieChartData = [];
    public $lineChartLabels = [];
    public $lineChartData = [];
    
    public $totalSetoran = 0;
    public $totalSantriAktif = 0;
    public $rataRataNilai = 0;
    public $persentaseLancar = 0;
    
    public function mount()
    {
        $this->bulan = now()->format('Y-m');
        $this->tahun = now()->year;
        $this->loadData();
    }

    public function updatedPeriode() 
    { 
        $this->loadData(); 
    }
    
    public function updatedBulan() 
    { 
        $this->loadData(); 
    }
    
    public function updatedUstadzId() 
    { 
        $this->loadData(); 
    }
    
    public function updatedKelas() 
    { 
        $this->loadData(); 
    }

    public function applyFilter()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->loadStatistics();
        $this->loadBarChart();
        $this->loadPieChart();
        $this->loadLineChart();

        // Dispatch event untuk update charts
        $this->dispatch('refreshCharts', [
            'barLabels' => $this->barChartLabels,
            'barData' => $this->barChartData,
            'pieLabels' => $this->pieChartLabels,
            'pieData' => $this->pieChartData,
            'lineLabels' => $this->lineChartLabels,
            'lineData' => $this->lineChartData,
        ]);
    }

    private function getDateRange()
    {
        switch ($this->periode) {
            case 'harian':
                return [now()->startOfDay(), now()->endOfDay()];

            case 'minggu_ini':
                return [now()->startOfWeek(), now()->endOfWeek()];

            case 'bulan_ini':
                return [now()->startOfMonth(), now()->endOfMonth()];

            case '3_bulan':
                return [now()->subMonths(3)->startOfMonth(), now()->endOfMonth()];

            case '6_bulan':
                return [now()->subMonths(6)->startOfMonth(), now()->endOfMonth()];

            case 'tahun_ini':
                return [now()->startOfYear(), now()->endOfYear()];

            case 'custom':
                if (!$this->bulan) {
                    return [now()->startOfMonth(), now()->endOfMonth()];
                }
                $start = date('Y-m-01', strtotime($this->bulan));
                $end = date('Y-m-t', strtotime($this->bulan));
                return [$start, $end];
        }

        return [now()->startOfMonth(), now()->endOfMonth()];
    }

    private function loadStatistics()
    {
        [$start, $end] = $this->getDateRange();

        $query = SetoranHafalan::whereBetween('tanggal_setoran', [$start, $end])
            ->when($this->ustadz_id, fn($q) => $q->where('ustadz_id', $this->ustadz_id))
            ->when($this->kelas, fn($q) => $q->whereHas('santri', fn($s) => $s->where('kelas', $this->kelas)));

        $this->totalSetoran = $query->count();
        $this->rataRataNilai = round($query->avg('nilai_angka') ?? 0, 2);

        $lancarCount = $query->clone()->where('penilaian', 'lancar')->count();

        $this->persentaseLancar = $this->totalSetoran > 0
            ? round(($lancarCount / $this->totalSetoran) * 100, 2)
            : 0;

        $santriQuery = Santri::aktif()
            ->when($this->kelas, fn($q) => $q->where('kelas', $this->kelas))
            ->when($this->ustadz_id, fn($q) => $q->where('ustadz_pembimbing_id', $this->ustadz_id));

        $this->totalSantriAktif = $santriQuery->count();
    }

    private function loadBarChart()
    {
        [$start, $end] = $this->getDateRange();

        // Ambil data ustadz beserta total setoran santri binaannya
        $ustadzData = User::ustadz()
            ->where('is_active', true)
            ->when($this->ustadz_id, fn($q) => $q->where('id', $this->ustadz_id))
            ->get()
            ->map(function($ustadz) use ($start, $end) {
                $totalSetoran = SetoranHafalan::whereHas('santri', function($q) use ($ustadz) {
                    $q->where('ustadz_pembimbing_id', $ustadz->id);
                })
                ->whereBetween('tanggal_setoran', [$start, $end])
                ->count();

                return [
                    'nama' => $ustadz->nama_lengkap,
                    'total' => $totalSetoran
                ];
            })
            ->where('total', '>', 0)
            ->sortByDesc('total')
            ->take(10)
            ->values();

        $this->barChartLabels = $ustadzData->pluck('nama')->toArray();
        $this->barChartData = $ustadzData->pluck('total')->toArray();
    }

    private function loadPieChart()
    {
        [$start, $end] = $this->getDateRange();

        $query = SetoranHafalan::whereBetween('tanggal_setoran', [$start, $end])
            ->when($this->ustadz_id, function($q) {
                $q->whereHas('santri', fn($s) => $s->where('ustadz_pembimbing_id', $this->ustadz_id));
            })
            ->when($this->kelas, function($q) {
                $q->whereHas('santri', fn($s) => $s->where('kelas', $this->kelas));
            });

        $lancar = $query->clone()->where('penilaian', 'lancar')->count();
        $kurangLancar = $query->clone()->where('penilaian', 'kurang_lancar')->count();
        $terbata = $query->clone()->where('penilaian', 'terbata')->count();

        $this->pieChartData = [$lancar, $kurangLancar, $terbata];
    }

    private function loadLineChart()
    {
        [$start, $end] = $this->getDateRange();

        // Ambil data ustadz beserta rata-rata nilai santri binaannya
        $ustadzData = User::ustadz()
            ->where('is_active', true)
            ->when($this->ustadz_id, fn($q) => $q->where('id', $this->ustadz_id))
            ->get()
            ->map(function($ustadz) use ($start, $end) {
                $avgNilai = SetoranHafalan::whereHas('santri', function($q) use ($ustadz) {
                    $q->where('ustadz_pembimbing_id', $ustadz->id);
                })
                ->whereBetween('tanggal_setoran', [$start, $end])
                ->avg('nilai_angka');
                
                return [
                    'nama' => $ustadz->nama_lengkap,
                    'rata_nilai' => round($avgNilai ?? 0, 2)
                ];
            })
            ->where('rata_nilai', '>', 0)
            ->sortBy('nama')
            ->values();

        $this->lineChartLabels = $ustadzData->pluck('nama')->toArray();
        $this->lineChartData = $ustadzData->pluck('rata_nilai')->toArray();
    }

    public function render()
    {
        [$start, $end] = $this->getDateRange();

        $topSantri = ProgressHafalan::with('santri')
            ->join('santri', 'progress_hafalan.santri_id', '=', 'santri.id')
            ->where('santri.status', 'aktif')
            ->when($this->kelas, fn($q) => $q->where('santri.kelas', $this->kelas))
            ->when($this->ustadz_id, fn($q) => $q->where('santri.ustadz_pembimbing_id', $this->ustadz_id))
            ->orderBy('progress_hafalan.persentase_hafalan', 'desc')
            ->take(10)
            ->get();

        $ustadzStats = User::ustadz()
            ->where('is_active', true)
            ->when($this->ustadz_id, fn($q) => $q->where('id', $this->ustadz_id))
            ->get()
            ->map(function ($ustadz) use ($start, $end) {
                $setoranQuery = SetoranHafalan::whereHas('santri', function($q) use ($ustadz) {
                    $q->where('ustadz_pembimbing_id', $ustadz->id);
                })->whereBetween('tanggal_setoran', [$start, $end]);

                $totalSetoran = $setoranQuery->count();
                $santriBinaan = Santri::where('ustadz_pembimbing_id', $ustadz->id)->count();
                $rataNilai = round($setoranQuery->avg('nilai_angka') ?? 0, 2);
                $lancar = $setoranQuery->where('penilaian', 'lancar')->count();
                
                return [
                    'id' => $ustadz->id,
                    'nama' => $ustadz->nama_lengkap,
                    'total_setoran' => $totalSetoran,
                    'santri_binaan' => $santriBinaan,
                    'rata_nilai' => $rataNilai,
                    'lancar' => $lancar,
                ];
            })
            ->where('total_setoran', '>', 0)
            ->sortByDesc('total_setoran')
            ->take(5);

        $distribusiKelas = Santri::aktif()
            ->with('progressHafalan')
            ->when($this->ustadz_id, fn($q) => $q->where('ustadz_pembimbing_id', $this->ustadz_id))
            ->when($this->kelas, fn($q) => $q->where('kelas', $this->kelas))
            ->get()
            ->groupBy('kelas')
            ->map(fn($s) => [
                'total' => $s->count(),
                'rata_juz' => round($s->avg(fn($i) => $i->progressHafalan->total_juz ?? 0), 2),
                'rata_persen' => round($s->avg(fn($i) => $i->progressHafalan->persentase_hafalan ?? 0), 2),
            ]);

        return view('livewire.admin.laporan-hafalan', [
            'topSantri' => $topSantri,
            'ustadzStats' => $ustadzStats,
            'distribusiKelas' => $distribusiKelas,
            'ustadzList' => User::ustadz()->where('is_active', true)->get(),
            'kelasList' => Santri::distinct()->pluck('kelas'),
        ]);
    }
}