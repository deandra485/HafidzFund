<?php

namespace App\Livewire\Ustadz;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Santri;
use App\Models\KehadiranSetoran;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.guest-user')]
class KehadiranSantri extends Component
{
    public $search = '';
    public $filterKelas = '';
    public $tanggal;
    public $santriList = [];

    public $statistik = [
        'hadir' => 0,
        'izin'  => 0,
        'sakit' => 0,
        'alpa'  => 0,
    ];

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        $this->loadSantri();
    }

    public function updatedSearch()        { $this->loadSantri(); }
    public function updatedFilterKelas()   { $this->loadSantri(); }
    public function updatedTanggal()       { $this->loadSantri(); }

    /**
     * HITUNG STATISTIK
     */
    public function hitungStatistik()
    {
        $this->statistik = [
            'hadir' => KehadiranSetoran::where('tanggal', $this->tanggal)->where('status_kehadiran', 'hadir')->count(),
            'izin'  => KehadiranSetoran::where('tanggal', $this->tanggal)->where('status_kehadiran', 'izin')->count(),
            'sakit' => KehadiranSetoran::where('tanggal', $this->tanggal)->where('status_kehadiran', 'sakit')->count(),
            'alpa'  => KehadiranSetoran::where('tanggal', $this->tanggal)->where('status_kehadiran', 'alpa')->count(),
        ];
    }

    /**
     * MEMUAT DATA SANTRI + STATUS SETORAN OTOMATIS
     */
    public function loadSantri()
    {
        $santri = Santri::query()
            ->when($this->search, fn($q) => $q->where('nama_lengkap', 'like', "%{$this->search}%"))
            ->when($this->filterKelas, fn($q) => $q->where('kelas', $this->filterKelas))
            ->orderBy('nama_lengkap')
            ->get();

        $this->santriList = $santri->map(function ($s) {

            // Cek kehadiran
            $kehadiran = KehadiranSetoran::where('santri_id', $s->id)
                ->where('tanggal', $this->tanggal)
                ->first();

            return [
                'id'                => $s->id,
                'nama'              => $s->nama_lengkap,
                'kelas'             => $s->kelas,
                'progress'          => $s->progress_hafalan ?? '-',

                // Kehadiran manual
                'status_kehadiran'  => $kehadiran->status_kehadiran ?? 'belum',
            ];

        })->toArray();

        $this->hitungStatistik();
    }

    /**
     * UPDATE KEHADIRAN SAJA
     */
    public function updateKehadiran($santriId, $status)
    {
        KehadiranSetoran::updateOrCreate(
            ['santri_id' => $santriId, 'tanggal' => $this->tanggal],
            ['status_kehadiran' => $status]
        );

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => "Update Kehadiran Santri ID $santriId menjadi $status"
        ]);

        $this->loadSantri();
    }

    public function render()
    {
        return view('livewire.ustadz.kehadiran-santri', [
            'santriList' => $this->santriList,
            'statistik'  => $this->statistik,
        ]);
    }
}
