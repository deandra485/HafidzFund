<div class="flex: 1; display: flex; flex-direction: column; overflow: hidden;">

    <!-- Header -->
    <header class="backdrop-blur-md bg-white/70 shadow-sm border-b border-green-100 sticky top-0 z-50">
        <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Riwayat Hafalan Santri</h1>
                <p class="text-gray-500 mt-1">Kelola dan pantau riwayat hafalan santri secara real-time</p>
            </div>
        </div>
    </header>

    <div class="p-6">

        {{-- Filter --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <input type="text" wire:model="searchSantri" class="form-input rounded-xl shadow-sm"
                placeholder=" Cari nama santri">

            <input type="date" wire:model="filterTanggal" class="form-input rounded-xl shadow-sm">

            <select wire:model="filterJenis" class="form-select rounded-xl shadow-sm">
                <option value="">Jenis Setoran</option>
                <option value="ziyadah">Ziyadah</option>
                <option value="muroja'ah">Muroja'ah</option>
            </select>

            <select wire:model="filterPenilaian" class="form-select rounded-xl shadow-sm">
                <option value="">Penilaian</option>
                <option value="lancar">Lancar</option>
                <option value="kurang_lancar">Kurang Lancar</option>
                <option value="terbata">Terbata</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="py-3 px-2 text-left">Santri</th>
                        <th class="text-left">Tanggal</th>
                        <th class="text-left">Surah</th>
                        <th class="text-left">Ayat</th>
                        <th class="text-left">Jenis</th>
                        <th class="text-left">Penilaian</th>
                        <th class="text-center">Detail</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach($setoran as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-2 font-medium text-gray-800">{{ $item->santri->nama_lengkap }}</td>
                        <td>{{ $item->tanggal_setoran }}</td>
                        <td>{{ $item->surah }}</td>
                        <td>{{ $item->ayat_mulai }} - {{ $item->ayat_selesai }}</td>
                        <td class="capitalize">{{ $item->jenis_setoran }}</td>
                        <td class="capitalize">{{ str_replace('_', ' ', $item->penilaian) }}</td>
                        <td class="text-center">
                            <button wire:click="showDetail({{ $item->id }})"
                                class="p-2 rounded-lg hover:bg-blue-100 transition">

                                <!-- SVG Ikon Mata -->
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                     fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                                     stroke="currentColor" class="w-6 h-6 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($setoran->isEmpty())
            <p class="text-center text-gray-500 py-4">Belum ada setoran.</p>
            @endif
        </div>

        {{-- Modal Detail --}}
        @if($detailSetoran)
        <div class="fixed inset-0 bg-black/40 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-xl shadow-xl w-96 animate-fade-in">
                <h3 class="font-bold text-xl mb-4 text-gray-800">📘 Detail Setoran</h3>

                <div class="space-y-2 text-gray-700">
                    <p><strong>Santri:</strong> {{ $detailSetoran->santri->nama_lengkap }}</p>
                    <p><strong>Surah:</strong> {{ $detailSetoran->surah }}</p>
                    <p><strong>Ayat:</strong> {{ $detailSetoran->ayat_mulai }} - {{ $detailSetoran->ayat_selesai }}</p>
                    <p><strong>Jenis:</strong> {{ ucfirst($detailSetoran->jenis_setoran) }}</p>
                    <p><strong>Penilaian:</strong> {{ ucfirst($detailSetoran->penilaian) }}</p>
                    <p><strong>Catatan:</strong> {{ $detailSetoran->catatan ?? '-' }}</p>
                </div>

                <button wire:click="closeDetail"
                    class="mt-5 w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">
                    Tutup
                </button>
            </div>
        </div>
        @endif

    </div>
</div>
