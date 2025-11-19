<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\SetoranHafalan;
use App\Models\Santri;
use App\Models\User;
use App\Models\ProgressHafalan;
use Illuminate\Support\Facades\DB;

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
    public $pieChartLabels = [];
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

    public function updatedPeriode() { $this->loadData(); }
    public function updatedBulan() { $this->loadData(); }
    public function updatedUstadzId() { $this->loadData(); }
    public function updatedKelas() { $this->loadData(); }

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

        $this->dispatch('refreshCharts');
    }

    private function getDateRange()
    {
        switch ($this->periode) {
            case 'bulan_ini':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case '3_bulan':
                return [now()->subMonths(3)->startOfMonth(), now()->endOfMonth()];
            case '6_bulan':
                return [now()->subMonths(6)->startOfMonth(), now()->endOfMonth()];
            case 'tahun_ini':
                return [now()->startOfYear(), now()->endOfYear()];
            case 'custom':
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
            ->when($this->kelas, fn($q) => $q->whereHas('santri', fn($q2) => $q2->where('kelas', $this->kelas)));

        $this->totalSetoran = $query->count();
        $this->rataRataNilai = round($query->avg('nilai_angka') ?? 0, 2);

        $lancarCount = $query->where('penilaian', 'lancar')->count();
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
        // BAR CHART PER HARI: 30 HARI TERAKHIR
        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');

            $data[] = SetoranHafalan::whereDate('tanggal_setoran', $tanggal)
                ->when($this->ustadz_id, fn($q) => $q->where('ustadz_id', $this->ustadz_id))
                ->when($this->kelas, fn($q) => $q->whereHas('santri', fn($q2) => $q2->where('kelas', $this->kelas)))
                ->count();
        }

        $this->barChartLabels = $labels;
        $this->barChartData = $data;
    }

    private function loadPieChart()
    {
        [$start, $end] = $this->getDateRange();

        $query = SetoranHafalan::whereBetween('tanggal_setoran', [$start, $end])
            ->when($this->ustadz_id, fn($q) => $q->where('ustadz_id', $this->ustadz_id))
            ->when($this->kelas, fn($q) => $q->whereHas('santri', fn($q2) => $q2->where('kelas', $this->kelas)));

        $penilaian = $query->select('penilaian', DB::raw('COUNT(*) as total'))
            ->groupBy('penilaian')
            ->get();

        $this->pieChartLabels = ['Lancar', 'Kurang Lancar', 'Terbata-bata'];
        $this->pieChartData = [
            $penilaian->where('penilaian', 'lancar')->first()->total ?? 0,
            $penilaian->where('penilaian', 'kurang_lancar')->first()->total ?? 0,
            $penilaian->where('penilaian', 'terbata')->first()->total ?? 0
        ];
    }

    private function loadLineChart()
    {
        // LINE CHART PER HARI BULAN BERJALAN
        $bulan = now()->month;
        $tahun = now()->year;
        $hariDalamBulan = now()->daysInMonth;

        $labels = [];
        $data = [];

        for ($hari = 1; $hari <= $hariDalamBulan; $hari++) {
            $labels[] = $hari;

            $avg = SetoranHafalan::whereYear('tanggal_setoran', $tahun)
                ->whereMonth('tanggal_setoran', $bulan)
                ->whereDay('tanggal_setoran', $hari)
                ->when($this->ustadz_id, fn($q) => $q->where('ustadz_id', $this->ustadz_id))
                ->when($this->kelas, fn($q) => $q->whereHas('santri', fn($q2) => $q2->where('kelas', $this->kelas)))
                ->avg('nilai_angka');

            $data[] = $avg ? round($avg, 2) : 0;
        }

        $this->lineChartLabels = $labels;
        $this->lineChartData = $data;
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
            ->with(['setoranHafalan' => fn($q) => $q->whereBetween('tanggal_setoran', [$start, $end])])
            ->get()
            ->map(function ($ustadz) use ($start, $end) {
                $setoran = SetoranHafalan::where('ustadz_id', $ustadz->id)
                    ->whereBetween('tanggal_setoran', [$start, $end]);

                return [
                    'id' => $ustadz->id,
                    'nama' => $ustadz->nama_lengkap,
                    'total_setoran' => $setoran->count(),
                    'santri_binaan' => Santri::where('ustadz_pembimbing_id', $ustadz->id)->count(),
                    'rata_nilai' => round($setoran->avg('nilai_angka') ?? 0, 2),
                    'lancar' => $setoran->where('penilaian', 'lancar')->count(),
                ];
            })
            ->sortByDesc('total_setoran')
            ->take(5);

        $distribusiKelas = Santri::aktif()
            ->with('progressHafalan')
            ->when($this->ustadz_id, fn($q) => $q->where('ustadz_pembimbing_id', $this->ustadz_id))
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
            'ustadzList' => User::ustadz()->active()->get(),
            'kelasList' => Santri::distinct()->pluck('kelas'),
        ]);
    }
}
