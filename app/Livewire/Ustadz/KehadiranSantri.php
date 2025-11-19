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

    public function updatedSearch()      { $this->loadSantri(); }
    public function updatedFilterKelas() { $this->loadSantri(); }
    public function updatedTanggal()     { $this->loadSantri(); }

    /**
     * =============================
     * HITUNG STATISTIK
     * =============================
     */
    public function hitungStatistik()
    {
        $this->statistik = [
            'hadir' => KehadiranSetoran::whereDate('tanggal', $this->tanggal)->where('status_kehadiran', 'hadir')->count(),
            'izin'  => KehadiranSetoran::whereDate('tanggal', $this->tanggal)->where('status_kehadiran', 'izin')->count(),
            'sakit' => KehadiranSetoran::whereDate('tanggal', $this->tanggal)->where('status_kehadiran', 'sakit')->count(),
            'alpa'  => KehadiranSetoran::whereDate('tanggal', $this->tanggal)->where('status_kehadiran', 'alpa')->count(),
        ];
    }

    /**
     * =============================
     * LOAD DATA SANTRI + KEHADIRAN
     * =============================
     */
    public function loadSantri()
    {
        // Ambil daftar santri sesuai ustadz pembimbing
        $santri = Santri::query()
            ->where('ustadz_pembimbing_id', Auth::id())
            ->when($this->search, fn($q) =>
                $q->where('nama_lengkap', 'like', "%{$this->search}%")
            )
            ->when($this->filterKelas, fn($q) =>
                $q->where('kelas', $this->filterKelas)
            )
            ->orderBy('nama_lengkap')
            ->get();

        // Ambil semua kehadiran dalam 1 query (lebih efisiensi)
        $kehadiranHariIni = KehadiranSetoran::where('tanggal', $this->tanggal)->get()
            ->keyBy('santri_id');

        $this->santriList = $santri->map(function ($s) use ($kehadiranHariIni) {

            $kehadiran = $kehadiranHariIni->get($s->id);

            return [
                'id'               => $s->id,
                'nama'             => $s->nama_lengkap,
                'kelas'            => $s->kelas,

                // Ini data tambahan kalau mau ditampilkan
                'progress'         => $s->progress_hafalan ?? '-',

                // Status default: "belum"
                'status_kehadiran' => $kehadiran->status_kehadiran ?? 'belum',
            ];

        })->toArray();

        $this->hitungStatistik();
    }

    /**
     * =============================
     * UPDATE STATUS KEHADIRAN
     * =============================
     */
    public function updateKehadiran($santriId, $status)
    {
        KehadiranSetoran::updateOrCreate(
            [
                'santri_id' => $santriId,
                'tanggal'   => $this->tanggal,
            ],
            [
                'status_kehadiran' => $status,
            ]
        );

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => "Update kehadiran Santri ID $santriId menjadi $status pada tanggal {$this->tanggal}",
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
