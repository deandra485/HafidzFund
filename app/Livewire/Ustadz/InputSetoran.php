<?php

namespace App\Livewire\Ustadz;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Santri;
use App\Models\SetoranHafalan;
use App\Models\ProgressHafalan;
use App\Models\KehadiranSetoran;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // ✅ Tambahkan ini
use Illuminate\Validation\Rule;

#[Layout('layouts.guest-user')]
class InputSetoran extends Component
{
    public $santri_id;
    public $tanggal_setoran;
    public $juz;
    public $surah;
    public $ayat_mulai;
    public $ayat_selesai;
    public $jumlah_halaman;
    public $jenis_setoran = 'ziyadah';
    public $penilaian;
    public $nilai_angka;
    public $catatan;

    public $selectedSantri;
    public $daftarSurah = []; // ✅ Tambahkan variabel daftar surah

    protected function rules()
    {
        return [
            'santri_id'        => ['required', Rule::exists('santri', 'id')],
            'tanggal_setoran'  => ['required', 'date'],
            'surah'            => ['required', 'string', 'max:50'],
            'ayat_mulai'       => ['required', 'integer', 'min:1'],
            'ayat_selesai'     => ['required', 'integer', 'gte:ayat_mulai'],
            'jenis_setoran'    => ['required', Rule::in(['ziyadah', "muroja'ah"])],
            'penilaian'        => ['required', Rule::in(['lancar', 'kurang_lancar', 'terbata'])],
            'juz'              => ['nullable', 'integer', 'min:1', 'max:30'],
            'jumlah_halaman'   => ['nullable', 'numeric', 'min:0'],
            'nilai_angka'      => ['nullable', 'integer', 'min:0', 'max:100'],
            'catatan'          => ['nullable', 'string'],
        ];
    }

    public function mount()
    {
        $this->tanggal_setoran = now()->format('Y-m-d\TH:i');

        // ✅ Ambil daftar surah dari API
        try {
            $response = Http::get('https://api.alquran.cloud/v1/surah');
            if ($response->successful()) {
                $data = $response->json();
                $this->daftarSurah = $data['data']; // daftar semua surah
            } else {
                $this->daftarSurah = [];
            }
        } catch (\Exception $e) {
            $this->daftarSurah = [];
        }
    }

    public function updatedSantriId($value)
    {
        if ($value) {
            $this->selectedSantri = Santri::with('progressHafalan')->find($value);
        } else {
            $this->selectedSantri = null;
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            $ustadz = Auth::user();

            $santri = Santri::where('id', $this->santri_id)
                ->where('ustadz_pembimbing_id', $ustadz->id)
                ->first();

            if (!$santri) {
                session()->flash('error', 'Santri ini bukan binaan Anda.');
                return;
            }

            // Simpan setoran hafalan
            $setoran = SetoranHafalan::create([
                'santri_id'        => $this->santri_id,
                'ustadz_id'        => $ustadz->id,
                'tanggal_setoran'  => $this->tanggal_setoran,
                'juz'              => $this->juz,
                'surah'            => $this->surah,
                'ayat_mulai'       => $this->ayat_mulai,
                'ayat_selesai'     => $this->ayat_selesai,
                'jumlah_halaman'   => $this->jumlah_halaman,
                'jenis_setoran'    => $this->jenis_setoran,
                'penilaian'        => $this->penilaian,
                'nilai_angka'      => $this->nilai_angka,
                'catatan'          => $this->catatan,
            ]);

            // Update progress hafalan
            if ($this->jenis_setoran === 'ziyadah' && $this->jumlah_halaman) {
                $progress = ProgressHafalan::firstOrCreate(['santri_id' => $this->santri_id]);
                $progress->updateProgress($this->jumlah_halaman);
                $progress->juz_terakhir = $this->juz;
                $progress->surah_terakhir = $this->surah;
                $progress->save();
            }

            // Update kehadiran
            KehadiranSetoran::updateOrCreate(
                [
                    'santri_id' => $this->santri_id,
                    'tanggal'   => now()->toDateString(),
                ],
                ['status_kehadiran' => 'hadir']
            );

            // Log aktivitas
            LogAktivitas::log(
                'Input setoran hafalan',
                'Setoran',
                "Santri: {$santri->nama_lengkap}, Surah: {$this->surah}, Ayat: {$this->ayat_mulai}-{$this->ayat_selesai}"
            );

            session()->flash('message', '✅ Setoran hafalan berhasil disimpan.');

            // Reset form
            $this->reset([
                'santri_id', 'juz', 'surah', 'ayat_mulai', 'ayat_selesai',
                'jumlah_halaman', 'penilaian', 'nilai_angka', 'catatan', 'selectedSantri'
            ]);
            $this->tanggal_setoran = now()->format('Y-m-d\TH:i');

            $this->dispatch('refresh-stats');
        } catch (\Exception $e) {
            session()->flash('error', '❌ Gagal menyimpan setoran: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $ustadz = Auth::user();

        $santriList = Santri::where('ustadz_pembimbing_id', $ustadz->id)
            ->with('progressHafalan')
            ->orderBy('nama_lengkap')
            ->get();

        $statistikHariIni = [
            'total'           => SetoranHafalan::hariIni()->where('ustadz_id', $ustadz->id)->count(),
            'lancar'          => SetoranHafalan::hariIni()->where('ustadz_id', $ustadz->id)->where('penilaian', 'lancar')->count(),
            'kurang_lancar'   => SetoranHafalan::hariIni()->where('ustadz_id', $ustadz->id)->where('penilaian', 'kurang_lancar')->count(),
            'terbata'         => SetoranHafalan::hariIni()->where('ustadz_id', $ustadz->id)->where('penilaian', 'terbata')->count(),
        ];

        return view('livewire.ustadz.input-setoran', [
            'santriList' => $santriList,
            'statistikHariIni' => $statistikHariIni,
            'daftarSurah' => $this->daftarSurah, // ✅ kirim ke view
        ]);
    }
}
