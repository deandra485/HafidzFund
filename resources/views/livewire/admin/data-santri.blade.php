<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center border-b border-gray-200 backdrop-blur-md bg-white/70 sticky top-0 z-50">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Data Santri</h1>
            <p class="text-gray-500 mt-1">Kelola data santri pesantren dengan mudah</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-2 bg-gray-100 border border-gray-200 px-4 py-2 rounded-xl text-gray-600 hover:bg-gray-200 transition">
                ← Kembali
            </a> --}}

            <a
                href="{{ route('admin.santri-excel') }}" wire:navigate
                class="flex items-center gap-2 border border-gray-200 bg-white px-4 py-2 rounded-xl text-gray-700 hover:bg-gray-100">
                ⬇️ Import Excel
            </a>


            <button wire:click="openModal('add')"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:scale-105 transition-transform duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Santri
            </button>
        </div>
    </header>

    <!-- Filter -->
    <section class="max-w-7xl mx-auto p-6 mt-4 bg-white rounded-xl shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-700">Cari Santri</label>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Nama atau NIS..."
                       class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Kelas</label>
                <select wire:model="filterKelas"
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas }}">{{ $kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Status</label>
                <select wire:model="filterStatus"
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="alumni">Alumni</option>
                    <option value="keluar">Keluar</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Angkatan</label>
                <select wire:model="filterAngkatan"
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Angkatan</option>
                    @foreach ($angkatanList as $angkatan)
                        <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

    <!-- Tabel Data -->
    <section class="max-w-7xl mx-auto p-6 mt-4 bg-white rounded-xl shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs border-b">
                <tr>
                    <th class="py-3 px-4">NIS</th>
                    <th class="py-3 px-4">Nama Lengkap</th>
                    <th class="py-3 px-4">Kelas</th>
                    <th class="py-3 px-4">Angkatan</th>
                    <th class="py-3 px-4">Ustadz Pembimbing</th>
                    <th class="py-3 px-4">Progress Hafalan</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($santriList as $santri)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3 px-4 font-semibold text-gray-800">{{ $santri->nis }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-white {{ $santri->jenis_kelamin === 'L' ? 'bg-blue-500' : 'bg-pink-500' }}">
                                    {{ Str::of($santri->nama_lengkap)->substr(0, 2)->upper() }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $santri->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $santri->jenis_kelamin }} • {{ $santri->tanggal_lahir ? \Carbon\Carbon::parse($santri->tanggal_lahir)->age . ' th' : '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="py-3 px-4 text-gray-600">{{ $santri->kelas ?? '-' }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $santri->angkatan ?? '-' }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $santri->ustadzPembimbing->name ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if ($santri->progressHafalan)
                                <div>
                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>{{ $santri->progressHafalan->total_juz ?? 0 }} Juz</span>
                                        <span class="text-green-600 font-semibold">{{ $santri->progressHafalan->persentase ?? 0 }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="h-1.5 bg-green-500 rounded-full" style="width: {{ $santri->progressHafalan->persentase ?? 0 }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </td>

                        <td class="py-3 px-4 text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $santri->status === 'aktif' ? 'bg-green-100 text-green-700' :
                                   ($santri->status === 'alumni' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($santri->status) }}
                            </span>
                        </td>

                        <td class="py-3 px-4 text-center flex justify-center gap-2">
                            <button wire:click="openModal('edit', {{ $santri->id }})" class="text-green-600 hover:text-green-800">✏️</button>
                            <button wire:click="delete({{ $santri->id }})" onclick="confirm('Hapus data ini?') || event.stopImmediatePropagation()" class="text-red-600 hover:text-red-800">🗑️</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-8 text-gray-400">Tidak ada data santri</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">{{ $santriList->links() }}</div>
    </section>

    @if ($modalOpen)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl w-full max-w-xl p-7 border border-white/20 animate-fadeIn">

                <!-- TITLE -->
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="text-indigo-600 text-2xl">📄</span>
                    {{ $modalMode === 'add' ? 'Tambah Santri Baru' : 'Edit Data Santri' }}
                </h2>

                <!-- FORM -->
                <form wire:submit.prevent="save" class="space-y-4">

                    <!-- ROW 1 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">NIS</label>
                            <input type="text" wire:model="nis"
                            class="w-full mt-1 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition"
                            readonly>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" wire:model="nama_lengkap"
                                class="w-full mt-1 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <!-- ROW 2 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select wire:model="jenis_kelamin"
                                class="w-full mt-1 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div>
                        <label class="text-sm font-medium text-gray-700">Kelas</label>
                        <select wire:model="kelas"
                            class="w-full mt-1 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                    <!-- ROW 3 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Angkatan</label>
                            <input type="text" wire:model="angkatan"
                                class="w-full mt-1 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">No Telp Wali</label>
                            <input type="text" wire:model="no_telp_wali"
                                class="w-full mt-1 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="closeModal"
                            class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl shadow-md hover:bg-indigo-700 hover:shadow-lg transition">
                            {{ $modalMode === 'add' ? 'Simpan' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
