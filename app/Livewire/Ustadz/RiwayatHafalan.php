<?php

namespace App\Livewire\Ustadz;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Santri;
use App\Models\SetoranHafalan;
use Livewire\WithPagination;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.guest-user')]
class RiwayatHafalan extends Component
{
    public $search = '';
    public $filterKelas = '';
    public $tanggal;
    public $santriList = [];
    public $statistik = [
        'total_santri'      => 0,
        'selesai'   => 0,
        'belum'     => 0,
    ];
    use WithPagination;
    public $perPage = 10;

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        $this->loadSantri();
    }

    public function updatedSearch()       { $this->resetPage(); }
    public function updatedFilterKelas()  { $this->resetPage(); }
    public function updatedTanggal()      { $this->resetPage(); }
    public function updatedPerPage()      { $this->resetPage(); }


    public function loadSantri()
    {
        $santri = Santri::query()
            ->when($this->search, fn($q) => $q->where('nama_lengkap', 'like', "%{$this->search}%"))
            ->when($this->filterKelas, fn($q) => $q->where('kelas', $this->filterKelas))
            ->orderBy('nama_lengkap')
            ->get();

        $this->santriList = $santri->map(function ($s) {

            // Cek setoran hari ini
            $setoran = SetoranHafalan::where('santri_id', $s->id)
                ->whereDate('tanggal_setoran', $this->tanggal)
                ->first();

            // Status setoran otomatis
            $statusSetoran = $setoran ? 'selesai' : 'belum';

            return [
                'id'                => $s->id,
                'nama'              => $s->nama_lengkap,
                'kelas'             => $s->kelas,
                'progress'          => $s->progress_hafalan ?? '-',

                // Setoran otomatis
                'status_setoran'    => $statusSetoran,

                'waktu'             => $setoran?->created_at?->format('H:i') ?? '-',
            ];

        })->toArray();

    }

    public function render()
    {
        return view('livewire.ustadz.riwayat-hafalan', [
            'santriList' => $this->santriList,
            'statistik'  => $this->statistik,
        ]);
    }
}
