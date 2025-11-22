<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v4a3 3 0 106 0v-4a3 3 0 00-3-3z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8V5m0 0a3 3 0 013 3h-6a3 3 0 013-3z" />
            </svg>
            Form Transaksi Baru
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- 🧾 KIRI: Form Transaksi --}}
            <div class="md:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <form wire:submit.prevent="simpan" class="space-y-5">
                    {{-- Santri --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Santri</label>
                        <select wire:model="santri_id" wire:change="simpan" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="">-- Pilih Santri --</option>
                            @foreach ($santriList as $santri)
                                <option value="{{ $santri->id }}">
                                    {{ $santri->nama_lengkap }} ({{ $santri->nis ?? 'NIS-' . $santri->id }})
                                </option>
                            @endforeach
                        </select>
                        @error('santri_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jenis Transaksi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                        <select wire:model="jenis_transaksi" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="">Pilih Jenis</option>
                            <option value="deposit">Deposit</option>
                            <option value="penarikan">Penarikan</option>
                            <option value="pembelian">Pembelian</option>
                        </select>
                        @error('jenis_transaksi') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nominal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nominal</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                            <input type="number" wire:model="nominal" class="w-full border border-gray-300 rounded-lg p-2.5 pl-8 focus:ring-2 focus:ring-green-500 focus:outline-none" placeholder="Masukkan nominal">
                        </div>
                        @error('nominal') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea wire:model="keterangan" rows="3" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-green-500 focus:outline-none" placeholder="Tuliskan keterangan tambahan..."></textarea>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.manajemen-keuangan') }}" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition" wire:navigate>
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

            {{-- 💰 KANAN: Info Saldo Santri --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                @if ($santri_id)
                    <div class="transition-all duration-300">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 1.343-3 3v4a3 3 0 106 0v-4a3 3 0 00-3-3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8V5m0 0a3 3 0 013 3h-6a3 3 0 013-3z" />
                            </svg>
                            Info Saldo Santri
                        </h2>

                        @if ($saldoSantri !== null)
                            <div class="space-y-2 text-gray-700">
                                <p><span class="font-medium text-gray-600">Nama:</span> {{ $namaSantri }}</p>
                                <p><span class="font-medium text-gray-600">NIS:</span> {{ $nisSantri }}</p>
                            </div>

                            <div class="mt-5 bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                                <p class="text-sm text-gray-500 font-medium mb-1">Saldo Tersedia</p>
                                <p class="text-3xl font-bold text-green-600">
                                    Rp{{ number_format($saldoSantri, 0, ',', '.') }}
                                </p>
                            </div>
                        @else
                            <p class="text-gray-400 italic mt-4">Memuat data saldo...</p>
                        @endif
                    </div>
                @else
                    <div class="flex items-center justify-center h-full text-gray-400 text-center">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 1.343-3 3v4a3 3 0 106 0v-4a3 3 0 00-3-3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8V5m0 0a3 3 0 013 3h-6a3 3 0 013-3z" />
                            </svg>
                            <p>Pilih santri untuk melihat informasi saldo</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
