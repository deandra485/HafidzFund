<?php

// app/Livewire/Admin/LaporanHafalan.php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\SetoranHafalan;
use App\Models\Santri;
use App\Models\User;
use App\Models\ProgressHafalan;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.guest')]
class LaporanHafalan extends Component
{
    public $periode = 'bulan_ini';
    public $bulan;
    public $tahun;
    public $ustadz_id = '';
    public $kelas = '';
    
    // Chart Data
    public $barChartLabels = [];
    public $barChartData = [];
    public $pieChartLabels = [];
    public $pieChartData = [];
    public $lineChartLabels = [];
    public $lineChartData = [];
    
    // Statistics
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
    }

    private function getDateRange()
    {
        switch ($this->periode) {
            case 'bulan_ini':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                break;
            case '3_bulan':
                $start = now()->subMonths(3)->startOfMonth();
                $end = now()->endOfMonth();
                break;
            case '6_bulan':
                $start = now()->subMonths(6)->startOfMonth();
                $end = now()->endOfMonth();
                break;
            case 'tahun_ini':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                break;
            case 'custom':
                $start = date('Y-m-01', strtotime($this->bulan));
                $end = date('Y-m-t', strtotime($this->bulan));
                break;
            default:
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
        }

        return [$start, $end];
    }

    private function loadStatistics()
    {
        [$start, $end] = $this->getDateRange();

        $query = SetoranHafalan::whereBetween('tanggal_setoran', [$start, $end]);

        if ($this->ustadz_id) {
            $query->where('ustadz_id', $this->ustadz_id);
        }

        if ($this->kelas) {
            $query->whereHas('santri', function($q) {
                $q->where('kelas', $this->kelas);
            });
        }

        $this->totalSetoran = $query->count();
        $this->rataRataNilai = round($query->avg('nilai_angka') ?? 0, 2);
        
        $lancarCount = $query->where('penilaian', 'lancar')->count();
        $this->persentaseLancar = $this->totalSetoran > 0 
            ? round(($lancarCount / $this->totalSetoran) * 100, 2) 
            : 0;

        $santriQuery = Santri::aktif();
        if ($this->kelas) {
            $santriQuery->where('kelas', $this->kelas);
        }
        if ($this->ustadz_id) {
            $santriQuery->where('ustadz_pembimbing_id', $this->ustadz_id);
        }
        $this->totalSantriAktif = $santriQuery->count();
    }

    private function loadBarChart()
    {
        [$start, $end] = $this->getDateRange();

        // Setoran per bulan dalam 6 bulan terakhir
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months->push($month);
        }

        $this->barChartLabels = $months->map(fn($m) => $m->locale('id')->isoFormat('MMM Y'))->toArray();

        $this->barChartData = $months->map(function($month) {
            $query = SetoranHafalan::whereYear('tanggal_setoran', $month->year)
                ->whereMonth('tanggal_setoran', $month->month);

            if ($this->ustadz_id) {
                $query->where('ustadz_id', $this->ustadz_id);
            }

            if ($this->kelas) {
                $query->whereHas('santri', function($q) {
                    $q->where('kelas', $this->kelas);
                });
            }

            return $query->count();
        })->toArray();
    }

    private function loadPieChart()
    {
        [$start, $end] = $this->getDateRange();

        $query = SetoranHafalan::whereBetween('tanggal_setoran', [$start, $end]);

        if ($this->ustadz_id) {
            $query->where('ustadz_id', $this->ustadz_id);
        }

        if ($this->kelas) {
            $query->whereHas('santri', function($q) {
                $q->where('kelas', $this->kelas);
            });
        }

        $penilaianData = $query->select('penilaian', DB::raw('count(*) as total'))
            ->groupBy('penilaian')
            ->get();

        $this->pieChartLabels = ['Lancar', 'Kurang Lancar', 'Terbata-bata'];
        $this->pieChartData = [
            $penilaianData->where('penilaian', 'lancar')->first()->total ?? 0,
            $penilaianData->where('penilaian', 'kurang_lancar')->first()->total ?? 0,
            $penilaianData->where('penilaian', 'terbata')->first()->total ?? 0,
        ];
    }

    private function loadLineChart()
    {
        [$start, $end] = $this->getDateRange();

        // Rata-rata nilai per bulan
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months->push($month);
        }

        $this->lineChartLabels = $months->map(fn($m) => $m->locale('id')->isoFormat('MMM'))->toArray();

        $this->lineChartData = $months->map(function($month) {
            $query = SetoranHafalan::whereYear('tanggal_setoran', $month->year)
                ->whereMonth('tanggal_setoran', $month->month);

            if ($this->ustadz_id) {
                $query->where('ustadz_id', $this->ustadz_id);
            }

            if ($this->kelas) {
                $query->whereHas('santri', function($q) {
                    $q->where('kelas', $this->kelas);
                });
            }

            return round($query->avg('nilai_angka') ?? 0, 2);
        })->toArray();
    }

    // public function exportPDF()
    // {
    //     $data = $this->prepareExportData();
        
    //     $pdf = Pdf::loadView('exports.laporan-hafalan-pdf', $data);
    //     return $pdf->download('laporan-hafalan-' . now()->format('Y-m-d') . '.pdf');
    // }

    // public function exportExcel()
    // {
    //     // Implementation with Maatwebsite\Excel
    //     // return response()->download(/* Excel file */);
    // }

    // private function prepareExportData()
    // {
    //     [$start, $end] = $this->getDateRange();

    //     $query = SetoranHafalan::with(['santri', 'ustadz'])
    //         ->whereBetween('tanggal_setoran', [$start, $end]);

    //     if ($this->ustadz_id) {
    //         $query->where('ustadz_id', $this->ustadz_id);
    //     }

    //     if ($this->kelas) {
    //         $query->whereHas('santri', function($q) {
    //             $q->where('kelas', $this->kelas);
    //         });
    //     }

    //     return [
    //         'periode' => $this->bulan ?? now()->format('Y-m'),
    //         'ustadz' => $this->ustadz_id ? User::find($this->ustadz_id)->nama_lengkap : 'Semua Ustadz',
    //         'kelas' => $this->kelas ?: 'Semua Kelas',
    //         'total_setoran' => $this->totalSetoran,
    //         'rata_rata_nilai' => $this->rataRataNilai,
    //         'persentase_lancar' => $this->persentaseLancar,
    //         'data_setoran' => $query->latest()->get(),
    //     ];
    // }

    public function render()
    {
        [$start, $end] = $this->getDateRange();

        // Top 10 Santri Terbaik
        $topSantriQuery = ProgressHafalan::with('santri')
            ->join('santri', 'progress_hafalan.santri_id', '=', 'santri.id')
            ->where('santri.status', 'aktif');

        if ($this->kelas) {
            $topSantriQuery->where('santri.kelas', $this->kelas);
        }

        if ($this->ustadz_id) {
            $topSantriQuery->where('santri.ustadz_pembimbing_id', $this->ustadz_id);
        }

        $topSantri = $topSantriQuery->orderBy('progress_hafalan.persentase_hafalan', 'desc')
            ->take(10)
            ->get();

        // Statistik Per Ustadz
        $ustadzStats = User::ustadz()
            ->with(['setoranHafalan' => function($q) use ($start, $end) {
                $q->whereBetween('tanggal_setoran', [$start, $end]);
            }])
            ->get()
            ->map(function($ustadz) use ($start, $end) {
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

        // Distribusi Hafalan Per Kelas
        $distribusiKelas = Santri::aktif()
            ->with('progressHafalan')
            ->when($this->ustadz_id, fn($q) => $q->where('ustadz_pembimbing_id', $this->ustadz_id))
            ->get()
            ->groupBy('kelas')
            ->map(function($santri) {
                return [
                    'total' => $santri->count(),
                    'rata_juz' => round($santri->avg(fn($s) => $s->progressHafalan->total_juz ?? 0), 2),
                    'rata_persen' => round($santri->avg(fn($s) => $s->progressHafalan->persentase_hafalan ?? 0), 2),
                ];
            });

        return view('livewire.admin.laporan-hafalan', [
            'topSantri' => $topSantri,
            'ustadzStats' => $ustadzStats,
            'distribusiKelas' => $distribusiKelas,
            'ustadzList' => User::ustadz()->active()->get(),
            'kelasList' => Santri::distinct()->pluck('kelas'),
        ]);
    }
}

