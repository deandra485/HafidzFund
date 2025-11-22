<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-100">
    <!-- Header -->
    <header class="backdrop-blur-md bg-white/70 shadow-sm border-b border-green-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Input Setoran Hafalan</h1>
                <p class="text-sm text-gray-500">Catat hafalan santri dengan mudah & cepat</p>
            </div>
            <button 
                onclick="window.history.back()" 
                class="flex items-center gap-2 bg-white/70 border border-gray-200 px-4 py-2 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-800 transition font-medium shadow-sm"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </button>
        </div>
    </header>

    <!-- Flash message -->
    <div class="max-w-7xl mx-auto mt-6 px-6">
        @if (session('message'))
            <div class="bg-green-100 text-green-700 border border-green-300 rounded-xl px-4 py-3 mb-4 flex items-center gap-2">
                ✅ {{ session('message') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 text-red-700 border border-red-300 rounded-xl px-4 py-3 mb-4 flex items-center gap-2">
                ⚠️ {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- FORM -->
        <div class="lg:col-span-2 bg-white/80 backdrop-blur-md shadow-xl border border-gray-100 rounded-2xl p-8 transition hover:shadow-2xl">
            <h2 class="text-xl font-semibold mb-6 text-gray-800 flex items-center gap-2">
                Form Setoran Hafalan
            </h2>

            <form wire:submit.prevent="submit" class="space-y-5">
                <!-- Pilih Santri -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Pilih Santri</label>
                    <div class="relative">
                        <select wire:model="santri_id" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">-- Pilih Santri Binaan --</option>
                        @forelse ($santriList as $santri)
                            <option value="{{ $santri->id }}">
                                {{ $santri->nama_lengkap }} (kelas {{ $santri->kelas ?? '-' }}, angkatan {{ $santri->angkatan ?? '-' }})
                            </option>
                        @empty
                            <option value="">Tidak ada santri binaan ditemukan</option>
                        @endforelse
                    </select>
                    </div>
                    @error('santri_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Info Santri Dinamis -->
                @if ($selectedSantri)
                    <div class="border border-green-100 bg-green-50/60 rounded-xl p-4 mt-3 animate-fadeIn">
                        <h3 class="font-semibold text-gray-800 text-lg mb-2 flex items-center gap-2">
                            {{ $selectedSantri->nama_lengkap }}
                        </h3>
                        <ul class="text-gray-600 text-sm space-y-1">
                            <li><span class="font-medium">NIS:</span> {{ $selectedSantri->nis ?? '-' }}</li>
                            <li><span class="font-medium">Kelas:</span> {{ $selectedSantri->kelas ?? '-' }}</li>
                            <li><span class="font-medium">Juz Terakhir:</span> {{ $selectedSantri->progressHafalan->juz_terakhir ?? '-' }}</li>
                            <li><span class="font-medium">Surah Terakhir:</span> {{ $selectedSantri->progressHafalan->surah_terakhir ?? '-' }}</li>
                        </ul>
                    </div>
                @endif

                <!-- Tanggal Setoran -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Tanggal Setoran</label>
                    <input type="datetime-local" wire:model="tanggal_setoran"
                        class="w-full border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-white">
                    @error('tanggal_setoran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Juz & Surah -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Juz</label>
                        <input type="number" wire:model="juz"
                            class="w-full border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                        @error('juz') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Surah</label>
                        <select wire:model="surah"
                            class="w-full border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                            <option value="">-- Pilih Surah --</option>
                            @foreach($daftarSurah as $surah)
                                <option value="{{ $surah['number'] }}">
                                    {{ $surah['number'] }}. {{ $surah['englishName'] }} ({{ $surah['name'] }})
                                </option>
                            @endforeach
                        </select>
                        @error('surah') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <!-- Ayat -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Ayat Mulai</label>
                        <input type="number" wire:model="ayat_mulai"
                            class="w-full border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                        @error('ayat_mulai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Ayat Selesai</label>
                        <input type="number" wire:model="ayat_selesai"
                            class="w-full border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                        @error('ayat_selesai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Jenis & Penilaian -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Jenis Setoran</label>
                        <select wire:model="jenis_setoran"
                            class="w-full border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                            <option value="ziyadah">Ziyadah</option>
                            <option value="muroja'ah">Muroja'ah</option>
                        </select>
                        @error('jenis_setoran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Penilaian</label>
                        <select wire:model="penilaian"
                            class="w-full border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                            <option value="">-- Pilih --</option>
                            <option value="lancar">Lancar</option>
                            <option value="kurang_lancar">Kurang Lancar</option>
                            <option value="terbata">Terbata</option>
                        </select>
                        @error('penilaian') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Jumlah Halaman & Nilai -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Jumlah Halaman</label>
                        <input type="number" wire:model="jumlah_halaman"
                            class="w-full border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                        @error('jumlah_halaman') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Nilai Angka</label>
                        <input type="number" wire:model="nilai_angka" min="0" max="100"
                            class="w-full border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                        @error('nilai_angka') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Catatan</label>
                    <textarea wire:model="catatan"
                        class="w-full border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white resize-none"
                        rows="3" placeholder="Tambahkan catatan singkat..."></textarea>
                    @error('catatan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Submit -->
                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold px-6 py-2.5 rounded-xl shadow hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                        Simpan Setoran
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistik -->
        <div class="bg-white/80 backdrop-blur-md border border-gray-100 shadow-xl rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-semibold mb-4 text-gray-800 flex items-center gap-2">
                    Statistik Hari Ini
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-gray-800">
                        <span>Total Setoran</span>
                        <span class="font-semibold">{{ $statistikHariIni['total'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Lancar</span>
                        <span class="font-semibold">{{ $statistikHariIni['lancar'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between text-yellow-600">
                        <span>Kurang Lancar</span>
                        <span class="font-semibold">{{ $statistikHariIni['kurang_lancar'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between text-red-600">
                        <span>Terbata</span>
                        <span class="font-semibold">{{ $statistikHariIni['terbata'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-gradient-to-r from-green-100 to-green-50 border border-green-200 rounded-xl p-4 text-center">
                <p class="text-sm text-gray-600">Terus semangat membimbing para santri 🌿</p>
            </div>
        </div>

    </main>
</div>
