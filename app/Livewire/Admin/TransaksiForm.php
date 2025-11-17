<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\TransaksiJajan;
use App\Models\SaldoJajan;
use App\Models\Santri;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.guest')]
class TransaksiForm extends Component
{
    public $santri_id, $jenis_transaksi, $nominal, $keterangan;
    public $santriList = [];
    public $saldoSantri = null;
    public $namaSantri = null;
    public $nisSantri = null;

    protected $rules = [
        'santri_id' => 'required|exists:santri,id',
        'jenis_transaksi' => 'required|in:deposit,penarikan,pembelian',
        'nominal' => 'required|numeric|min:1',
        'keterangan' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->santriList = Santri::orderBy('nama_lengkap', 'asc')->get();
    }

    // ✅ Update otomatis saat dropdown santri dipilih
    public function updatedSantriId($value)
    {
        if ($value) {
            $santri = Santri::find($value);
            $saldo = SaldoJajan::where('santri_id', $value)->first();

            $this->namaSantri = $santri->nama_lengkap ?? '-';
            $this->nisSantri = $santri->nis ?? 'NIS-' . $santri->id;
            $this->saldoSantri = $saldo ? $saldo->saldo_tersedia : 0;
        } else {
            $this->namaSantri = null;
            $this->nisSantri = null;
            $this->saldoSantri = null;
        }
    }

    public function simpan()
    {
        $this->validate();

        DB::transaction(function () {
            $saldo = SaldoJajan::firstOrCreate(
                ['santri_id' => $this->santri_id],
                ['saldo_tersedia' => 0, 'total_deposit' => 0, 'total_pengeluaran' => 0]
            );

            $saldoSebelum = $saldo->saldo_tersedia;

            if ($this->jenis_transaksi === 'deposit') {
                $saldo->saldo_tersedia += $this->nominal;
                $saldo->total_deposit += $this->nominal;
            } else {
                if ($saldo->saldo_tersedia < $this->nominal) {
                    throw new \Exception("Saldo tidak mencukupi untuk transaksi ini.");
                }
                $saldo->saldo_tersedia -= $this->nominal;
                $saldo->total_pengeluaran += $this->nominal;
            }

            $saldo->save();

            TransaksiJajan::create([
                'santri_id' => $this->santri_id,
                'admin_id' => Auth::id(),
                'tanggal_transaksi' => now(),
                'jenis_transaksi' => $this->jenis_transaksi,
                'nominal' => $this->nominal,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldo->saldo_tersedia,
                'keterangan' => $this->keterangan,
                'no_bukti' => 'TRX-' . strtoupper(uniqid()),
            ]);
        });

        session()->flash('message', 'Transaksi berhasil disimpan!');
        return redirect()->route('admin.manajemen-keuangan');
    }

    public function render()
    {
        return view('livewire.admin.transaksi-form');
    }
}
