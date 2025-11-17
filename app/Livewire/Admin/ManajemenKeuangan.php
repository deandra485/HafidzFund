<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\TransaksiJajan;
use App\Models\SaldoJajan;
use App\Models\Santri;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.guest')]
class ManajemenKeuangan extends Component
{
    use WithPagination;

    public $search = '';
    public $filterJenis = '';
    public $filterTanggal = '';
    public $perPage = 10;
    public $statistik = [];

    protected $listeners = ['transaksiTersimpan' => '$refresh'];

    public function mount()
    {
        $this->hitungStatistik();
    }

    public function render()
    {
        $query = TransaksiJajan::with('santri')
            ->when($this->search, fn($q) => 
                $q->whereHas('santri', fn($s) => $s->where('nama_lengkap', 'like', "%{$this->search}%"))
                  ->orWhere('no_bukti', 'like', "%{$this->search}%")
            )
            ->when($this->filterJenis, fn($q) => $q->where('jenis_transaksi', $this->filterJenis))
            ->when($this->filterTanggal, fn($q) => $q->whereDate('tanggal_transaksi', $this->filterTanggal))
            ->latest();

        $transaksiList = $query->paginate($this->perPage);

        return view('livewire.admin.manajemen-keuangan', [
            'transaksiList' => $transaksiList,
        ]);
    }

    public function hitungStatistik()
    {
        $this->statistik = [
            'total_transaksi' => TransaksiJajan::count(),
            'total_deposit' => TransaksiJajan::where('jenis_transaksi', 'deposit')->sum('nominal'),
            'total_penarikan' => TransaksiJajan::where('jenis_transaksi', 'penarikan')->sum('nominal'),
            'saldo_tersedia' => SaldoJajan::sum('saldo_tersedia'),
        ];
    }

    public function deleteTransaksi($id)
    {
        DB::transaction(function () use ($id) {
            $transaksi = TransaksiJajan::findOrFail($id);
            $saldo = SaldoJajan::where('santri_id', $transaksi->santri_id)->first();

            if ($saldo) {
                if ($transaksi->jenis_transaksi === 'deposit') {
                    $saldo->saldo_tersedia -= $transaksi->nominal;
                    $saldo->total_deposit -= $transaksi->nominal;
                } else {
                    $saldo->saldo_tersedia += $transaksi->nominal;
                    $saldo->total_pengeluaran -= $transaksi->nominal;
                }

                $saldo->last_updated_at = now();
                $saldo->save();
            }

            $transaksi->delete();
        });

        session()->flash('message', 'Transaksi dan saldo berhasil diperbarui.');
        $this->hitungStatistik();
    }

    public function export()
    {
        // Nanti bisa ditambah export Excel pakai Maatwebsite\Excel
        session()->flash('message', 'Export Excel masih dalam pengembangan 😄');
    }
}
