<?php

namespace App\Livewire\Ustadz;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Santri;
use App\Models\SetoranHafalan;
use App\Models\JadwalSetoran;
use App\Models\ProgressHafalan;
use Carbon\Carbon;

#[Layout('layouts.guest-user')]
class Dashboard extends Component
{
    public $santriBinaan;
    public $jadwalHariIni;
    public $setoranHariIni;
    public $belumSetor;
    public $rataJuz;
    public $topSantri;

   public function mount()
{
    try {
        $ustadz = Auth::user();
        if (!$ustadz) {
            return redirect()->route('login');
        }

        $ustadzId = $ustadz->id;

        // 🔹 Santri binaan aktif
        $this->santriBinaan = Santri::where('ustadz_pembimbing_id', $ustadzId)
            ->where('status', 'aktif')
            ->with('progressHafalan')
            ->get();

        // 🔹 Jadwal setoran hari ini
        $this->jadwalHariIni = JadwalSetoran::with('santri')
            ->where('ustadz_id', $ustadzId)
            ->whereDate('hari_setoran', Carbon::today())
            ->where('is_active', true)
            ->get();

        // 🔹 Setoran hafalan hari ini
        $this->setoranHariIni = SetoranHafalan::where('ustadz_id', $ustadzId)
            ->whereDate('tanggal_setoran', Carbon::today()) // ← lebih tepat pakai tanggal_setoran
            ->get();

        // 🔹 Santri yang belum setor
        $santriSudahSetor = $this->setoranHariIni->unique('santri_id')->count();
        $this->belumSetor = max(0, $this->santriBinaan->count() - $santriSudahSetor);

        // 🔹 Rata-rata juz terakhir
        $this->rataJuz = ProgressHafalan::whereHas('santri', function ($q) use ($ustadzId) {
                $q->where('ustadz_pembimbing_id', $ustadzId)->where('status', 'aktif');
            })
            ->avg('juz_terakhir') ?? 0;

        // 🔹 Top 3 santri
        $this->topSantri = ProgressHafalan::with('santri')
            ->whereHas('santri', fn($q) => 
                $q->where('ustadz_pembimbing_id', $ustadzId)
                  ->where('status', 'aktif')
            )
            ->orderByDesc('persentase_hafalan')
            ->take(3)
            ->get();

    } catch (\Throwable $e) {
        // Tangani error agar Livewire tidak me-return JSON
        report($e);
        $this->santriBinaan = collect();
        $this->jadwalHariIni = collect();
        $this->setoranHariIni = collect();
        $this->belumSetor = 0;
        $this->rataJuz = 0;
        $this->topSantri = collect();
    }
}

}
