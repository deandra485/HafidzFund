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

    <div class="bg-white rounded-2xl shadow-sm border p-6 mt-10">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Update Status </h2>
        <div>
            <input type="date"
                wire:model.live="tanggal"
                class="border px-4 py-2.5 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 bg-white text-gray-700">
                <select wire:model.live="perPage" class="border rounded-lg px-4 py-2">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            <button wire:click="updateBulk"
                class="px-5 py-2.5 bg-green-600 text-white font-semibold rounded-xl shadow hover:bg-green-700 transition">
                Terapkan ke Semua Centang
            </button>
        </div>
    </div>

    <div class="p-6">

        <h2 class="text-2xl font-bold text-gray-800">Jadwal Setoran Hari Ini</h2>

        <div class="mt-6 bg-white rounded-xl shadow-sm border overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 text-gray-600 text-sm border-b">
                    <tr>
                        <th class="p-4 text-left font-semibold">Waktu</th>
                        <th class="p-4 text-left font-semibold">Nama Santri</th>
                        <th class="p-4 text-left font-semibold">Kelas</th>
                        <th class="p-4 text-left font-semibold">Progress</th>
                        <th class="p-4 text-left font-semibold">Status</th>
                        <th class="p-4 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-gray-800 divide-y">

                    @foreach ($santriList as $item)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="p-4 text-gray-700 font-medium">
                                {{ $item['waktu'] }}
                            </td>

                            <td class="p-4 font-semibold text-gray-900">
                                {{ $item['nama'] }}
                            </td>

                            <td class="p-4 text-gray-700">
                                {{ $item['kelas'] }}
                            </td>

                            <td class="p-4 text-gray-700">
                                {{ $item['progress'] }}
                            </td>

                            <!-- STATUS BADGE -->
                            <td class="p-4">
                                @php
                                    $status = $item['status_setoran'] ?? 'belum';

                                    $badge = match($status) {
                                        'selesai' => 'bg-emerald-100 text-emerald-700',
                                        'proses' => 'bg-blue-100 text-blue-700',
                                        'antri' => 'bg-amber-100 text-amber-700',
                                        default => 'bg-gray-100 text-gray-700'
                                    };
                                @endphp

                                <span class="px-4 py-1.5 rounded-full text-sm font-medium {{ $badge }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <td class="p-4">

                            @if ($item['status_setoran'] === 'selesai')
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm">
                                    Sudah Setor
                                </span>
                            @else
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm">
                                    Belum Setor
                                </span>
                            @endif

                                    <button class="text-gray-600 text-xl">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>

            @if (count($santriList) === 0)
                <p class="text-center text-gray-500 p-6">Tidak ada jadwal.</p>
            @endif

        </div>

    </div>


</div>
