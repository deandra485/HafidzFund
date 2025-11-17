<div class="flex: 1; display: flex; flex-direction: column; overflow: hidden;">
    <!-- HEADER -->
    <header class="backdrop-blur-md bg-white/70 shadow-sm border-b border-green-100 sticky top-0 z-50">
        <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <!-- Kiri -->
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span>Santri Binaan Anda</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola dan pantau santri binaan Anda dengan mudah
                </p>
            </div>

            <!-- Kanan -->
            <div
                class="flex items-center bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-700 shadow-sm">
                <span class="mr-2 text-gray-500">Total Santri:</span>
                <span class="font-semibold text-green-600">{{ count($santriList) }}</span>
            </div>
        </div>
    </header>

    <!-- ISI HALAMAN -->
    <main class="w-full py-8">

        {{-- Notifikasi --}}
        @if (session('message'))
            <div class="p-4 mb-6 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm shadow-sm mx-6">
                {{ session('message') }}
            </div>
        @endif

        {{-- Form Tambah Santri --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100 mx-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <span>Tambah Santri dari Data Admin</span>
            </h2>

            <div class="flex flex-col sm:flex-row gap-3">
                <select wire:model="selected_santri"
                    class="flex-1 border border-gray-300 rounded-lg p-2.5 text-sm text-gray-700 focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="">-- Pilih Santri --</option>
                    @foreach ($availableSantri as $santri)
                        <option value="{{ $santri->id }}">{{ $santri->nama_lengkap }}</option>
                    @endforeach
                </select>

                <button wire:click="addSantri"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition-all shadow-sm">
                    Tambah
                </button>
            </div>
        </div>

        {{-- Daftar Santri --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5 px-6">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <span>Daftar Santri Binaan</span>
            </h3>
            <p class="text-sm text-gray-500 mt-1 sm:mt-0">Santri yang sedang Anda bimbing</p>
        </div>

        @if ($santriList->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-5 rounded-lg text-center text-sm shadow-sm mx-6">
                Belum ada santri binaan yang ditambahkan.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 px-6">
                @foreach ($santriList as $santri)
                    <div
                        class="bg-white border border-gray-200 shadow-sm rounded-xl p-5 hover:shadow-lg hover:border-green-400 transition-all duration-200">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-base font-semibold text-gray-800">{{ $santri->nama_lengkap }}</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    Kelas:
                                    <span class="font-medium text-gray-700">{{ $santri->kelas ?? '-' }}</span> |
                                    Angkatan:
                                    <span class="font-medium text-gray-700">{{ $santri->angkatan ?? '-' }}</span>
                                </p>
                            </div>
                            <div
                                class="bg-green-100 text-green-700 w-9 h-9 flex items-center justify-center rounded-full font-semibold text-sm">
                                {{ strtoupper(substr($santri->nama_lengkap, 0, 1)) }}
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-5">
                            <a href="{{ route('ustadz.input-setoran', ['santri_id' => $santri->id]) }}" wire:navigate
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-medium shadow transition">
                                Input Setoran
                            </a>

                            <button wire:click="removeSantri({{ $santri->id }})"
                                class="text-red-600 hover:text-red-800 text-xs font-semibold transition">
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</div>
