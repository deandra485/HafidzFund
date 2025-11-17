<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center border-b border-gray-200 backdrop-blur-md bg-white/70 sticky top-0 z-50">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Laporan Hafalan</h1>
                <p class="text-gray-600 mt-1">Monitoring dan evaluasi progress hafalan santri</p>
            </div>
            {{-- <div class="flex gap-3">
                <button wire:click="exportPDF" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2 font-medium">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Export PDF
                </button>
                <button wire:click="exportExcel" class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 flex items-center gap-2 font-medium shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </button>
            </div> --}}
    </header>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select wire:model.live="periode" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500">
                    <option value="bulan_ini">Bulan Ini</option>
                    <option value="3_bulan">3 Bulan Terakhir</option>
                    <option value="6_bulan">6 Bulan Terakhir</option>
                    <option value="tahun_ini">Tahun Ini</option>
                    <option value="custom">Custom</option>
                </select>
            </div>

            @if($periode === 'custom')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <input type="month" wire:model.live="bulan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500">
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ustadz</label>
                <select wire:model.live="ustadz_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Ustadz</option>
                    @foreach($ustadzList as $ustadz)
                        <option value="{{ $ustadz->id }}">{{ $ustadz->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select wire:model.live="kelas" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k }}">{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button wire:click="applyFilter" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-green-800 font-medium shadow-lg">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-10" wire:poll.60s="loadData">
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-blue-100 text-sm mb-2">Total Setoran</p>
            <h3 class="text-4xl font-bold">{{ number_format($totalSetoran) }}</h3>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-green-100 text-sm mb-2">Santri Aktif</p>
            <h3 class="text-4xl font-bold">{{ $totalSantriAktif }}</h3>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
            <p class="text-purple-100 text-sm mb-2">Rata-rata Nilai</p>
            <h3 class="text-4xl font-bold">{{ $rataRataNilai }}</h3>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-orange-100 text-sm mb-2">Persentase Lancar</p>
            <h3 class="text-4xl font-bold">{{ $persentaseLancar }}%</h3>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-3 gap-6 mb-6">
        <!-- Bar Chart - Setoran Per Bulan -->
        <div class="col-span-2 bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Jumlah Setoran Per Bulan</h3>
                    <p class="text-sm text-gray-500">6 bulan terakhir</p>
                </div>
                <div class="bg-blue-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <canvas id="barChart" wire:ignore></canvas>
        </div>

        <!-- Pie Chart - Distribusi Penilaian -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Distribusi Penilaian</h3>
                    <p class="text-sm text-gray-500">Status hafalan</p>
                </div>
                <div class="bg-green-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </div>
            </div>
            <canvas id="pieChart" wire:ignore></canvas>
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-gray-700">Lancar</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ $pieChartData[0] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                        <span class="text-gray-700">Kurang Lancar</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ $pieChartData[1] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-gray-700">Terbata-bata</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ $pieChartData[2] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Line Chart - Rata-rata Nilai -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Tren Rata-rata Nilai Hafalan</h3>
                <p class="text-sm text-gray-500">Perkembangan nilai 6 bulan terakhir</p>
            </div>
            <div class="bg-purple-100 p-2 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
        </div>
        <canvas id="lineChart" wire:ignore></canvas>
    </div>

    <!-- Tables Section -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <!-- Top 10 Santri Terbaik -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-700 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Top 10 Santri Terbaik
                </h3>
            </div>
            <div class="overflow-auto max-h-96">
                <table class="w-full">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kelas</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Progress</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($topSantri as $index => $progress)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                @if($index === 0)
                                    <span class="text-2xl">🥇</span>
                                @elseif($index === 1)
                                    <span class="text-2xl">🥈</span>
                                @elseif($index === 2)
                                    <span class="text-2xl">🥉</span>
                                @else
                                    <span class="text-sm font-semibold text-gray-600">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">{{ $progress->santri->nama_lengkap }}</p>
                                <p class="text-xs text-gray-500">{{ $progress->santri->nis }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                    {{ $progress->santri->kelas }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="text-lg font-bold text-green-600">{{ number_format($progress->persentase_hafalan, 1) }}%</span>
                                    <span class="text-xs text-gray-500">{{ number_format($progress->total_juz, 1) }} Juz</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Statistik Per Ustadz -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-700 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Top 5 Ustadz Aktif
                </h3>
            </div>
            <div class="overflow-auto max-h-96">
                <table class="w-full">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ustadz</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Setoran</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Binaan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Lancar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($ustadzStats as $stat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-700 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($stat['nama'], 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $stat['nama'] }}</p>
                                        <p class="text-xs text-gray-500">Rata-rata: {{ $stat['rata_nilai'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 text-sm font-semibold bg-blue-100 text-blue-800 rounded-full">
                                    {{ $stat['total_setoran'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 text-sm font-semibold bg-green-100 text-green-800 rounded-full">
                                    {{ $stat['santri_binaan'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 text-sm font-semibold bg-purple-100 text-purple-800 rounded-full">
                                    {{ $stat['lancar'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Distribusi Per Kelas -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Distribusi Hafalan Per Kelas</h3>
                <p class="text-sm text-gray-500">Rata-rata progress setiap kelas</p>
            </div>
            <div class="bg-blue-100 p-2 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($distribusiKelas as $kelasName => $data)
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl p-4 border border-blue-100 hover:shadow-lg transition">
                <div class="text-center mb-3">
                    <span class="inline-block px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-bold text-lg">
                        {{ $kelasName }}
                    </span>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-600">Total:</span>
                        <span class="font-bold text-gray-800">{{ $data['total'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-600">Rata Juz:</span>
                        <span class="font-bold text-green-600">{{ $data['rata_juz'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-600">Progress:</span>
                        <span class="font-bold text-purple-600">{{ $data['rata_persen'] }}%</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>    

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Bar Chart
            const ctxBar = document.getElementById('barChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: @js($barChartLabels),
                    datasets: [{
                        label: 'Jumlah Setoran',
                        data: @js($barChartData),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10
                            }
                        }
                    }
                }
            });

            // Pie Chart
            const ctxPie = document.getElementById('pieChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: @js($pieChartLabels),
                    datasets: [{
                        data: @js($pieChartData),
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Line Chart
            const ctxLine = document.getElementById('lineChart').getContext('2d');
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: @js($lineChartLabels),
                    datasets: [{
                        label: 'Rata-rata Nilai',
                        data: @js($lineChartData),
                        borderColor: 'rgba(139, 92, 246, 1)',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: 'rgba(139, 92, 246, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</div>