<?php

// app/Livewire/Admin/JadwalSetoran.php
namespace App\Livewire\Ustadz;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\JadwalSetoran as JadwalModel;
use App\Models\Santri;
use App\Models\User;
use App\Models\LogAktivitas;

#[Layout('layouts.guest-user')]
class JadwalSetoran extends Component
{
    use WithPagination;

    public $search = '';
    public $filterHari = '';
    public $filterUstadz = '';
    public $perPage = 20;

    // Form Modal
    public $modalOpen = false;
    public $modalMode = 'add';
    public $jadwalId;
    
    public $santri_id;
    public $ustadz_id;
    public $hari_setoran;
    public $waktu_mulai;
    public $is_active = true;

    protected $queryString = ['search', 'filterHari', 'filterUstadz'];

    protected function rules()
    {
        return [
            'santri_id' => 'required|exists:santri,id',
            'ustadz_id' => 'required|exists:users,id',
            'hari_setoran' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'waktu_mulai' => 'required|date_format:H:i',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($mode = 'add', $id = null)
    {
        $this->modalMode = $mode;
        $this->modalOpen = true;
        $this->resetValidation();

        if ($mode === 'edit' && $id) {
            $jadwal = JadwalModel::findOrFail($id);
            $this->jadwalId = $jadwal->id;
            $this->santri_id = $jadwal->santri_id;
            $this->ustadz_id = $jadwal->ustadz_id;
            $this->hari_setoran = $jadwal->hari_setoran;
            $this->waktu_mulai = $jadwal->waktu_mulai->format('H:i');
            $this->is_active = $jadwal->is_active;
        }
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->reset(['jadwalId', 'santri_id', 'ustadz_id', 'hari_setoran', 'waktu_mulai', 'is_active']);
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->modalMode === 'add') {
                JadwalModel::create([
                    'santri_id' => $this->santri_id,
                    'ustadz_id' => $this->ustadz_id,
                    'hari_setoran' => $this->hari_setoran,
                    'waktu_mulai' => $this->waktu_mulai,
                    'is_active' => $this->is_active,
                ]);

                LogAktivitas::log('Menambah jadwal setoran', 'Jadwal');
                session()->flash('message', 'Jadwal setoran berhasil ditambahkan!');
            } else {
                $jadwal = JadwalModel::findOrFail($this->jadwalId);
                $jadwal->update([
                    'santri_id' => $this->santri_id,
                    'ustadz_id' => $this->ustadz_id,
                    'hari_setoran' => $this->hari_setoran,
                    'waktu_mulai' => $this->waktu_mulai,
                    'is_active' => $this->is_active,
                ]);

                LogAktivitas::log('Mengupdate jadwal setoran', 'Jadwal');
                session()->flash('message', 'Jadwal setoran berhasil diupdate!');
            }

            $this->closeModal();
            $this->dispatch('refresh-table');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $jadwal = JadwalModel::findOrFail($id);
            $jadwal->is_active = !$jadwal->is_active;
            $jadwal->save();

            session()->flash('message', 'Status jadwal berhasil diubah!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $jadwal = JadwalModel::findOrFail($id);
            $jadwal->delete();

            LogAktivitas::log('Menghapus jadwal setoran', 'Jadwal');
            session()->flash('message', 'Jadwal berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $jadwalQuery = JadwalModel::with(['santri', 'ustadz'])
            ->when($this->search, function($q) {
                $q->whereHas('santri', function($query) {
                    $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                          ->orWhere('nis', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterHari, fn($q) => $q->where('hari_setoran', $this->filterHari))
            ->when($this->filterUstadz, fn($q) => $q->where('ustadz_id', $this->filterUstadz))
            ->orderBy('hari_setoran')
            ->orderBy('waktu_mulai');

        $statistik = [
            'total_jadwal' => JadwalModel::count(),
            'jadwal_aktif' => JadwalModel::where('is_active', true)->count(),
            'jadwal_hari_ini' => JadwalModel::hariIni()->active()->count(),
        ];

        // Group by hari
        $jadwalPerHari = JadwalModel::active()
            ->with(['santri', 'ustadz'])
            ->get()
            ->groupBy('hari_setoran');

        return view('livewire.ustadz.jadwal-setoran', [
            'jadwalList' => $jadwalQuery->paginate($this->perPage),
            'santriList' => Santri::aktif()->orderBy('nama_lengkap')->get(),
            'ustadzList' => User::ustadz()->active()->orderBy('nama_lengkap')->get(),
            'statistik' => $statistik,
            'jadwalPerHari' => $jadwalPerHari,
        ]);
    }
}