<?php

namespace App\Livewire\Ustadz;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Santri;
use App\Models\SetoranHafalan;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.guest-user')]
class RiwayatHafalan extends Component
{
    use WithPagination;

    public $searchSantri = '';
    public $filterTanggal = '';
    public $filterJenis = '';
    public $filterPenilaian = '';
    public $detailSetoran = null;
    public $limit = 10; // NEW

    public function updatingSearchSantri() { $this->resetPage(); }
    public function updatingFilterTanggal() { $this->resetPage(); }
    public function updatingFilterJenis() { $this->resetPage(); }
    public function updatingFilterPenilaian() { $this->resetPage(); }
    public function updatingLimit() { $this->resetPage(); }

    public function showDetail($id)
    {
        $this->detailSetoran = SetoranHafalan::with('santri')
            ->where('ustadz_id', Auth::id())
            ->find($id);
    }

    public function closeDetail()
    {
        $this->detailSetoran = null;
    }

    public function render()
    {
        $ustadzId = Auth::id();

        $setoran = SetoranHafalan::with('santri')
            ->where('ustadz_id', $ustadzId)
            ->when($this->searchSantri, fn($q) =>
                $q->whereHas('santri', fn($s) =>
                    $s->where('nama_lengkap', 'like', '%' . $this->searchSantri . '%')
                )
            )
            ->when($this->filterTanggal, fn($q) =>
                $q->whereDate('tanggal_setoran', $this->filterTanggal)
            )
            ->when($this->filterJenis, fn($q) =>
                $q->where('jenis_setoran', $this->filterJenis)
            )
            ->when($this->filterPenilaian, fn($q) =>
                $q->where('penilaian', $this->filterPenilaian)
            )
            ->orderBy('tanggal_setoran', 'desc')
            ->paginate($this->limit);

        return view('livewire.ustadz.riwayat-hafalan', [
            'setoran' => $setoran,
        ]);
    }
}
