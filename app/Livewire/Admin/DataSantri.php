<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Santri;
use App\Models\User;
use App\Models\ProgressHafalan;
use App\Models\SaldoJajan;
use App\Models\LogAktivitas;

#[Layout('layouts.guest')]
class DataSantri extends Component
{
    use WithPagination;

    public $search = '';
    public $filterKelas = '';
    public $filterStatus = '';
    public $filterAngkatan = '';
    public $perPage = 10;

    // Form properties
    public $modalOpen = false;
    public $modalMode = 'add'; // add or edit
    public $santriId;
    
    public $nis;
    public $nama_lengkap;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $jenis_kelamin;
    public $alamat;
    public $kelas;
    public $angkatan;
    public $status = 'aktif';
    public $ustadz_pembimbing_id;
    public $no_telp_wali;

    protected $queryString = ['search', 'filterKelas', 'filterStatus'];

    protected function rules()
    {
        return [
            'nis' => 'required|unique:santri,nis,' . $this->santriId,
            'nama_lengkap' => 'required|max:100',
            'tempat_lahir' => 'nullable|max:50',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable',
            'kelas' => 'nullable|max:20',
            'angkatan' => 'nullable|max:10',
            'status' => 'required|in:aktif,alumni,keluar',
            'ustadz_pembimbing_id' => 'nullable|exists:users,id',
            'no_telp_wali' => 'nullable|max:20',
        ];
    }

    // ============================================
    // 🔥 AUTO GENERATE NIS
    // ============================================
    private function generateNIS()
    {
        $prefix = '2025'; // prefix bebas ubah

        // Cari nis terakhir yg prefix-nya sama
        $last = Santri::where('nis', 'like', $prefix . '%')
                      ->orderBy('nis', 'desc')
                      ->first();

        if ($last) {
            // Ambil angka setelah prefix
            $lastNumber = intval(substr($last->nis, strlen($prefix)));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Jadi format 4 digit: 0001, 0002, ...
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
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

            $santri = Santri::findOrFail($id);
            $this->santriId = $santri->id;
            $this->fill($santri->only([
                'nis', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir',
                'jenis_kelamin', 'alamat', 'kelas', 'angkatan', 
                'status', 'ustadz_pembimbing_id', 'no_telp_wali'
            ]));

        } else {
            // Mode tambah → generate NIS
            $this->nis = $this->generateNIS();
        }
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->reset([
            'santriId', 'nis', 'nama_lengkap', 'tempat_lahir', 
            'tanggal_lahir', 'jenis_kelamin', 'alamat', 'kelas', 
            'angkatan', 'ustadz_pembimbing_id', 'no_telp_wali'
        ]);
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->modalMode === 'add') {
                $santri = Santri::create([
                    'nis' => $this->nis,
                    'nama_lengkap' => $this->nama_lengkap,
                    'tempat_lahir' => $this->tempat_lahir,
                    'tanggal_lahir' => $this->tanggal_lahir,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'alamat' => $this->alamat,
                    'kelas' => $this->kelas,
                    'angkatan' => $this->angkatan,
                    'status' => $this->status,
                    'ustadz_pembimbing_id' => $this->ustadz_pembimbing_id,
                    'no_telp_wali' => $this->no_telp_wali,
                ]);

                ProgressHafalan::create(['santri_id' => $santri->id]);
                SaldoJajan::create(['santri_id' => $santri->id]);

                LogAktivitas::log('Menambah santri baru: ' . $this->nama_lengkap, 'Santri');
                
                session()->flash('message', 'Data santri berhasil ditambahkan!');
            } else {

                $santri = Santri::findOrFail($this->santriId);
                $santri->update([
                    'nis' => $this->nis,
                    'nama_lengkap' => $this->nama_lengkap,
                    'tempat_lahir' => $this->tempat_lahir,
                    'tanggal_lahir' => $this->tanggal_lahir,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'alamat' => $this->alamat,
                    'kelas' => $this->kelas,
                    'angkatan' => $this->angkatan,
                    'status' => $this->status,
                    'ustadz_pembimbing_id' => $this->ustadz_pembimbing_id,
                    'no_telp_wali' => $this->no_telp_wali,
                ]);

                LogAktivitas::log('Mengupdate data santri: ' . $this->nama_lengkap, 'Santri');
                
                session()->flash('message', 'Data santri berhasil diupdate!');
            }

            $this->closeModal();
            $this->dispatch('refresh-table');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $santri = Santri::findOrFail($id);
            $nama = $santri->nama_lengkap;
            $santri->delete();

            LogAktivitas::log('Menghapus santri: ' . $nama, 'Santri');
            
            session()->flash('message', 'Data santri berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $santriQuery = Santri::with(['ustadzPembimbing', 'progressHafalan'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterKelas, fn($q) => $q->where('kelas', $this->filterKelas))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterAngkatan, fn($q) => $q->where('angkatan', $this->filterAngkatan))
            ->latest();

        return view('livewire.admin.data-santri', [
            'santriList' => $santriQuery->paginate($this->perPage),
            'ustadzList' => User::ustadz()->active()->get(),

            'kelasList' => [
                '7'  => 'Kelas 7 (SMP 1)',
                '8'  => 'Kelas 8 (SMP 2)',
                '9'  => 'Kelas 9 (SMP 3)',
                '10' => 'Kelas 10 (SMA 1)',
                '11' => 'Kelas 11 (SMA 2)',
                '12' => 'Kelas 12 (SMA 3)',
            ],

            'angkatanList' => Santri::distinct()->pluck('angkatan'),
        ]);
    }

}
