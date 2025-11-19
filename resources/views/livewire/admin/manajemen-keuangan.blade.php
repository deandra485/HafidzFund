<div class="min-h-screen bg-gray-50 mx-auto my-auto rounded-xl shadow">
    <!-- Header -->
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center border-b border-gray-200 backdrop-blur-md bg-white/70 sticky top-0 z-50">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Manajemen Keuangan</h1>
            <p class="text-gray-500 mt-1">Kelola transaksi dan keuangan santri</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- <!-- Tombol Kembali -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-2 bg-gray-100 border border-gray-200 px-4 py-2 rounded-xl text-gray-600 hover:bg-gray-200 transition">
                ← Kembali
            </a>

            <!-- Tombol Export -->
            <button wire:click="export"
                class="flex items-center gap-2 border border-gray-200 bg-white px-4 py-2 rounded-xl text-gray-700 hover:bg-gray-100 transition">
                ⬇️ Export Excel
            </button> --}}

            <a 
                href="{{ route('admin.transaksi.create') }}" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:scale-105 transition-transform duration-200" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Transaksi Baru
            </a>
        </div>
    </header>

    <!-- Flash Messages -->
    @foreach (['message' => 'green', 'error' => 'red'] as $msg => $color)
        @if(session()->has($msg))
            <div class="flex items-center gap-3 mb-6 px-4 py-3 rounded-lg border bg-{{ $color }}-50 border-{{ $color }}-200 text-{{ $color }}-800 shadow-sm animate-fadeIn">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session($msg) }}
            </div>
        @endif
    @endforeach

    <!-- Statistik Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-10">
        @foreach ($statistik as $label => $value)
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition-shadow duration-300">
                <p class="text-sm font-medium text-gray-500">{{ ucwords(str_replace('_', ' ', $label)) }}</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-2">
                    {{ is_numeric($value) ? ' '.number_format($value,0,',','.') : $value }}
                </h3>
            </div>
        @endforeach
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6 flex flex-wrap gap-4">
        <input type="search" 
            wire:model.live.debounce.300ms="search"
            placeholder="Cari Nama, NIS, atau No. Bukti..." 
            class="flex-1 min-w-[200px] border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        
        <select wire:model.live="filterJenis" class="border rounded-lg px-4 py-2">
            <option value="">Semua Jenis</option>
            <option value="deposit">Deposit</option>
            <option value="penarikan">Penarikan</option>
            <option value="pembelian">Pembelian</option>
        </select>

        <input type="date" wire:model.live="filterTanggal" class="border rounded-lg px-4 py-2">

        <select wire:model.live="perPage" class="border rounded-lg px-4 py-2">
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </select>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-gray-50">
                    <tr class="text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">No. Bukti</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Santri</th>
                        <th class="px-6 py-3">Jenis</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3 text-right">Nominal</th>
                        <th class="px-6 py-3 text-right">Saldo</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transaksiList as $transaksi)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-gray-800">{{ $transaksi->no_bukti }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $transaksi->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ $transaksi->santri->initials ?? '-' }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $transaksi->santri->nama_lengkap ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $transaksi->santri->nis ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $transaksi->jenis_transaksi === 'deposit' ? 'bg-green-100 text-green-800' : 
                                       ($transaksi->jenis_transaksi === 'penarikan' ? 'bg-orange-100 text-orange-800' : 
                                       'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($transaksi->jenis_transaksi) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $transaksi->keterangan }}</td>
                            <td class="px-6 py-4 text-right font-bold {{ $transaksi->jenis_transaksi==='deposit'?'text-green-600':'text-red-600' }}">
                                {{ $transaksi->jenis_transaksi==='deposit'?'+':'-' }} Rp {{ number_format($transaksi->nominal,0,',','.') }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-600">
                                Rp {{ number_format($transaksi->saldo_sesudah,0,',','.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="deleteTransaksi({{ $transaksi->id }})"
                                    class="p-2 rounded-lg hover:bg-red-50 text-red-600 hover:text-red-800 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                Tidak ada transaksi ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
