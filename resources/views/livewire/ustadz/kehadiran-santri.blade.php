<div class="flex: 1; display: flex; flex-direction: column; overflow: hidden;">

    <!-- Header -->
    <header class="backdrop-blur-md bg-white/70 shadow-sm border-b border-green-100 sticky top-0 z-50">
        <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Kehadiran Santri</h1>
                <p class="text-gray-500 mt-1">Kelola dan pantau kehadiran santri secara real-time</p>
            </div>
        </div>
    </header>

    {{-- ========================= --}}
    {{--      STATISTIK HADIR     --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-10">

        @foreach (['hadir' => 'emerald', 'izin' => 'amber', 'sakit' => 'blue', 'alpa' => 'rose'] as $key => $color)
            <div class="p-6 rounded-2xl bg-white shadow-sm border border-gray-100 hover:shadow-md transition group">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-semibold text-gray-600 uppercase">{{ $key }}</p>

                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-{{ $color }}-100 text-{{ $color }}-700 text-xl">
                        {{-- ikon jika dibutuhkan --}}
                    </div>
                </div>

                <p class="text-4xl font-bold mt-4 tracking-tight text-gray-900">
                    {{ $statistik[$key] ?? 0 }}
                </p>
            </div>
        @endforeach

    </div>

    {{-- ========================= --}}
    {{--   BULK UPDATE KEHADIRAN   --}}
    {{-- ========================= --}}
    <div class="bg-white rounded-2xl shadow-sm border p-6 mt-10">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Update Status </h2>

        <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
            <select wire:model="bulkStatus"
                    class="border rounded-xl px-4 py-2.5 bg-gray-50 focus:ring-2 focus:ring-indigo-500">
                <option value="hadir">Hadir</option>
                <option value="izin">Izin</option>
                <option value="sakit">Sakit</option>
                <option value="alpa">Alpa</option>
            </select>

            <input type="date"
                wire:model.live="tanggal"
                class="border px-4 py-2.5 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 bg-white text-gray-700">

            <button wire:click="updateBulk"
                class="px-5 py-2.5 bg-green-600 text-white font-semibold rounded-xl shadow hover:bg-green-700 transition">
                Terapkan ke Semua Centang
            </button>
        </div>
    </div>

    {{-- ========================= --}}
    {{--      TABEL KEHADIRAN      --}}
    {{-- ========================= --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mt-10">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-4 text-sm font-semibold text-gray-600">#</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">Nama</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">Kelas</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach ($santriList as $santri)
                    <tr class="hover:bg-gray-50 transition">

                        <!-- Checkbox -->
                        <td class="p-4">
                            <input type="checkbox"
                                   wire:model="selectedStatus.{{ $santri['id'] }}"
                                   class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </td>

                        <td class="p-4 font-medium text-gray-900">{{ $santri['nama'] }}</td>
                        <td class="p-4 text-gray-600">{{ $santri['kelas'] }}</td>

                        <td class="p-4">
                            <div class="flex gap-2">

                                @foreach (['hadir','izin','sakit','alpa'] as $status)
                                    <button
                                        wire:click="updateKehadiran({{ $santri['id'] }}, '{{ $status }}')"
                                        class="px-4 py-1.5 rounded-lg text-sm font-medium transition
                                        @if ($santri['status_kehadiran'] === $status)
                                            bg-indigo-600 text-white shadow
                                        @else
                                            hover:bg-gray-200
                                        @endif">
                                        {{ ucfirst($status) }}
                                    </button>
                                @endforeach

                            </div>
                        </td>

                    </tr>
                @endforeach

            </tbody>
        </table>

        @if (count($santriList) === 0)
            <p class="text-center text-gray-500 p-6">Tidak ada santri bimbingan.</p>
        @endif
    </div>
    

</div>
