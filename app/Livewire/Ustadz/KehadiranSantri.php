<?php

namespace App\Livewire\Ustadz;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Santri;
use App\Models\KehadiranSetoran;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.guest-user')]
class KehadiranSantri extends Component
{
    public $search = '';
    public $filterKelas = '';
    public $tanggal;
    public $santriList = [];
    public $sesiAktif = 'halaqoh_pagi'; // Sesi yang sedang dipilih
    public $viewMode = 'sesi'; // 'sesi' atau 'harian'
    
    public $selectedSantri = [];
    public $bulkStatus = 'hadir';

    public $statistik = [
        'halaqoh_pagi' => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0],
        'halaqoh_siang' => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0],
        'halaqoh_sore' => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0],
        'halaqoh_malam' => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0],
    ];

    public $statistikHarian = [
        'hadir' => 0,
        'izin' => 0,
        'sakit' => 0,
        'alpa' => 0,
    ];

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        $this->loadSantri();
    }

    public function updatedSearch()      { $this->loadSantri(); }
    public function updatedFilterKelas() { $this->loadSantri(); }
    public function updatedTanggal()     { $this->loadSantri(); }
    public function updatedSesiAktif()   { $this->loadSantri(); }
    public function updatedViewMode()    { $this->loadSantri(); }

    public function getSesiList()
    {
        return \App\Models\KehadiranSetoran::getSesiList();
    }

    /**
     * Hitung Statistik Per Sesi
     */
    public function hitungStatistik()
    {
        $kehadiran = KehadiranSetoran::whereDate('tanggal', $this->tanggal)->get();
        
        foreach (array_keys($this->statistik) as $sesi) {
            $this->statistik[$sesi] = [
                'hadir' => $kehadiran->where($sesi, 'hadir')->count(),
                'izin'  => $kehadiran->where($sesi, 'izin')->count(),
                'sakit' => $kehadiran->where($sesi, 'sakit')->count(),
                'alpa'  => $kehadiran->where($sesi, 'alpa')->count(),
            ];
        }

        // Statistik harian (rata-rata semua sesi)
        $this->hitungStatistikHarian();
    }

    /**
     * Hitung Statistik Harian (Agregat)
     */
    public function hitungStatistikHarian()
    {
        $kehadiran = KehadiranSetoran::whereDate('tanggal', $this->tanggal)->get();
        
        $totalHadir = 0;
        $totalIzin = 0;
        $totalSakit = 0;
        $totalAlpa = 0;
        
        foreach ($kehadiran as $k) {
            foreach (array_keys($this->statistik) as $sesi) {
                if ($k->$sesi === 'hadir') $totalHadir++;
                if ($k->$sesi === 'izin') $totalIzin++;
                if ($k->$sesi === 'sakit') $totalSakit++;
                if ($k->$sesi === 'alpa') $totalAlpa++;
            }
        }

        $this->statistikHarian = [
            'hadir' => $totalHadir,
            'izin'  => $totalIzin,
            'sakit' => $totalSakit,
            'alpa'  => $totalAlpa,
        ];
    }

    /**
     * Load Data Santri + Kehadiran
     */
    public function loadSantri()
    {
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

        $kehadiranHariIni = KehadiranSetoran::where('tanggal', $this->tanggal)
            ->get()
            ->keyBy('santri_id');

        $this->santriList = $santri->map(function ($s) use ($kehadiranHariIni) {
            $kehadiran = $kehadiranHariIni->get($s->id);

            $data = [
                'id'    => $s->id,
                'nama'  => $s->nama_lengkap,
                'kelas' => $s->kelas,
            ];

            if ($this->viewMode === 'sesi') {
                // Mode sesi: tampilkan status sesi aktif saja
                $data['status'] = $kehadiran->{$this->sesiAktif} ?? 'belum';
            } else {
                // Mode harian: tampilkan semua sesi
                foreach (array_keys($this->getSesiList()) as $sesi) {
                    $data[$sesi] = $kehadiran->$sesi ?? 'belum';
                }
                $data['persentase'] = $kehadiran ? $kehadiran->persentase_kehadiran : 0;
                $data['status_harian'] = $kehadiran ? $kehadiran->status_harian : 'Belum Dicatat';
            }

            return $data;
        })->toArray();

        $this->hitungStatistik();
    }

    /**
     * Update Status Kehadiran (Single)
     */
    public function updateKehadiran($santriId, $status, $sesi = null)
    {
        $sesi = $sesi ?? $this->sesiAktif;

        KehadiranSetoran::updateOrCreate(
            [
                'santri_id' => $santriId,
                'tanggal'   => $this->tanggal,
            ],
            [
                $sesi => $status,
                'updated_by' => Auth::id(),
            ]
        );

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => "Update kehadiran {$sesi} Santri ID {$santriId} → {$status} ({$this->tanggal})",
        ]);

        $this->loadSantri();
        $this->dispatch('kehadiran-updated');
    }

    /**
     * Bulk Update Kehadiran
     */
    public function updateBulk()
    {
        if (empty($this->selectedSantri)) {
            session()->flash('error', 'Pilih minimal 1 santri!');
            return;
        }

        $updated = 0;

        foreach ($this->selectedSantri as $santriId => $checked) {
            if ($checked) {
                $this->updateKehadiran($santriId, $this->bulkStatus);
                $updated++;
            }
        }

        $this->selectedSantri = [];
        session()->flash('success', "{$updated} santri berhasil diupdate!");
        $this->loadSantri();
    }

    /**
     * Copy Kehadiran dari Hari Sebelumnya
     */
    public function copyFromYesterday()
    {
        $yesterday = date('Y-m-d', strtotime($this->tanggal . ' -1 day'));
        
        $kehadiranKemarin = KehadiranSetoran::where('tanggal', $yesterday)->get();

        if ($kehadiranKemarin->isEmpty()) {
            session()->flash('error', 'Tidak ada data kehadiran kemarin!');
            return;
        }

        $copied = 0;

        foreach ($kehadiranKemarin as $k) {
            KehadiranSetoran::updateOrCreate(
                [
                    'santri_id' => $k->santri_id,
                    'tanggal'   => $this->tanggal,
                ],
                [
                    'halaqoh_pagi' => $k->halaqoh_pagi,
                    'halaqoh_siang' => $k->halaqoh_siang,
                    'halaqoh_sore' => $k->halaqoh_sore,
                    'halaqoh_malam' => $k->halaqoh_malam,
                    'updated_by' => Auth::id(),
                ]
            );
            $copied++;
        }

        session()->flash('success', "Berhasil menyalin {$copied} data kehadiran!");
        $this->loadSantri();
    }

    public function render()
    {
        return view('livewire.ustadz.kehadiran-santri', [
            'santriList' => $this->santriList,
            'statistik'  => $this->statistik,
            'statistikHarian' => $this->statistikHarian,
            'sesiList' => $this->getSesiList(),
        ]);
    }
}